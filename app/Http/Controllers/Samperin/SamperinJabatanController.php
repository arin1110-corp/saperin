<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinJabatan;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SamperinJabatanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SamperinJabatan::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('jabatan_kode', 'like', '%' . $search . '%')
                    ->orWhere('jabatan_nama', 'like', '%' . $search . '%')
                    ->orWhere('jabatan_kategori', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('jabatan_status', (int) $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $jabatans = $query->orderBy('jabatan_id')->paginate(5)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalJabatan = SamperinJabatan::count();

        $jabatanAktif = SamperinJabatan::where('jabatan_status', 1)->count();

        $jabatanNonaktif = SamperinJabatan::where('jabatan_status', 0)->count();

        return view('dashboard.master.jabatan', compact('jabatans', 'totalJabatan', 'jabatanAktif', 'jabatanNonaktif'));
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
                'jabatan_nama' => ['required', 'string', 'max:255'],
                'jabatan_kategori' => ['required', 'string', 'max:100'],
                'jabatan_status' => ['required', 'in:0,1'],
            ],
            [
                'jabatan_nama.required' => 'Nama jabatan wajib diisi.',
                'jabatan_kategori.required' => 'Kategori jabatan wajib dipilih.',
                'jabatan_status.required' => 'Status jabatan wajib dipilih.',
                'jabatan_status.in' => 'Status jabatan tidak valid.',
            ],
        );

        $nama = trim($validated['jabatan_nama']);
        $kategori = trim($validated['jabatan_kategori']);

        /*
    |--------------------------------------------------------------------------
    | CEK DUPLIKAT NAMA
    |--------------------------------------------------------------------------
    */

        $exists = SamperinJabatan::query()
            ->whereRaw('LOWER(jabatan_nama) = ?', [strtolower($nama)])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'jabatan_nama' => 'Nama jabatan tersebut sudah digunakan.',
                ]);
        }

        DB::beginTransaction();

        try {
            /*
        |--------------------------------------------------------------------------
        | AMBIL ID BERIKUTNYA
        |--------------------------------------------------------------------------
        */

            $lastId = SamperinJabatan::max('jabatan_id');

            $nextId = ((int) $lastId) + 1;

            /*
        |--------------------------------------------------------------------------
        | GENERATE KODE JABATAN OTOMATIS
        |--------------------------------------------------------------------------
        |
        | Contoh:
        | JAB-001
        | JAB-002
        | JAB-003
        |
        */

            $jabatanKode = 'JAB-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);

            /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA
        |--------------------------------------------------------------------------
        */

            SamperinJabatan::create([
                'jabatan_uid' => (string) \Illuminate\Support\Str::uuid(),

                'jabatan_kode' => $jabatanKode,

                'jabatan_nama' => $nama,

                'jabatan_kategori' => $kategori,

                'jabatan_status' => (int) $validated['jabatan_status'],
            ]);

            DB::commit();

            return redirect()
                ->route('master.jabatan.index')
                ->with('success', "Jabatan {$nama} berhasil ditambahkan dengan kode {$jabatanKode}.");
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('SAMPERIN JABATAN STORE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'jabatan' => 'Gagal menambahkan jabatan: ' . $e->getMessage(),
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
        $jabatan = SamperinJabatan::where('jabatan_id', $id)->firstOrFail();

        $validated = $request->validate(
            [
                'jabatan_nama' => ['required', 'string', 'max:255'],
                'jabatan_kategori' => ['required', 'string', 'max:100'],
                'jabatan_status' => ['required', 'in:0,1'],
            ],
            [
                'jabatan_nama.required' => 'Nama jabatan wajib diisi.',
                'jabatan_kategori.required' => 'Kategori jabatan wajib diisi.',
                'jabatan_status.required' => 'Status jabatan wajib dipilih.',
                'jabatan_status.in' => 'Status jabatan tidak valid.',
            ],
        );

        try {
            $jabatan->update([
                'jabatan_nama' => trim($validated['jabatan_nama']),
                'jabatan_kategori' => trim($validated['jabatan_kategori']),
                'jabatan_status' => (int) $validated['jabatan_status'],
            ]);

            return redirect()->route('master.jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN JABATAN UPDATE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'jabatan' => 'Gagal memperbarui jabatan: ' . $e->getMessage(),
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
        $jabatan = SamperinJabatan::where('jabatan_id', $id)->firstOrFail();

        $jabatan->jabatan_status = (int) $jabatan->jabatan_status === 1 ? 0 : 1;

        $jabatan->save();

        return back()->with('success', $jabatan->jabatan_status === 1 ? 'Jabatan berhasil diaktifkan.' : 'Jabatan berhasil dinonaktifkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(int $id)
    {
        $jabatan = SamperinJabatan::where('jabatan_id', $id)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CEK RELASI PEGAWAI
        |--------------------------------------------------------------------------
        */

        $jumlahPegawai = SamperinUser::where(
            'user_jabatan_id',
            $jabatan->jabatan_id
        )->count();

        if ($jumlahPegawai > 0) {
            return back()->withErrors([
                'jabatan' => 'Jabatan tidak dapat dihapus karena masih digunakan oleh ' . $jumlahPegawai . ' pegawai.',
            ]);
        }

        try {
            $jabatan->delete();

            return back()->with('success', 'Jabatan berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN JABATAN DELETE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'jabatan' => 'Gagal menghapus jabatan: ' . $e->getMessage(),
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
        return view('dashboard.master.jabatan-import');
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

        /*
        |--------------------------------------------------------------------------
        | JANGAN MENGANDALKAN VALIDASI MIMES
        |--------------------------------------------------------------------------
        |
        | Sama seperti controller Bidang:
        | kita membaca extension asli file.
        |
        */

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
                    'import' => 'Data INSERT jabatan tidak ditemukan di dalam file SQL.',
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
                | KOLOM
                |--------------------------------------------------------------------------
                */

                $columns = array_map(function ($column) {
                    return trim($column, " \t\n\r\0\x0B`\"");
                }, explode(',', $columnsString));

                /*
                |--------------------------------------------------------------------------
                | HANYA JABATAN
                |--------------------------------------------------------------------------
                */

                if (!in_array('jabatan_id', $columns, true)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | PARSE VALUES
                |--------------------------------------------------------------------------
                */

                $rows = $this->parseSqlValues($valuesString);

                foreach ($rows as $row) {
                    if (count($row) !== count($columns)) {
                        continue;
                    }

                    $data = [];

                    foreach ($columns as $index => $column) {
                        $data[$column] = $row[$index] ?? null;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | VALIDASI DASAR
                    |--------------------------------------------------------------------------
                    */

                    if (!isset($data['jabatan_id']) || !isset($data['jabatan_nama'])) {
                        continue;
                    }

                    $jabatanId = (int) $data['jabatan_id'];

                    if ($jabatanId <= 0) {
                        continue;
                    }

                    $nama = trim((string) $data['jabatan_nama']);

                    if ($nama === '') {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | DATA TAMBAHAN
                    |--------------------------------------------------------------------------
                    */

                    $kode = isset($data['jabatan_kode']) ? trim((string) $data['jabatan_kode']) : '';

                    $kategori = isset($data['jabatan_kategori']) ? trim((string) $data['jabatan_kategori']) : '';

                    $status = isset($data['jabatan_status']) && $data['jabatan_status'] !== '' ? (int) $data['jabatan_status'] : 1;

                    /*
                    |--------------------------------------------------------------------------
                    | ID WAJIB DIPERTAHANKAN
                    |--------------------------------------------------------------------------
                    */

                    $existing = SamperinJabatan::where('jabatan_id', $jabatanId)->first();

                    /*
                    |--------------------------------------------------------------------------
                    | UID
                    |--------------------------------------------------------------------------
                    */

                    $uid = $existing?->jabatan_uid ?? (string) Str::uuid();

                    /*
                    |--------------------------------------------------------------------------
                    | KODE
                    |--------------------------------------------------------------------------
                    |
                    | Kalau SQL memiliki jabatan_kode,
                    | gunakan dari file.
                    |
                    | Kalau tidak ada,
                    | gunakan fallback.
                    |
                    */

                    if ($kode === '') {
                        $kode = $existing?->jabatan_kode ?? 'JAB-' . str_pad((string) $jabatanId, 3, '0', STR_PAD_LEFT);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */

                    if ($existing) {
                        $existing->update([
                            'jabatan_nama' => $nama,

                            'jabatan_kode' => $kode,

                            'jabatan_kategori' => $kategori !== '' ? $kategori : null,

                            'jabatan_status' => $status,
                        ]);

                        $updated++;
                    } else {
                        /*
                        |--------------------------------------------------------------------------
                        | INSERT DENGAN ID FILE
                        |--------------------------------------------------------------------------
                        */

                        DB::table('samperin_jabatan')->insert([
                            'jabatan_id' => $jabatanId,

                            'jabatan_uid' => $uid,

                            'jabatan_kode' => $kode,

                            'jabatan_nama' => $nama,

                            'jabatan_kategori' => $kategori !== '' ? $kategori : null,

                            'jabatan_status' => $status,
                        ]);

                        $inserted++;
                    }

                    $total++;
                }
            }

            if ($total === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data jabatan yang berhasil dibaca dari file SQL.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | AUTO INCREMENT
            |--------------------------------------------------------------------------
            |
            | TIDAK ADA TRANSACTION.
            |
            */

            $this->resetAutoIncrement();

            return redirect()
                ->route('master.jabatan.index')
                ->with('success', "Import SQL berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN JABATAN IMPORT SQL', [
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

        $insideParenthesis = false;

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
                    | SQL escape ''
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
                $insideParenthesis = true;

                $currentRow = [];

                $currentValue = '';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | AKHIR ROW
            |--------------------------------------------------------------------------
            */

            if ($char === ')' && $insideParenthesis) {
                $currentRow[] = $this->cleanSqlValue($currentValue);

                $rows[] = $currentRow;

                $currentRow = [];

                $currentValue = '';

                $insideParenthesis = false;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | PEMISAH KOLOM
            |--------------------------------------------------------------------------
            */

            if ($char === ',' && $insideParenthesis) {
                $currentRow[] = $this->cleanSqlValue($currentValue);

                $currentValue = '';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | VALUE
            |--------------------------------------------------------------------------
            */

            if ($insideParenthesis) {
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

            if (!isset($columnMap['jabatan_id']) || !isset($columnMap['jabatan_nama'])) {
                return back()->withErrors([
                    'import' => 'Excel wajib memiliki kolom jabatan_id dan jabatan_nama.',
                ]);
            }

            $inserted = 0;

            $updated = 0;

            foreach ($rows as $row) {
                /*
                |--------------------------------------------------------------------------
                | ID
                |--------------------------------------------------------------------------
                */

                $jabatanId = (int) ($row[$columnMap['jabatan_id']] ?? 0);

                if ($jabatanId <= 0) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | NAMA
                |--------------------------------------------------------------------------
                */

                $nama = trim((string) ($row[$columnMap['jabatan_nama']] ?? ''));

                if ($nama === '') {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | KODE
                |--------------------------------------------------------------------------
                */

                $kode = '';

                if (isset($columnMap['jabatan_kode'])) {
                    $kode = trim((string) ($row[$columnMap['jabatan_kode']] ?? ''));
                }

                /*
                |--------------------------------------------------------------------------
                | KATEGORI
                |--------------------------------------------------------------------------
                */

                $kategori = '';

                if (isset($columnMap['jabatan_kategori'])) {
                    $kategori = trim((string) ($row[$columnMap['jabatan_kategori']] ?? ''));
                }

                /*
                |--------------------------------------------------------------------------

                | STATUS
                |--------------------------------------------------------------------------
                */

                $status = 1;

                if (isset($columnMap['jabatan_status'])) {
                    $status = (int) ($row[$columnMap['jabatan_status']] ?? 1);
                }

                /*
                |--------------------------------------------------------------------------
                | EXISTING
                |--------------------------------------------------------------------------
                */

                $existing = SamperinJabatan::where('jabatan_id', $jabatanId)->first();

                /*
                |--------------------------------------------------------------------------
                | KODE FALLBACK
                |--------------------------------------------------------------------------
                */

                if ($kode === '') {
                    $kode = $existing?->jabatan_kode ?? 'JAB-' . str_pad((string) $jabatanId, 3, '0', STR_PAD_LEFT);
                }

                /*
                |--------------------------------------------------------------------------
                | UPDATE
                |--------------------------------------------------------------------------
                */

                if ($existing) {
                    $existing->update([
                        'jabatan_kode' => $kode,

                        'jabatan_nama' => $nama,

                        'jabatan_kategori' => $kategori !== '' ? $kategori : null,

                        'jabatan_status' => $status,
                    ]);

                    $updated++;
                } else {
                    /*
                    |--------------------------------------------------------------------------
                    | INSERT
                    |--------------------------------------------------------------------------
                    */

                    DB::table('samperin_jabatan')->insert([
                        'jabatan_id' => $jabatanId,

                        'jabatan_uid' => (string) Str::uuid(),

                        'jabatan_kode' => $kode,

                        'jabatan_nama' => $nama,

                        'jabatan_kategori' => $kategori !== '' ? $kategori : null,

                        'jabatan_status' => $status,
                    ]);

                    $inserted++;
                }
            }

            if ($inserted === 0 && $updated === 0) {
                return back()->withErrors([
                    'import' => 'Tidak ada data jabatan yang berhasil diimport.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | AUTO INCREMENT
            |--------------------------------------------------------------------------
            */

            $this->resetAutoIncrement();

            return redirect()
                ->route('master.jabatan.index')
                ->with('success', "Import Excel berhasil. {$inserted} data ditambahkan dan {$updated} data diperbarui.");
        } catch (Throwable $e) {
            Log::error('SAMPERIN JABATAN IMPORT EXCEL', [
                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'import' => 'Import Excel gagal: ' . $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESET AUTO INCREMENT
    |--------------------------------------------------------------------------
    */

    private function resetAutoIncrement(): void
    {
        $maxId = SamperinJabatan::max('jabatan_id');

        if ($maxId !== null) {
            DB::statement('ALTER TABLE samperin_jabatan AUTO_INCREMENT = ' . ((int) $maxId + 1));
        }
    }
}