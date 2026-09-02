<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinApi;
use App\Models\SamperinFolder;
use App\Models\SamperinUser;
use App\Models\SamperinUserFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SamperinFotoImportController extends Controller
{
    /**
     * URL dasar sumber foto SADARIN.
     */
    private string $sadarinBaseUrl = 'https://sadarin.saplarin.site';

    /**
     * Halaman import foto.
     */
    public function index()
    {
        return view('dashboard.data-pegawai.import-foto');
    }

    /**
     * Proses import foto dari SQL SADARIN.
     */
    public function import(Request $request)
    {
        $request->validate(
            [
                'file_sql' => ['required', 'file', 'mimes:sql,txt', 'max:51200'],
            ],
            [
                'file_sql.required' => 'File SQL wajib dipilih.',
                'file_sql.file' => 'File yang dikirim tidak valid.',
                'file_sql.mimes' => 'File harus berupa SQL.',
                'file_sql.max' => 'Ukuran file maksimal 50 MB.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | CEK API ARINDRIVE
        |--------------------------------------------------------------------------
        */

        $api = SamperinApi::where('api_kode', 'ARINDRIVE')->where('api_status', true)->first();

        if (!$api) {
            return back()->withInput()->with('error', 'API ARINDRIVE aktif tidak ditemukan di Setting API.');
        }

        if (empty($api->api_url)) {
            return back()->withInput()->with('error', 'URL API ARINDRIVE belum diatur.');
        }

        if (empty($api->api_token)) {
            return back()->withInput()->with('error', 'Token API ARINDRIVE belum diatur.');
        }

        /*
        |--------------------------------------------------------------------------
        | BACA FILE SQL
        |--------------------------------------------------------------------------
        */

        $sql = file_get_contents($request->file('file_sql')->getRealPath());

        if ($sql === false || trim($sql) === '') {
            return back()->withInput()->with('error', 'File SQL kosong atau tidak dapat dibaca.');
        }

        /*
        |--------------------------------------------------------------------------
        | PARSE DATA sadarin_user
        |--------------------------------------------------------------------------
        */

        try {
            $rows = $this->parseSadarinUserSql($sql);
        } catch (\Throwable $e) {
            Log::error('Import foto SAMPERIN - SQL parser error', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Gagal membaca struktur data SQL: ' . $e->getMessage());
        }

        if (empty($rows)) {
            return back()->withInput()->with('error', 'Tidak ditemukan data INSERT untuk tabel sadarin_user.');
        }

        /*
        |--------------------------------------------------------------------------
        | COUNTER
        |--------------------------------------------------------------------------
        */

        $total = count($rows);

        $diproses = 0;
        $berhasil = 0;
        $skip = 0;
        $pegawaiTidakDitemukan = 0;
        $fotoKosong = 0;
        $folderTidakDitemukan = 0;
        $downloadGagal = 0;
        $uploadGagal = 0;
        $databaseGagal = 0;

        $errors = [];

        /*
        |--------------------------------------------------------------------------
        | PROSES SATU PER SATU
        |--------------------------------------------------------------------------
        */

        foreach ($rows as $row) {
            $diproses++;

            $sourceUserId = $this->cleanValue($row['user_id'] ?? null);
            $fotoPath = $this->cleanValue($row['user_foto'] ?? null);

            /*
            |--------------------------------------------------------------------------
            | USER ID WAJIB ADA
            |--------------------------------------------------------------------------
            */

            if ($sourceUserId === null) {
                $errors[] = 'Baris tanpa user_id.';
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | FOTO KOSONG
            |--------------------------------------------------------------------------
            */

            if ($fotoPath === null) {
                $fotoKosong++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CARI PEGAWAI SAMPERIN BERDASARKAN USER_ID
            |--------------------------------------------------------------------------
            |
            | TIDAK menggunakan NIP.
            | TIDAK menggunakan NIK.
            |
            */

            $user = SamperinUser::where('user_id', $sourceUserId)->first();

            if (!$user) {
                $pegawaiTidakDitemukan++;

                $errors[] = "user_id {$sourceUserId}: pegawai tidak ditemukan di SAMPERIN.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CEK FOTO YANG SUDAH ADA
            |--------------------------------------------------------------------------
            */

            $fotoExisting = SamperinUserFoto::where('user_foto_user_uid', $user->user_uid)->first();

            /*
            |--------------------------------------------------------------------------
            | JIKA SUDAH HASIL MIGRASI, SKIP
            |--------------------------------------------------------------------------
            |
            | Path lama SADARIN:
            | assets/foto_pegawai/...
            |
            | Kalau bukan path lama tersebut, kita anggap sudah
            | memiliki hasil upload/migrasi.
            |
            */

            if ($fotoExisting && !empty($fotoExisting->user_foto_file) && !Str::startsWith(ltrim($fotoExisting->user_foto_file, '/'), 'assets/foto_pegawai/')) {
                $skip++;
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | JENIS KERJA WAJIB ADA
            |--------------------------------------------------------------------------
            */

            if (empty($user->user_jenis_kerja_id)) {
                $errors[] = "user_id {$sourceUserId}: user_jenis_kerja_id kosong.";
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CARI FOLDER FOTO SESUAI JENIS KERJA
            |--------------------------------------------------------------------------
            */

            $folder = SamperinFolder::where('folder_kode', 'FOTO_PEGAWAI')->where('folder_jenis', 'foto')->where('folder_jenis_kerja_id', $user->user_jenis_kerja_id)->where('folder_status', true)->first();

            if (!$folder) {
                $folderTidakDitemukan++;

                $errors[] = "user_id {$sourceUserId}: folder FOTO_PEGAWAI untuk jenis kerja {$user->user_jenis_kerja_id} tidak ditemukan.";

                continue;
            }

            if (empty($folder->folder_drive_id)) {
                $folderTidakDitemukan++;

                $errors[] = "user_id {$sourceUserId}: Google Drive Folder ID FOTO_PEGAWAI kosong.";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | NORMALISASI PATH FOTO
            |--------------------------------------------------------------------------
            */

            $fotoPath = ltrim(trim($fotoPath), '/');

            /*
            |--------------------------------------------------------------------------
            | HANYA IZINKAN PATH FOTO
            |--------------------------------------------------------------------------
            */

            if (!Str::startsWith(strtolower($fotoPath), 'assets/foto_pegawai/')) {
                $errors[] = "user_id {$sourceUserId}: path foto tidak valid ({$fotoPath}).";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | BENTUK URL SUMBER
            |--------------------------------------------------------------------------
            */

            $sourceUrl = rtrim($this->sadarinBaseUrl, '/') . '/' . $fotoPath;

            /*
            |--------------------------------------------------------------------------
            | DOWNLOAD / COPY FOTO DARI SADARIN
            |--------------------------------------------------------------------------
            |
            | PENTING:
            | File SADARIN TIDAK DIHAPUS.
            |
            */

            try {
                $response = Http::timeout(60)->retry(2, 500)->get($sourceUrl);

                if (!$response->successful()) {
                    $downloadGagal++;

                    $errors[] = "user_id {$sourceUserId}: gagal mengambil foto dari SADARIN ({$response->status()}).";

                    continue;
                }

                $fileContent = $response->body();

                if ($fileContent === '') {
                    $downloadGagal++;

                    $errors[] = "user_id {$sourceUserId}: foto dari SADARIN kosong.";

                    continue;
                }
            } catch (\Throwable $e) {
                $downloadGagal++;

                $errors[] = "user_id {$sourceUserId}: gagal download foto - {$e->getMessage()}";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | NAMA FILE
            |--------------------------------------------------------------------------
            */

            $originalFilename = basename($fotoPath);

            $originalFilename = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalFilename);

            if (!$originalFilename) {
                $originalFilename = 'foto_' . $sourceUserId . '.jpg';
            }

            /*
            |--------------------------------------------------------------------------
            | MIME
            |--------------------------------------------------------------------------
            */

            $mime = $response->header('Content-Type');

            if (!$mime || Str::contains($mime, ';')) {
                $mime = $this->mimeFromExtension(pathinfo($originalFilename, PATHINFO_EXTENSION));
            }

            /*
            |--------------------------------------------------------------------------
            | UPLOAD KE ARINDRIVE
            |--------------------------------------------------------------------------
            */

            try {
                $uploadResponse = Http::withToken($api->api_token)
                    ->timeout(120)
                    ->attach('file', $fileContent, $originalFilename)
                    ->post(rtrim($api->api_url, '/') . '/api/upload-drive', [
                        'folder_id' => $folder->folder_drive_id,
                        'filename' => $originalFilename,
                        'source_app' => 'samperin',
                        'folder' => $folder->folder_prefix ?: 'foto-pegawai',
                        'reference_id' => $user->user_uid . '-foto',
                    ]);

                if (!$uploadResponse->successful()) {
                    $uploadGagal++;

                    $errors[] = "user_id {$sourceUserId}: ArinDrive HTTP {$uploadResponse->status()} - " . $uploadResponse->body();

                    continue;
                }

                $result = $uploadResponse->json();

                if (!($result['success'] ?? false)) {
                    $uploadGagal++;

                    $errors[] = "user_id {$sourceUserId}: ArinDrive menolak upload - " . ($result['message'] ?? 'Upload gagal.');

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | AMBIL HASIL FILE DARI ARINDRIVE
                |--------------------------------------------------------------------------
                */

                $driveFile = $result['data']['url'] ?? ($result['data']['file_url'] ?? ($result['data']['path'] ?? ($result['data']['file'] ?? null)));

                if (!$driveFile) {
                    $uploadGagal++;

                    $errors[] = "user_id {$sourceUserId}: upload berhasil tetapi hasil file dari ArinDrive tidak ditemukan.";

                    continue;
                }
            } catch (\Throwable $e) {
                $uploadGagal++;

                $errors[] = "user_id {$sourceUserId}: error upload ArinDrive - {$e->getMessage()}";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN KE samperin_user_foto
            |--------------------------------------------------------------------------
            */

            try {
                DB::transaction(function () use (&$fotoExisting, $user, $driveFile, $originalFilename, $mime, $fileContent) {
                    $data = [
                        'user_foto_uid' => $fotoExisting?->user_foto_uid ?? (string) Str::uuid(),

                        'user_foto_user_uid' => $user->user_uid,

                        'user_foto_file' => $driveFile,

                        'user_foto_nama' => $originalFilename,

                        'user_foto_mime' => $mime,

                        'user_foto_size' => strlen($fileContent),

                        'user_foto_tanggal' => now()->format('Y-m-d'),

                        'user_foto_keterangan' => 'Duplikasi foto dari SADARIN ke ArinDrive',
                    ];

                    if ($fotoExisting) {
                        $fotoExisting->update($data);
                    } else {
                        $fotoExisting = SamperinUserFoto::create($data);
                    }
                });

                $berhasil++;
            } catch (\Throwable $e) {
                $databaseGagal++;

                $errors[] = "user_id {$sourceUserId}: gagal menyimpan database - {$e->getMessage()}";

                continue;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | HASIL
        |--------------------------------------------------------------------------
        */

        $message = 'Import foto selesai. ' . "Total: {$total}, " . "Berhasil: {$berhasil}, " . "Skip: {$skip}, " . "Foto kosong: {$fotoKosong}, " . "Pegawai tidak ditemukan: {$pegawaiTidakDitemukan}, " . "Folder tidak ditemukan: {$folderTidakDitemukan}, " . "Download gagal: {$downloadGagal}, " . "Upload gagal: {$uploadGagal}, " . "Database gagal: {$databaseGagal}.";

        return back()->with('success', $message)->with('import_errors', $errors);
    }

    /**
     * Parse INSERT INTO sadarin_user dari SQL dump.
     */
    private function parseSadarinUserSql(string $sql): array
    {
        $rows = [];

        /*
        |--------------------------------------------------------------------------
        | CARI SEMUA INSERT sadarin_user
        |--------------------------------------------------------------------------
        */

        $pattern = '/insert\s+into\s+[`"]?sadarin_user[`"]?\s*' . '\((.*?)\)\s*values\s*(.*?);/is';

        preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $columnString = trim($match[1]);
            $valuesString = trim($match[2]);

            $columns = $this->splitSqlValues($columnString, false);

            $columns = array_map(function ($column) {
                return trim(trim($column), '`" ');
            }, $columns);

            /*
            |--------------------------------------------------------------------------
            | AMBIL SETIAP (...) DATA
            |--------------------------------------------------------------------------
            */

            $tuples = $this->extractSqlTuples($valuesString);

            foreach ($tuples as $tuple) {
                $values = $this->splitSqlValues($tuple, true);

                if (count($values) !== count($columns)) {
                    continue;
                }

                $row = [];

                foreach ($columns as $index => $column) {
                    $row[$column] = $this->decodeSqlValue($values[$index]);
                }

                /*
                |--------------------------------------------------------------------------
                | KITA HANYA BUTUH INI
                |--------------------------------------------------------------------------
                */

                $rows[] = [
                    'user_id' => $row['user_id'] ?? null,

                    'user_foto' => $row['user_foto'] ?? null,
                ];
            }
        }

        return $rows;
    }

    /**
     * Extract tuple:
     * (1,'ABC','...'),(2,'DEF','...')
     */
    private function extractSqlTuples(string $values): array
    {
        $tuples = [];

        $length = strlen($values);

        $insideString = false;
        $escaped = false;
        $depth = 0;
        $start = null;

        for ($i = 0; $i < $length; $i++) {
            $char = $values[$i];

            if ($escaped) {
                $escaped = false;
                continue;
            }

            if ($char === '\\') {
                $escaped = true;
                continue;
            }

            if ($char === "'") {
                if ($insideString && $i + 1 < $length && $values[$i + 1] === "'") {
                    $i++;
                    continue;
                }

                $insideString = !$insideString;
                continue;
            }

            if ($insideString) {
                continue;
            }

            if ($char === '(') {
                if ($depth === 0) {
                    $start = $i + 1;
                }

                $depth++;
                continue;
            }

            if ($char === ')') {
                $depth--;

                if ($depth === 0 && $start !== null) {
                    $tuples[] = substr($values, $start, $i - $start);

                    $start = null;
                }
            }
        }

        return $tuples;
    }

    /**
     * Split nilai SQL berdasarkan koma di luar string.
     */
    private function splitSqlValues(string $string, bool $isValueString = true): array
    {
        $parts = [];

        $current = '';
        $length = strlen($string);

        $insideString = false;
        $escaped = false;

        for ($i = 0; $i < $length; $i++) {
            $char = $string[$i];

            if ($escaped) {
                $current .= $char;
                $escaped = false;

                continue;
            }

            if ($char === '\\') {
                $current .= $char;
                $escaped = true;

                continue;
            }

            if ($char === "'") {
                /*
                |--------------------------------------------------------------------------
                | SQL escaped quote ''
                |--------------------------------------------------------------------------
                */

                if ($insideString && $i + 1 < $length && $string[$i + 1] === "'") {
                    $current .= "''";
                    $i++;

                    continue;
                }

                $insideString = !$insideString;

                $current .= $char;

                continue;
            }

            if ($char === ',' && !$insideString) {
                $parts[] = trim($current);
                $current = '';

                continue;
            }

            $current .= $char;
        }

        $parts[] = trim($current);

        return $parts;
    }

    /**
     * Decode nilai SQL.
     */
    private function decodeSqlValue(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if (strtoupper($value) === 'NULL') {
            return null;
        }

        if (strlen($value) >= 2 && $value[0] === "'" && $value[strlen($value) - 1] === "'") {
            $value = substr($value, 1, -1);

            /*
            |--------------------------------------------------------------------------
            | SQL escape
            |--------------------------------------------------------------------------
            */

            $value = str_replace("''", "'", $value);

            $value = str_replace(['\\\'', '\\\\'], ["'", '\\'], $value);
        }

        return $this->cleanValue($value);
    }

    /**
     * Bersihkan nilai.
     */
    private function cleanValue($value): mixed
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if ($value === '-' || strtolower($value) === 'null') {
            return null;
        }

        return $value;
    }

    /**
     * MIME berdasarkan extension.
     */
    private function mimeFromExtension(?string $extension): string
    {
        return match (strtolower((string) $extension)) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
            default => 'application/octet-stream',
        };
    }
}