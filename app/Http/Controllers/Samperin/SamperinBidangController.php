<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinBidang;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SamperinBidangController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SamperinBidang::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('bidang_kode', 'like', '%' . $search . '%')->orWhere('bidang_nama', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('bidang_status', (int) $request->status);
        }

        $bidangs = $query->orderBy('bidang_id')->paginate(5)->withQueryString();

        $totalBidang = SamperinBidang::count();

        $bidangAktif = SamperinBidang::where('bidang_status', 1)->count();

        $bidangNonaktif = SamperinBidang::where('bidang_status', 0)->count();

        return view('dashboard.master.bidang', compact('bidangs', 'totalBidang', 'bidangAktif', 'bidangNonaktif'));
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
                'bidang_kode' => ['required', 'string', 'max:50'],
                'bidang_nama' => ['required', 'string', 'max:255'],
                'bidang_status' => ['required', 'in:0,1'],
            ],
            [
                'bidang_kode.required' => 'Kode bidang wajib diisi.',
                'bidang_nama.required' => 'Nama bidang wajib diisi.',
                'bidang_status.required' => 'Status bidang wajib dipilih.',
                'bidang_status.in' => 'Status bidang tidak valid.',
            ],
        );

        if (SamperinBidang::where('bidang_kode', trim($validated['bidang_kode']))->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'bidang_kode' => 'Kode bidang tersebut sudah digunakan.',
                ]);
        }

        if (SamperinBidang::where('bidang_nama', trim($validated['bidang_nama']))->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'bidang_nama' => 'Nama bidang tersebut sudah digunakan.',
                ]);
        }

        try {
            SamperinBidang::create([
                'bidang_uid' => (string) Str::uuid(),
                'bidang_kode' => trim($validated['bidang_kode']),
                'bidang_nama' => trim($validated['bidang_nama']),
                'bidang_status' => (int) $validated['bidang_status'],
            ]);

            return redirect()->route('master.bidang.index')->with('success', 'Bidang berhasil ditambahkan.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN BIDANG STORE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'bidang' => 'Gagal menambahkan bidang.',
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, string $uid)
    {
        $bidang = SamperinBidang::where('bidang_uid', $uid)->firstOrFail();

        $validated = $request->validate(
            [
                'bidang_kode' => ['required', 'string', 'max:50'],
                'bidang_nama' => ['required', 'string', 'max:255'],
                'bidang_status' => ['required', 'in:0,1'],
            ],
            [
                'bidang_kode.required' => 'Kode bidang wajib diisi.',
                'bidang_nama.required' => 'Nama bidang wajib diisi.',
                'bidang_status.required' => 'Status bidang wajib dipilih.',
                'bidang_status.in' => 'Status bidang tidak valid.',
            ],
        );

        $kodeExists = SamperinBidang::where('bidang_kode', trim($validated['bidang_kode']))
            ->where('bidang_id', '!=', $bidang->bidang_id)
            ->exists();

        if ($kodeExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'bidang_kode' => 'Kode bidang tersebut sudah digunakan.',
                ]);
        }

        $namaExists = SamperinBidang::where('bidang_nama', trim($validated['bidang_nama']))
            ->where('bidang_id', '!=', $bidang->bidang_id)
            ->exists();

        if ($namaExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'bidang_nama' => 'Nama bidang tersebut sudah digunakan.',
                ]);
        }

        try {
            $bidang->update([
                'bidang_kode' => trim($validated['bidang_kode']),
                'bidang_nama' => trim($validated['bidang_nama']),
                'bidang_status' => (int) $validated['bidang_status'],
            ]);

            return redirect()->route('master.bidang.index')->with('success', 'Bidang berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN BIDANG UPDATE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'bidang' => 'Gagal memperbarui bidang.',
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(string $uid)
    {
        $bidang = SamperinBidang::where('bidang_uid', $uid)->firstOrFail();

        $bidang->bidang_status = (int) $bidang->bidang_status === 1 ? 0 : 1;

        $bidang->save();

        return back()->with('success', $bidang->bidang_status === 1 ? 'Bidang berhasil diaktifkan.' : 'Bidang berhasil dinonaktifkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $uid)
    {
        $bidang = SamperinBidang::where('bidang_uid', $uid)->firstOrFail();

        $jumlahPegawai = SamperinUser::where('user_bidang_id', $bidang->bidang_id)->count();

        if ($jumlahPegawai > 0) {
            return back()->withErrors([
                'bidang' => 'Bidang tidak dapat dihapus karena masih digunakan oleh ' . $jumlahPegawai . ' pegawai.',
            ]);
        }

        try {
            $bidang->delete();

            return back()->with('success', 'Bidang berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN BIDANG DELETE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'bidang' => 'Gagal menghapus bidang.',
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
        return view('dashboard.master.bidang-import');
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
            | Ambil INSERT INTO
            |--------------------------------------------------------------------------
            |
            | Mendukung dump seperti:
            |
            | insert into `sadarin_bidang`
            | (`bidang_id`,`bidang_nama`,...)
            | values (...),(...);
            |
            */

            $pattern = '/insert\s+into\s+[`"]?[^`"\s(]+[`"]?\s*' . '\((.*?)\)\s*values\s*(.*?);/is';

            preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER);

            if (empty($matches)) {
                return back()->withErrors([
                    'import' => 'Data INSERT bidang tidak ditemukan di dalam file SQL.',
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
                | Bersihkan nama kolom
                |--------------------------------------------------------------------------
                */

                $columns = array_map(function ($column) {
                    return trim($column, " \t\n\r\0\x0B`\"");
                }, explode(',', $columnsString));

                /*
                |--------------------------------------------------------------------------
                | Hanya proses tabel bidang
                |--------------------------------------------------------------------------
                */

                if (!in_array('bidang_id', $columns, true)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Pecah VALUES
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
                    | Pastikan data bidang
                    |--------------------------------------------------------------------------
                    */

                    if (!isset($data['bidang_id']) || !isset($data['bidang_nama'])) {
                        continue;
                    }

                    $bidangId = (int) $data['bidang_id'];

                    if ($bidangId <= 0) {
                        continue;
                    }

                    $nama = trim((string) $data['bidang_nama']);

                    if ($nama === '') {
                        continue;
                    }

                    /*
|--------------------------------------------------------------------------
| ID WAJIB DIPERTAHANKAN
|--------------------------------------------------------------------------
*/

                    $existing = SamperinBidang::where('bidang_id', $bidangId)->first();

                    $payload = [
                        'bidang_uid' => $existing?->bidang_uid ?? (string) Str::uuid(),

                        /*
    |--------------------------------------------------------------------------
    | KODE DIAMBIL DARI BIDANG_LINK
    |--------------------------------------------------------------------------
    */

                        'bidang_kode' => !empty($data['bidang_link']) ? trim($data['bidang_link']) : $existing?->bidang_kode ?? 'BID-' . str_pad((string) $bidangId, 3, '0', STR_PAD_LEFT),

                        'bidang_nama' => $nama,

                        'bidang_status' => isset($data['bidang_status']) ? (int) $data['bidang_status'] : 1,
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE / INSERT
                    |--------------------------------------------------------------------------
                    */

                    if ($existing) {
                        $existing->update([
                            'bidang_nama' => $payload['bidang_nama'],

                            'bidang_status' => $payload['bidang_status'],
                        ]);

                        $updated++;
                    } else {
                        /*
                        |--------------------------------------------------------------------------
                        | INSERT DENGAN ID DARI FILE
                        |--------------------------------------------------------------------------
                        */

                        DB::table('samperin_bidang')->insert([
                            'bidang_id' => $bidangId,
                            'bidang_uid' => $payload['bidang_uid'],
                            'bidang_kode' => $payload['bidang_kode'],
                            'bidang_nama' => $payload['bidang_nama'],
                            'bidang_status' => $payload['bidang_status'],
                        ]);

                        $inserted++;
                    }

                    $total++;
                }
            }

            if ($total === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data bidang yang berhasil dibaca dari file SQL.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Sesuaikan AUTO_INCREMENT
            |--------------------------------------------------------------------------
            */

            $maxId = SamperinBidang::max('bidang_id');

            if ($maxId) {
                DB::statement('ALTER TABLE samperin_bidang AUTO_INCREMENT = ' . ((int) $maxId + 1));
            }

            return redirect()
                ->route('master.bidang.index')
                ->with('success', "Import SQL berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN BIDANG IMPORT SQL', [
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
                    | SQL escaping: ''
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
            | Pastikan PhpSpreadsheet tersedia
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

            if (!isset($columnMap['bidang_id']) || !isset($columnMap['bidang_nama'])) {
                return back()->withErrors([
                    'import' => 'Excel wajib memiliki kolom bidang_id dan bidang_nama.',
                ]);
            }

            $inserted = 0;
            $updated = 0;

            foreach ($rows as $row) {
                $bidangId = (int) ($row[$columnMap['bidang_id']] ?? 0);

                $nama = trim((string) ($row[$columnMap['bidang_nama']] ?? ''));

                if ($bidangId <= 0 || $nama === '') {
                    continue;
                }

                $status = 1;

                if (isset($columnMap['bidang_status'])) {
                    $status = (int) ($row[$columnMap['bidang_status']] ?? 1);
                }

                $existing = SamperinBidang::where('bidang_id', $bidangId)->first();

                if ($existing) {
                    $existing->update([
                        'bidang_nama' => $nama,
                        'bidang_status' => $status,
                    ]);

                    $updated++;
                } else {
                    DB::table('samperin_bidang')->insert([
                        'bidang_id' => $bidangId,
                        'bidang_uid' => (string) Str::uuid(),
                        'bidang_kode' => 'BID-' . str_pad((string) $bidangId, 3, '0', STR_PAD_LEFT),
                        'bidang_nama' => $nama,
                        'bidang_status' => $status,
                    ]);

                    $inserted++;
                }
            }

            if ($inserted === 0 && $updated === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data bidang yang berhasil diimport.',
                ]);
            }

            $maxId = SamperinBidang::max('bidang_id');

            if ($maxId) {
                DB::statement('ALTER TABLE samperin_bidang AUTO_INCREMENT = ' . ((int) $maxId + 1));
            }

            return redirect()
                ->route('master.bidang.index')
                ->with('success', "Import Excel berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN BIDANG IMPORT EXCEL', [
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