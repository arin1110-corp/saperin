<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinPendidikan;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SamperinPendidikanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SamperinPendidikan::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('pendidikan_kode', 'like', '%' . $search . '%')
                    ->orWhere('pendidikan_jenjang', 'like', '%' . $search . '%')
                    ->orWhere('pendidikan_jurusan', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('pendidikan_status', (int) $request->status);
        }

        $pendidikans = $query->orderBy('pendidikan_id')->paginate(5)->withQueryString();

        $totalPendidikan = SamperinPendidikan::count();

        $pendidikanAktif = SamperinPendidikan::where('pendidikan_status', 1)->count();

        $pendidikanNonaktif = SamperinPendidikan::where('pendidikan_status', 0)->count();

        return view('dashboard.master.pendidikan', compact('pendidikans', 'totalPendidikan', 'pendidikanAktif', 'pendidikanNonaktif'));
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
                'pendidikan_jenjang' => ['required', 'string', 'max:100'],

                'pendidikan_jurusan' => ['required', 'string', 'max:100'],

                'pendidikan_status' => ['required', 'in:0,1'],
            ],
            [
                'pendidikan_jenjang.required' => 'Jenjang pendidikan wajib diisi.',

                'pendidikan_jurusan.required' => 'Jurusan pendidikan wajib diisi.',

                'pendidikan_status.required' => 'Status pendidikan wajib dipilih.',

                'pendidikan_status.in' => 'Status pendidikan tidak valid.',
            ],
        );

        $jenjang = trim($validated['pendidikan_jenjang']);
        $jurusan = trim($validated['pendidikan_jurusan']);

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        */

        if (SamperinPendidikan::where('pendidikan_jenjang', $jenjang)->where('pendidikan_jurusan', $jurusan)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'pendidikan' => 'Jenjang dan jurusan tersebut sudah terdaftar.',
                ]);
        }

        try {
            /*
            |--------------------------------------------------------------------------
            | GENERATE KODE OTOMATIS
            |--------------------------------------------------------------------------
            */

            $maxId = SamperinPendidikan::max('pendidikan_id');

            $nextId = ((int) $maxId) + 1;

            $kode = 'PEND-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);

            /*
            |--------------------------------------------------------------------------
            | PASTIKAN KODE TIDAK DUPLIKAT
            |--------------------------------------------------------------------------
            */

            while (SamperinPendidikan::where('pendidikan_kode', $kode)->exists()) {
                $nextId++;

                $kode = 'PEND-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);
            }

            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            SamperinPendidikan::create([
                'pendidikan_uid' => (string) Str::uuid(),

                'pendidikan_kode' => $kode,

                'pendidikan_jenjang' => $jenjang,

                'pendidikan_jurusan' => $jurusan,

                'pendidikan_status' => (int) $validated['pendidikan_status'],
            ]);

            return redirect()->route('master.pendidikan.index')->with('success', 'Pendidikan berhasil ditambahkan.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN PENDIDIKAN STORE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'pendidikan' => 'Gagal menambahkan pendidikan: ' . $e->getMessage(),
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
        $pendidikan = SamperinPendidikan::where('pendidikan_uid', $uid)->firstOrFail();

        $validated = $request->validate(
            [
                'pendidikan_jenjang' => ['required', 'string', 'max:100'],

                'pendidikan_jurusan' => ['required', 'string', 'max:100'],

                'pendidikan_status' => ['required', 'in:0,1'],
            ],
            [
                'pendidikan_jenjang.required' => 'Jenjang pendidikan wajib diisi.',

                'pendidikan_jurusan.required' => 'Jurusan pendidikan wajib diisi.',

                'pendidikan_status.required' => 'Status pendidikan wajib dipilih.',

                'pendidikan_status.in' => 'Status pendidikan tidak valid.',
            ],
        );

        $jenjang = trim($validated['pendidikan_jenjang']);
        $jurusan = trim($validated['pendidikan_jurusan']);

        /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $duplicate = SamperinPendidikan::where('pendidikan_jenjang', $jenjang)->where('pendidikan_jurusan', $jurusan)->where('pendidikan_id', '!=', $pendidikan->pendidikan_id)->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'pendidikan' => 'Jenjang dan jurusan tersebut sudah digunakan.',
                ]);
        }

        try {
            $pendidikan->update([
                'pendidikan_jenjang' => $jenjang,

                'pendidikan_jurusan' => $jurusan,

                'pendidikan_status' => (int) $validated['pendidikan_status'],
            ]);

            return redirect()->route('master.pendidikan.index')->with('success', 'Pendidikan berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN PENDIDIKAN UPDATE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'pendidikan' => 'Gagal memperbarui pendidikan: ' . $e->getMessage(),
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
        $pendidikan = SamperinPendidikan::where('pendidikan_uid', $uid)->firstOrFail();

        $pendidikan->pendidikan_status = (int) $pendidikan->pendidikan_status === 1 ? 0 : 1;

        $pendidikan->save();

        return back()->with('success', $pendidikan->pendidikan_status === 1 ? 'Pendidikan berhasil diaktifkan.' : 'Pendidikan berhasil dinonaktifkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $uid)
    {
        $pendidikan = SamperinPendidikan::where('pendidikan_uid', $uid)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CEK RELASI PEGAWAI
        |--------------------------------------------------------------------------
        */

        $jumlahPegawai = SamperinUser::where('user_pendidikan_id', $pendidikan->pendidikan_id)->count();

        if ($jumlahPegawai > 0) {
            return back()->withErrors([
                'pendidikan' => 'Pendidikan tidak dapat dihapus karena masih digunakan oleh ' . $jumlahPegawai . ' pegawai.',
            ]);
        }

        try {
            $pendidikan->delete();

            return back()->with('success', 'Pendidikan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN PENDIDIKAN DELETE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'pendidikan' => 'Gagal menghapus pendidikan: ' . $e->getMessage(),
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
        return view('dashboard.master.pendidikan-import');
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT PROCESS
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

        if ($extension === 'sql') {
            return $this->importSql($file);
        }

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

            $pattern = '/insert\s+into\s+[`"]?[^`"\s(]+[`"]?\s*' . '\((.*?)\)\s*values\s*(.*?);/is';

            preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER);

            if (empty($matches)) {
                return back()->withErrors([
                    'import' => 'Data INSERT pendidikan tidak ditemukan di dalam file SQL.',
                ]);
            }

            $total = 0;
            $inserted = 0;
            $updated = 0;

            foreach ($matches as $match) {
                $columnsString = trim($match[1]);

                $valuesString = trim($match[2]);

                $columns = array_map(function ($column) {
                    return trim($column, " \t\n\r\0\x0B`\"");
                }, explode(',', $columnsString));

                if (!in_array('pendidikan_id', $columns, true)) {
                    continue;
                }

                $rows = $this->parseSqlValues($valuesString);

                foreach ($rows as $row) {
                    if (count($row) !== count($columns)) {
                        continue;
                    }

                    $data = [];

                    foreach ($columns as $index => $column) {
                        $data[$column] = $row[$index];
                    }

                    if (!isset($data['pendidikan_id']) || !isset($data['pendidikan_jenjang'])) {
                        continue;
                    }

                    $pendidikanId = (int) $data['pendidikan_id'];

                    if ($pendidikanId <= 0) {
                        continue;
                    }

                    $jenjang = trim((string) $data['pendidikan_jenjang']);

                    if ($jenjang === '') {
                        continue;
                    }

                    $jurusan = isset($data['pendidikan_jurusan']) ? trim((string) $data['pendidikan_jurusan']) : '';

                    $existing = SamperinPendidikan::where('pendidikan_id', $pendidikanId)->first();

                    $payload = [
                        'pendidikan_uid' => $existing?->pendidikan_uid ?? (string) Str::uuid(),

                        'pendidikan_kode' => !empty($data['pendidikan_kode']) ? trim($data['pendidikan_kode']) : 'PEND-' . str_pad((string) $pendidikanId, 3, '0', STR_PAD_LEFT),

                        'pendidikan_jenjang' => $jenjang,

                        'pendidikan_jurusan' => $jurusan,

                        'pendidikan_status' => isset($data['pendidikan_status']) ? (int) $data['pendidikan_status'] : 1,
                    ];

                    if ($existing) {
                        $existing->update([
                            'pendidikan_kode' => $payload['pendidikan_kode'],

                            'pendidikan_jenjang' => $payload['pendidikan_jenjang'],

                            'pendidikan_jurusan' => $payload['pendidikan_jurusan'],

                            'pendidikan_status' => $payload['pendidikan_status'],
                        ]);

                        $updated++;
                    } else {
                        DB::table('samperin_pendidikan')->insert([
                            'pendidikan_id' => $pendidikanId,

                            'pendidikan_uid' => $payload['pendidikan_uid'],

                            'pendidikan_kode' => $payload['pendidikan_kode'],

                            'pendidikan_jenjang' => $payload['pendidikan_jenjang'],

                            'pendidikan_jurusan' => $payload['pendidikan_jurusan'],

                            'pendidikan_status' => $payload['pendidikan_status'],
                        ]);

                        $inserted++;
                    }

                    $total++;
                }
            }

            if ($total === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data pendidikan yang berhasil dibaca dari file SQL.',
                ]);
            }

            $maxId = SamperinPendidikan::max('pendidikan_id');

            if ($maxId) {
                DB::statement('ALTER TABLE samperin_pendidikan AUTO_INCREMENT = ' . ((int) $maxId + 1));
            }

            return redirect()
                ->route('master.pendidikan.index')
                ->with('success', "Import SQL berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN PENDIDIKAN IMPORT SQL', [
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

        $insideRow = false;

        $length = strlen($values);

        for ($i = 0; $i < $length; $i++) {
            $char = $values[$i];

            if ($insideString) {
                if ($char === '\\' && $i + 1 < $length) {
                    $currentValue .= $char;

                    $currentValue .= $values[++$i];

                    continue;
                }

                if ($char === $quote) {
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

            if ($char === "'" || $char === '"') {
                $insideString = true;

                $quote = $char;

                continue;
            }

            if ($char === '(') {
                $insideRow = true;

                $currentRow = [];

                $currentValue = '';

                continue;
            }

            if ($char === ',' && $insideRow) {
                $currentRow[] = $this->cleanSqlValue($currentValue);

                $currentValue = '';

                continue;
            }

            if ($char === ')' && $insideRow) {
                $currentRow[] = $this->cleanSqlValue($currentValue);

                $rows[] = $currentRow;

                $currentRow = [];

                $currentValue = '';

                $insideRow = false;

                continue;
            }

            if (!$insideRow) {
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

            $header = array_shift($rows);

            $header = array_map(function ($value) {
                return strtolower(trim((string) $value));
            }, $header);

            $columnMap = [];

            foreach ($header as $key => $name) {
                $columnMap[$name] = $key;
            }

            if (!isset($columnMap['pendidikan_id']) || !isset($columnMap['pendidikan_jenjang'])) {
                return back()->withErrors([
                    'import' => 'Excel wajib memiliki kolom pendidikan_id dan pendidikan_jenjang.',
                ]);
            }

            $inserted = 0;
            $updated = 0;

            foreach ($rows as $row) {
                $pendidikanId = (int) ($row[$columnMap['pendidikan_id']] ?? 0);

                $jenjang = trim((string) ($row[$columnMap['pendidikan_jenjang']] ?? ''));

                if ($pendidikanId <= 0 || $jenjang === '') {
                    continue;
                }

                $jurusan = '';

                if (isset($columnMap['pendidikan_jurusan'])) {
                    $jurusan = trim((string) ($row[$columnMap['pendidikan_jurusan']] ?? ''));
                }

                $status = 1;

                if (isset($columnMap['pendidikan_status'])) {
                    $status = (int) ($row[$columnMap['pendidikan_status']] ?? 1);
                }

                $kode = null;

                if (isset($columnMap['pendidikan_kode'])) {
                    $kode = trim((string) ($row[$columnMap['pendidikan_kode']] ?? ''));
                }

                if (!$kode) {
                    $kode = 'PEND-' . str_pad((string) $pendidikanId, 3, '0', STR_PAD_LEFT);
                }

                $existing = SamperinPendidikan::where('pendidikan_id', $pendidikanId)->first();

                if ($existing) {
                    $existing->update([
                        'pendidikan_kode' => $kode,

                        'pendidikan_jenjang' => $jenjang,

                        'pendidikan_jurusan' => $jurusan,

                        'pendidikan_status' => $status,
                    ]);

                    $updated++;
                } else {
                    DB::table('samperin_pendidikan')->insert([
                        'pendidikan_id' => $pendidikanId,

                        'pendidikan_uid' => (string) Str::uuid(),

                        'pendidikan_kode' => $kode,

                        'pendidikan_jenjang' => $jenjang,

                        'pendidikan_jurusan' => $jurusan,

                        'pendidikan_status' => $status,
                    ]);

                    $inserted++;
                }
            }

            if ($inserted === 0 && $updated === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data pendidikan yang berhasil diimport.',
                ]);
            }

            $maxId = SamperinPendidikan::max('pendidikan_id');

            if ($maxId) {
                DB::statement('ALTER TABLE samperin_pendidikan AUTO_INCREMENT = ' . ((int) $maxId + 1));
            }

            return redirect()
                ->route('master.pendidikan.index')
                ->with('success', "Import Excel berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN PENDIDIKAN IMPORT EXCEL', [
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
