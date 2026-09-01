<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinEselon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SamperinEselonController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SamperinEselon::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('eselon_kode', 'like', '%' . $search . '%')->orWhere('eselon_nama', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('eselon_status', (int) $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $eselons = $query->orderBy('eselon_id')->paginate(5)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalEselon = SamperinEselon::count();

        $eselonAktif = SamperinEselon::where('eselon_status', 1)->count();

        $eselonNonaktif = SamperinEselon::where('eselon_status', 0)->count();

        return view('dashboard.master.eselon', compact('eselons', 'totalEselon', 'eselonAktif', 'eselonNonaktif'));
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
                'eselon_nama' => ['required', 'string', 'max:100'],

                'eselon_status' => ['required', 'in:0,1'],
            ],
            [
                'eselon_nama.required' => 'Nama eselon wajib diisi.',

                'eselon_status.required' => 'Status eselon wajib dipilih.',

                'eselon_status.in' => 'Status eselon tidak valid.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | CEK NAMA DUPLIKAT
        |--------------------------------------------------------------------------
        */

        if (SamperinEselon::where('eselon_nama', trim($validated['eselon_nama']))->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'eselon_nama' => 'Nama eselon tersebut sudah digunakan.',
                ]);
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | GENERATE KODE OTOMATIS
            |--------------------------------------------------------------------------
            |
            | Contoh:
            |
            | ESELON-001
            | ESELON-002
            | ESELON-003
            |
            */

            $lastId = SamperinEselon::max('eselon_id');

            $nextNumber = ((int) $lastId) + 1;

            $kode = 'ESELON-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

            /*
            |--------------------------------------------------------------------------
            | PASTIKAN KODE UNIK
            |--------------------------------------------------------------------------
            */

            while (SamperinEselon::where('eselon_kode', $kode)->exists()) {
                $nextNumber++;

                $kode = 'ESELON-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            }

            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            SamperinEselon::create([
                'eselon_uid' => (string) Str::uuid(),

                'eselon_kode' => $kode,

                'eselon_nama' => trim($validated['eselon_nama']),

                'eselon_status' => (int) $validated['eselon_status'],
            ]);

            return redirect()->route('master.eselon.index')->with('success', 'Eselon berhasil ditambahkan.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN ESELON STORE', [
                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'eselon' => 'Gagal menambahkan eselon: ' . $e->getMessage(),
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
        $eselon = SamperinEselon::where('eselon_uid', $uid)->firstOrFail();

        $validated = $request->validate(
            [
                'eselon_nama' => ['required', 'string', 'max:100'],

                'eselon_status' => ['required', 'in:0,1'],
            ],
            [
                'eselon_nama.required' => 'Nama eselon wajib diisi.',

                'eselon_status.required' => 'Status eselon wajib dipilih.',

                'eselon_status.in' => 'Status eselon tidak valid.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT NAMA
        |--------------------------------------------------------------------------
        */

        $namaExists = SamperinEselon::where('eselon_nama', trim($validated['eselon_nama']))
            ->where('eselon_id', '!=', $eselon->eselon_id)
            ->exists();

        if ($namaExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'eselon_nama' => 'Nama eselon tersebut sudah digunakan.',
                ]);
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | KODE TIDAK DIUBAH
            |--------------------------------------------------------------------------
            |
            | Kode dibuat otomatis saat pertama kali dibuat.
            |
            */

            $eselon->update([
                'eselon_nama' => trim($validated['eselon_nama']),

                'eselon_status' => (int) $validated['eselon_status'],
            ]);

            return redirect()->route('master.eselon.index')->with('success', 'Eselon berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN ESELON UPDATE', [
                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'eselon' => 'Gagal memperbarui eselon: ' . $e->getMessage(),
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
        $eselon = SamperinEselon::where('eselon_uid', $uid)->firstOrFail();

        try {
            $eselon->eselon_status = (int) $eselon->eselon_status === 1 ? 0 : 1;

            $eselon->save();

            return back()->with('success', $eselon->eselon_status === 1 ? 'Eselon berhasil diaktifkan.' : 'Eselon berhasil dinonaktifkan.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN ESELON TOGGLE STATUS', [
                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'eselon' => 'Gagal mengubah status eselon: ' . $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $uid)
    {
        $eselon = SamperinEselon::where('eselon_uid', $uid)->firstOrFail();

        try {
            $eselon->delete();

            return back()->with('success', 'Eselon berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN ESELON DELETE', [
                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'eselon' => 'Gagal menghapus eselon: ' . $e->getMessage(),
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
        return view('dashboard.master.eselon-import');
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
            | CARI INSERT
            |--------------------------------------------------------------------------
            */

            $pattern = '/insert\s+into\s+[`"]?[^`"\s(]+[`"]?\s*' . '\((.*?)\)\s*values\s*(.*?);/is';

            preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER);

            if (empty($matches)) {
                return back()->withErrors([
                    'import' => 'Data INSERT eselon tidak ditemukan di dalam file SQL.',
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
                | PASTIKAN DATA ESELON
                |--------------------------------------------------------------------------
                */

                if (!in_array('eselon_id', $columns, true)) {
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

                    if (!isset($data['eselon_id']) || !isset($data['eselon_nama'])) {
                        continue;
                    }

                    $eselonId = (int) $data['eselon_id'];

                    if ($eselonId <= 0) {
                        continue;
                    }

                    $nama = trim((string) $data['eselon_nama']);

                    if ($nama === '') {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CARI DATA BERDASARKAN ID
                    |--------------------------------------------------------------------------
                    */

                    $existing = SamperinEselon::where('eselon_id', $eselonId)->first();

                    /*
                    |--------------------------------------------------------------------------
                    | UID
                    |--------------------------------------------------------------------------
                    */

                    $uid = $existing?->eselon_uid ?? (!empty($data['eselon_uid']) ? trim($data['eselon_uid']) : (string) Str::uuid());

                    /*
                    |--------------------------------------------------------------------------
                    | KODE
                    |--------------------------------------------------------------------------
                    */

                    $kode = $existing?->eselon_kode;

                    if (!$kode) {
                        if (!empty($data['eselon_kode'])) {
                            $kode = trim($data['eselon_kode']);
                        } else {
                            $kode = 'ESELON-' . str_pad((string) $eselonId, 3, '0', STR_PAD_LEFT);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    $status = isset($data['eselon_status']) ? (int) $data['eselon_status'] : 1;

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */

                    if ($existing) {
                        $existing->update([
                            'eselon_nama' => $nama,

                            'eselon_status' => $status,
                        ]);

                        $updated++;
                    } else {
                        /*
                        |--------------------------------------------------------------------------
                        | INSERT DENGAN ID ASLI
                        |--------------------------------------------------------------------------
                        */

                        DB::table('samperin_eselon')->insert([
                            'eselon_id' => $eselonId,

                            'eselon_uid' => $uid,

                            'eselon_kode' => $kode,

                            'eselon_nama' => $nama,

                            'eselon_status' => $status,

                            'eselon_created_at' => now(),

                            'eselon_updated_at' => now(),
                        ]);

                        $inserted++;
                    }

                    $total++;
                }
            }

            if ($total === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data eselon yang berhasil dibaca dari file SQL.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | AUTO INCREMENT
            |--------------------------------------------------------------------------
            */

            $maxId = SamperinEselon::max('eselon_id');

            if ($maxId) {
                DB::statement('ALTER TABLE samperin_eselon AUTO_INCREMENT = ' . ((int) $maxId + 1));
            }

            return redirect()
                ->route('master.eselon.index')
                ->with('success', "Import SQL berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN ESELON IMPORT SQL', [
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

        $rowStarted = false;

        $length = strlen($values);

        for ($i = 0; $i < $length; $i++) {
            $char = $values[$i];

            /*
            |--------------------------------------------------------------------------
            | DALAM STRING
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
                    |--------------------------------------------------------------------------
                    | SQL ESCAPING ''
                    |--------------------------------------------------------------------------
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

                $rowStarted = true;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | KOMA
            |--------------------------------------------------------------------------
            */

            if ($char === ',' && $rowStarted) {
                $currentRow[] = $this->cleanSqlValue($currentValue);

                $currentValue = '';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | AKHIR ROW
            |--------------------------------------------------------------------------
            */

            if ($char === ')' && $rowStarted) {
                $currentRow[] = $this->cleanSqlValue($currentValue);

                $rows[] = $currentRow;

                $currentRow = [];

                $currentValue = '';

                $rowStarted = false;

                continue;
            }

            if ($rowStarted) {
                $currentValue .= $char;
            }
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
            | CEK PHPSPREADSHEET
            |--------------------------------------------------------------------------
            */

            if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                return back()->withErrors([
                    'import' => 'PhpSpreadsheet belum terpasang. Jalankan: composer require phpoffice/phpspreadsheet',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | LOAD FILE
            |--------------------------------------------------------------------------
            */

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

            if (!isset($columnMap['eselon_id']) || !isset($columnMap['eselon_nama'])) {
                return back()->withErrors([
                    'import' => 'Excel wajib memiliki kolom eselon_id dan eselon_nama.',
                ]);
            }

            $inserted = 0;

            $updated = 0;

            foreach ($rows as $row) {
                $eselonId = (int) ($row[$columnMap['eselon_id']] ?? 0);

                $nama = trim((string) ($row[$columnMap['eselon_nama']] ?? ''));

                if ($eselonId <= 0 || $nama === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                $status = 1;

                if (isset($columnMap['eselon_status'])) {
                    $status = (int) ($row[$columnMap['eselon_status']] ?? 1);
                }

                /*
                |--------------------------------------------------------------------------
                | KODE
                |--------------------------------------------------------------------------
                */

                $kode = 'ESELON-' . str_pad((string) $eselonId, 3, '0', STR_PAD_LEFT);

                if (isset($columnMap['eselon_kode'])) {
                    $excelKode = trim((string) ($row[$columnMap['eselon_kode']] ?? ''));

                    if ($excelKode !== '') {
                        $kode = $excelKode;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | UID
                |--------------------------------------------------------------------------
                */

                $uid = null;

                if (isset($columnMap['eselon_uid'])) {
                    $excelUid = trim((string) ($row[$columnMap['eselon_uid']] ?? ''));

                    if ($excelUid !== '') {
                        $uid = $excelUid;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | CARI DATA
                |--------------------------------------------------------------------------
                */

                $existing = SamperinEselon::where('eselon_id', $eselonId)->first();

                if ($existing) {
                    $existing->update([
                        'eselon_nama' => $nama,

                        'eselon_status' => $status,
                    ]);

                    $updated++;
                } else {
                    DB::table('samperin_eselon')->insert([
                        'eselon_id' => $eselonId,

                        'eselon_uid' => $uid ?: (string) Str::uuid(),

                        'eselon_kode' => $kode,

                        'eselon_nama' => $nama,

                        'eselon_status' => $status,

                        'eselon_created_at' => now(),

                        'eselon_updated_at' => now(),
                    ]);

                    $inserted++;
                }
            }

            if ($inserted === 0 && $updated === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data eselon yang berhasil diimport.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | AUTO INCREMENT
            |--------------------------------------------------------------------------
            */

            $maxId = SamperinEselon::max('eselon_id');

            if ($maxId) {
                DB::statement('ALTER TABLE samperin_eselon AUTO_INCREMENT = ' . ((int) $maxId + 1));
            }

            return redirect()
                ->route('master.eselon.index')
                ->with('success', "Import Excel berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN ESELON IMPORT EXCEL', [
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