<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinGolongan;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SamperinGolonganController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SamperinGolongan::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('golongan_kode', 'like', '%' . $search . '%')
                    ->orWhere('golongan_nama', 'like', '%' . $search . '%')
                    ->orWhere('golongan_pangkat', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('golongan_status', (int) $request->status);
        }

        $golongans = $query->orderBy('golongan_id')->paginate(5)->withQueryString();

        $totalGolongan = SamperinGolongan::count();

        $golonganAktif = SamperinGolongan::where('golongan_status', 1)->count();

        $golonganNonaktif = SamperinGolongan::where('golongan_status', 0)->count();

        return view('dashboard.master.golongan', compact('golongans', 'totalGolongan', 'golonganAktif', 'golonganNonaktif'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'golongan_nama' => ['required', 'string', 'max:100'],

                'golongan_pangkat' => ['nullable', 'string', 'max:100'],

                'golongan_status' => ['required', 'in:0,1'],
            ],
            [
                'golongan_nama.required' => 'Nama golongan wajib diisi.',

                'golongan_pangkat.string' => 'Pangkat golongan tidak valid.',

                'golongan_status.required' => 'Status golongan wajib dipilih.',

                'golongan_status.in' => 'Status golongan tidak valid.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | CEK NAMA
        |--------------------------------------------------------------------------
        */

        if (SamperinGolongan::where('golongan_nama', trim($validated['golongan_nama']))->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'golongan_nama' => 'Nama golongan tersebut sudah digunakan.',
                ]);
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | GENERATE KODE OTOMATIS
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | GOL-001
            | GOL-002
            | GOL-003
            |
            */

            $lastId = SamperinGolongan::max('golongan_id');

            $nextId = ((int) $lastId) + 1;

            $kode = 'GOL-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);

            /*
            |--------------------------------------------------------------------------
            | PASTIKAN KODE UNIK
            |--------------------------------------------------------------------------
            */

            while (SamperinGolongan::where('golongan_kode', $kode)->exists()) {
                $nextId++;

                $kode = 'GOL-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);
            }

            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            SamperinGolongan::create([
                'golongan_uid' => (string) Str::uuid(),

                'golongan_kode' => $kode,

                'golongan_nama' => trim($validated['golongan_nama']),

                'golongan_pangkat' => isset($validated['golongan_pangkat']) ? trim($validated['golongan_pangkat']) : null,

                'golongan_status' => (int) $validated['golongan_status'],
            ]);

            return redirect()->route('master.golongan.index')->with('success', 'Golongan berhasil ditambahkan.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN GOLONGAN STORE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'golongan' => 'Gagal menambahkan golongan: ' . $e->getMessage(),
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, int $id)
    {
        $golongan = SamperinGolongan::where('golongan_id', $id)->firstOrFail();

        $validated = $request->validate(
            [
                'golongan_nama' => ['required', 'string', 'max:100'],

                'golongan_pangkat' => ['nullable', 'string', 'max:100'],

                'golongan_status' => ['required', 'in:0,1'],
            ],
            [
                'golongan_nama.required' => 'Nama golongan wajib diisi.',

                'golongan_status.required' => 'Status golongan wajib dipilih.',

                'golongan_status.in' => 'Status golongan tidak valid.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | CEK NAMA DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $namaExists = SamperinGolongan::where('golongan_nama', trim($validated['golongan_nama']))
            ->where('golongan_id', '!=', $golongan->golongan_id)
            ->exists();

        if ($namaExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'golongan_nama' => 'Nama golongan tersebut sudah digunakan.',
                ]);
        }

        try {
            $golongan->update([
                'golongan_nama' => trim($validated['golongan_nama']),

                'golongan_pangkat' => isset($validated['golongan_pangkat']) ? trim($validated['golongan_pangkat']) : null,

                'golongan_status' => (int) $validated['golongan_status'],
            ]);

            return redirect()->route('master.golongan.index')->with('success', 'Golongan berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN GOLONGAN UPDATE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'golongan' => 'Gagal memperbarui golongan: ' . $e->getMessage(),
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(int $id)
    {
        $golongan = SamperinGolongan::where('golongan_id', $id)->firstOrFail();

        $golongan->golongan_status = (int) $golongan->golongan_status === 1 ? 0 : 1;

        $golongan->save();

        return back()->with('success', $golongan->golongan_status === 1 ? 'Golongan berhasil diaktifkan.' : 'Golongan berhasil dinonaktifkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(int $id)
    {
        $golongan = SamperinGolongan::where('golongan_id', $id)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CEK RELASI PEGAWAI
        |--------------------------------------------------------------------------
        */

        $jumlahPegawai = SamperinUser::where('user_golongan_id', $golongan->golongan_id)->count();

        if ($jumlahPegawai > 0) {
            return back()->withErrors([
                'golongan' => 'Golongan tidak dapat dihapus karena masih digunakan oleh ' . $jumlahPegawai . ' pegawai.',
            ]);
        }

        try {
            $golongan->delete();

            return back()->with('success', 'Golongan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN GOLONGAN DELETE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'golongan' => 'Gagal menghapus golongan: ' . $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN IMPORT
    |--------------------------------------------------------------------------
    */

    public function import()
    {
        return view('dashboard.master.golongan-import');
    }

    /*
    |--------------------------------------------------------------------------
    | PROSES IMPORT
    |--------------------------------------------------------------------------
    */

    public function importProcess(Request $request)
    {
        $request->validate(
            [
                'file' => ['required', 'file', 'max:10240'],
            ],
            [
                'file.required' => 'File import wajib dipilih.',

                'file.file' => 'File import tidak valid.',

                'file.max' => 'Ukuran file maksimal 10 MB.',
            ],
        );

        $file = $request->file('file');

        if (!$file || !$file->isValid()) {
            return back()->withErrors([
                'import' => 'File tidak dapat dibaca.',
            ]);
        }

        $extension = strtolower($file->getClientOriginalExtension());

        /*
        |--------------------------------------------------------------------------
        | SQL
        |--------------------------------------------------------------------------
        */

        if ($extension === 'sql') {
            return $this->importSql($file);
        }

        /*
        |--------------------------------------------------------------------------
        | EXCEL
        |--------------------------------------------------------------------------
        */

        if (in_array($extension, ['xls', 'xlsx'], true)) {
            return $this->importExcel($file);
        }

        return back()->withErrors([
            'import' => 'Format file tidak didukung. Gunakan file SQL, XLS, atau XLSX.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT SQL
    |--------------------------------------------------------------------------
    */

    private function importSql($file)
    {
        try {
            $sql = file_get_contents($file->getRealPath());

            if ($sql === false || trim($sql) === '') {
                return back()->withErrors([
                    'import' => 'File SQL kosong atau tidak dapat dibaca.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            $pattern = '/insert\s+into\s+[`"]?[^`"\s(]+[`"]?\s*' . '\((.*?)\)\s*values\s*(.*?);/is';

            preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER);

            if (empty($matches)) {
                return back()->withErrors([
                    'import' => 'Data INSERT golongan tidak ditemukan di dalam file SQL.',
                ]);
            }

            $total = 0;
            $inserted = 0;
            $updated = 0;

            foreach ($matches as $match) {
                $columnsString = trim($match[1]);

                $valuesString = trim($match[2]);

                /*
                |--------------------------------------------------------------------------
                | COLUMNS
                |--------------------------------------------------------------------------
                */

                $columns = array_map(function ($column) {
                    return trim($column, " \t\n\r\0\x0B`\"");
                }, explode(',', $columnsString));

                /*
                |--------------------------------------------------------------------------
                | PASTIKAN DATA GOLONGAN
                |--------------------------------------------------------------------------
                */

                if (!in_array('golongan_id', $columns, true)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | VALUES
                |--------------------------------------------------------------------------
                */

                $rows = $this->parseSqlValues($valuesString);

                foreach ($rows as $row) {
                    if (count($row) !== count($columns)) {
                        continue;
                    }

                    $data = [];

                    foreach ($columns as $index => $column) {
                        $data[$column] = $row[$index];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | VALIDASI
                    |--------------------------------------------------------------------------
                    */

                    if (!isset($data['golongan_id']) || !isset($data['golongan_nama'])) {
                        continue;
                    }

                    $golonganId = (int) $data['golongan_id'];

                    if ($golonganId <= 0) {
                        continue;
                    }

                    $nama = trim((string) $data['golongan_nama']);

                    if ($nama === '') {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | DATA
                    |--------------------------------------------------------------------------
                    */

                    $existing = SamperinGolongan::where('golongan_id', $golonganId)->first();

                    $kode = !empty($data['golongan_kode']) ? trim((string) $data['golongan_kode']) : null;

                    /*
                    |--------------------------------------------------------------------------
                    | UID
                    |--------------------------------------------------------------------------
                    */

                    $uid = !empty($data['golongan_uid']) ? trim((string) $data['golongan_uid']) : $existing?->golongan_uid ?? (string) Str::uuid();

                    /*
                    |--------------------------------------------------------------------------
                    | KODE
                    |--------------------------------------------------------------------------
                    */

                    if (!$kode) {
                        $kode = $existing?->golongan_kode ?? 'GOL-' . str_pad((string) $golonganId, 3, '0', STR_PAD_LEFT);
                    }

                    $pangkat = isset($data['golongan_pangkat']) ? ($data['golongan_pangkat'] !== null ? trim((string) $data['golongan_pangkat']) : null) : null;

                    $status = isset($data['golongan_status']) ? (int) $data['golongan_status'] : 1;

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */

                    if ($existing) {
                        $existing->update([
                            'golongan_kode' => $kode,

                            'golongan_nama' => $nama,

                            'golongan_pangkat' => $pangkat,

                            'golongan_status' => $status,
                        ]);

                        $updated++;
                    } else {
                        /*
                        |--------------------------------------------------------------------------
                        | INSERT DENGAN ID FILE
                        |--------------------------------------------------------------------------
                        */

                        DB::table('samperin_golongan')->insert([
                            'golongan_id' => $golonganId,

                            'golongan_uid' => $uid,

                            'golongan_kode' => $kode,

                            'golongan_nama' => $nama,

                            'golongan_pangkat' => $pangkat,

                            'golongan_status' => $status,
                        ]);

                        $inserted++;
                    }

                    $total++;
                }
            }

            if ($total === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data golongan yang berhasil dibaca dari file SQL.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | AUTO INCREMENT
            |--------------------------------------------------------------------------
            */

            $maxId = SamperinGolongan::max('golongan_id');

            if ($maxId) {
                DB::statement('ALTER TABLE samperin_golongan AUTO_INCREMENT = ' . ((int) $maxId + 1));
            }

            return redirect()
                ->route('master.golongan.index')
                ->with('success', "Import SQL berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN GOLONGAN IMPORT SQL', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'import' => 'Import SQL gagal: ' . $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PARSER SQL VALUES
    |--------------------------------------------------------------------------
    */

    private function parseSqlValues(string $values): array
    {
        $rows = [];

        $currentRow = [];

        $currentValue = '';

        $insideString = false;

        $quote = null;

        $length = strlen($values);

        for ($i = 0; $i < $length; $i++) {
            $char = $values[$i];

            /*
            |--------------------------------------------------------------------------
            | STRING
            |--------------------------------------------------------------------------
            */

            if ($insideString) {
                if ($char === '\\' && $i + 1 < $length) {
                    $currentValue .= $char;

                    $currentValue .= $values[++$i];

                    continue;
                }

                if ($char === $quote) {
                    /*
                    | SQL escaping ''
                    */

                    if ($i + 1 < $length && $values[$i + 1] === $quote) {
                        $currentValue .= $quote;

                        $i++;

                        continue;
                    }

                    $insideString = false;

                    $quote = null;

                    continue;
                }

                $currentValue .= $char;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | MULAI STRING
            |--------------------------------------------------------------------------
            */

            if ($char === "'" || $char === '"') {
                $insideString = true;

                $quote = $char;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | MULAI ROW
            |--------------------------------------------------------------------------
            */

            if ($char === '(') {
                $currentRow = [];

                $currentValue = '';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | PEMISAH KOLOM
            |--------------------------------------------------------------------------
            */

            if ($char === ',') {
                $currentRow[] = $this->cleanSqlValue($currentValue);

                $currentValue = '';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | AKHIR ROW
            |--------------------------------------------------------------------------
            */

            if ($char === ')') {
                $currentRow[] = $this->cleanSqlValue($currentValue);

                $rows[] = $currentRow;

                $currentRow = [];

                $currentValue = '';

                continue;
            }

            $currentValue .= $char;
        }

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN SQL VALUE
    |--------------------------------------------------------------------------
    */

    private function cleanSqlValue(string $value): mixed
    {
        $value = trim($value);

        if (strcasecmp($value, 'NULL') === 0) {
            return null;
        }

        return trim($value, " \t\n\r\0\x0B");
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT EXCEL
    |--------------------------------------------------------------------------
    */

    private function importExcel($file)
    {
        try {
            /*
            |--------------------------------------------------------------------------
            | CHECK PHPSPREADSHEET
            |--------------------------------------------------------------------------
            */

            if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                return back()->withErrors([
                    'import' => 'PhpSpreadsheet belum terpasang. Jalankan: composer require phpoffice/phpspreadsheet',
                ]);
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());

            $sheet = $spreadsheet->getActiveSheet();

            $rows = $sheet->toArray(null, true, true, true);

            if (count($rows) < 2) {
                return back()->withErrors([
                    'import' => 'File Excel tidak memiliki data.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | HEADER
            |--------------------------------------------------------------------------
            */

            $header = array_shift($rows);

            $header = array_map(function ($value) {
                return strtolower(trim((string) $value));
            }, $header);

            $columnMap = [];

            foreach ($header as $key => $name) {
                $columnMap[$name] = $key;
            }

            if (!isset($columnMap['golongan_id']) || !isset($columnMap['golongan_nama'])) {
                return back()->withErrors([
                    'import' => 'Excel wajib memiliki kolom golongan_id dan golongan_nama.',
                ]);
            }

            $inserted = 0;
            $updated = 0;

            foreach ($rows as $row) {
                $golonganId = (int) ($row[$columnMap['golongan_id']] ?? 0);

                $nama = trim((string) ($row[$columnMap['golongan_nama']] ?? ''));

                if ($golonganId <= 0 || $nama === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | KODE
                |--------------------------------------------------------------------------
                */

                $kode = null;

                if (isset($columnMap['golongan_kode'])) {
                    $kode = trim((string) ($row[$columnMap['golongan_kode']] ?? ''));
                }

                /*
                |--------------------------------------------------------------------------
                | PANGKAT
                |--------------------------------------------------------------------------
                */

                $pangkat = null;

                if (isset($columnMap['golongan_pangkat'])) {
                    $pangkatValue = $row[$columnMap['golongan_pangkat']] ?? null;

                    $pangkat = $pangkatValue !== null && trim((string) $pangkatValue) !== '' ? trim((string) $pangkatValue) : null;
                }

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                $status = 1;

                if (isset($columnMap['golongan_status'])) {
                    $status = (int) ($row[$columnMap['golongan_status']] ?? 1);
                }

                /*
                |--------------------------------------------------------------------------
                | EXISTING
                |--------------------------------------------------------------------------
                */

                $existing = SamperinGolongan::where('golongan_id', $golonganId)->first();

                if ($existing) {
                    $existing->update([
                        'golongan_kode' => $kode !== '' ? $kode : $existing->golongan_kode,

                        'golongan_nama' => $nama,

                        'golongan_pangkat' => $pangkat,

                        'golongan_status' => $status,
                    ]);

                    $updated++;
                } else {
                    if (!$kode) {
                        $kode = 'GOL-' . str_pad((string) $golonganId, 3, '0', STR_PAD_LEFT);
                    }

                    DB::table('samperin_golongan')->insert([
                        'golongan_id' => $golonganId,

                        'golongan_uid' => (string) Str::uuid(),

                        'golongan_kode' => $kode,

                        'golongan_nama' => $nama,

                        'golongan_pangkat' => $pangkat,

                        'golongan_status' => $status,
                    ]);

                    $inserted++;
                }
            }

            if ($inserted === 0 && $updated === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data golongan yang berhasil diimport.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | AUTO INCREMENT
            |--------------------------------------------------------------------------
            */

            $maxId = SamperinGolongan::max('golongan_id');

            if ($maxId) {
                DB::statement('ALTER TABLE samperin_golongan AUTO_INCREMENT = ' . ((int) $maxId + 1));
            }

            return redirect()
                ->route('master.golongan.index')
                ->with('success', "Import Excel berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN GOLONGAN IMPORT EXCEL', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'import' => 'Import Excel gagal: ' . $e->getMessage(),
            ]);
        }
    }
}
