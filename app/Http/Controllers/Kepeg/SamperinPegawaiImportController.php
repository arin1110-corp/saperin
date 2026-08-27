<?php

namespace App\Http\Controllers\Kepeg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class SamperinPegawaiImportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DAFTAR TABEL YANG BOLEH DIIMPORT
    |--------------------------------------------------------------------------
    */

    private array $tables = [
        'jabatan' => [
            'source' => 'sadarin_jabatan',
            'target' => 'samperin_jabatan',
            'id' => 'jabatan_id',
            'uid' => 'jabatan_uid',
        ],

        'bidang' => [
            'source' => 'sadarin_bidang',
            'target' => 'samperin_bidang',
            'id' => 'bidang_id',
            'uid' => 'bidang_uid',
        ],

        'golongan' => [
            'source' => 'sadarin_golongan',
            'target' => 'samperin_golongan',
            'id' => 'golongan_id',
            'uid' => 'golongan_uid',
        ],

        'eselon' => [
            'source' => 'sadarin_eselon',
            'target' => 'samperin_eselon',
            'id' => 'eselon_id',
            'uid' => 'eselon_uid',
        ],

        'pendidikan' => [
            'source' => 'sadarin_pendidikan',
            'target' => 'samperin_pendidikan',
            'id' => 'pendidikan_id',
            'uid' => 'pendidikan_uid',
        ],

        'jenis_kerja' => [
            'source' => 'sadarin_jenis_kerja',
            'target' => 'samperin_jenis_kerja',
            'id' => 'jenis_kerja_id',
            'uid' => 'jenis_kerja_uid',
        ],
    ];

    /*
    |--------------------------------------------------------------------------
    | HALAMAN IMPORT
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('dashboard-kepeg.pegawai.import', [
            'tables' => $this->tables,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT
    |--------------------------------------------------------------------------
    */

    public function import(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'file' => ['required', 'file', 'max:51200'],

                'tables' => ['required', 'array', 'min:1'],

                'tables.*' => ['required', 'string', 'in:jabatan,bidang,golongan,eselon,pendidikan,jenis_kerja'],
            ],
            [
                'file.required' => 'File SQL wajib dipilih.',

                'file.file' => 'File tidak valid.',

                'tables.required' => 'Pilih minimal satu data yang akan diimport.',

                'tables.min' => 'Pilih minimal satu data yang akan diimport.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */

        $file = $request->file('file');

        $sql = file_get_contents($file->getRealPath());

        if ($sql === false || trim($sql) === '') {
            return back()
                ->withErrors([
                    'file' => 'File SQL kosong atau tidak dapat dibaca.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | TABLE YANG DIPILIH
        |--------------------------------------------------------------------------
        */

        $selectedTables = $request->input('tables', []);

        /*
        |--------------------------------------------------------------------------
        | HASIL
        |--------------------------------------------------------------------------
        */

        $results = [];

        $totalSuccess = 0;

        $totalSkip = 0;

        $totalFail = 0;

        /*
        |--------------------------------------------------------------------------
        | PROSES
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {
            foreach ($selectedTables as $tableKey) {
                $config = $this->tables[$tableKey];

                $result = $this->importMaster($sql, $config);

                $results[$tableKey] = $result;

                $totalSuccess += $result['success'];

                $totalSkip += $result['skip'];

                $totalFail += $result['failed'];
            }

            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors([
                    'file' => 'Import gagal: ' . $e->getMessage(),
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN HASIL
        |--------------------------------------------------------------------------
        */

        return back()
            ->with('import_success', 'Import selesai. ' . 'Berhasil: ' . $totalSuccess . ', Dilewati: ' . $totalSkip . ', Gagal: ' . $totalFail)
            ->with('import_results', $results);
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT MASTER
    |--------------------------------------------------------------------------
    */

    private function importMaster(string $sql, array $config): array
    {
        $source = $config['source'];

        $target = $config['target'];

        $idColumn = $config['id'];

        $uidColumn = $config['uid'];

        /*
        |--------------------------------------------------------------------------
        | PARSE INSERT
        |--------------------------------------------------------------------------
        */

        $rows = $this->parseInsertSql($sql, $source);

        if (empty($rows)) {
            return [
                'success' => 0,
                'skip' => 0,
                'failed' => 0,
                'message' => 'Tidak ditemukan data ' . $source . ' pada file SQL.',
            ];
        }

        $success = 0;

        $skip = 0;

        $failed = 0;

        /*
        |--------------------------------------------------------------------------
        | KOLOM TARGET
        |--------------------------------------------------------------------------
        */

        $targetColumns = SchemaColumns::get($target);

        /*
        |--------------------------------------------------------------------------
        | LOOP DATA
        |--------------------------------------------------------------------------
        */

        foreach ($rows as $row) {
            try {
                /*
                |--------------------------------------------------------------------------
                | ID LAMA
                |--------------------------------------------------------------------------
                */

                if (!array_key_exists($idColumn, $row)) {
                    $failed++;

                    continue;
                }

                $oldId = $this->integerOrNull($row[$idColumn]);

                if ($oldId === null) {
                    $failed++;

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | CEK DATA SUDAH ADA
                |--------------------------------------------------------------------------
                */

                $existing = DB::table($target)->where($idColumn, $oldId)->first();

                /*
                |--------------------------------------------------------------------------
                | UID LAMA
                |--------------------------------------------------------------------------
                */

                if ($existing) {
                    $uid = $existing->{$uidColumn} ?? null;
                } else {
                    $uid = (string) Str::uuid();
                }

                /*
                |--------------------------------------------------------------------------
                | FILTER KOLOM
                |--------------------------------------------------------------------------
                */

                $data = [];

                foreach ($row as $column => $value) {
                    /*
                    |--------------------------------------------------------------------------
                    | NORMALISASI NAMA
                    |--------------------------------------------------------------------------
                    */

                    $column = strtolower(trim($column));

                    /*
                    |--------------------------------------------------------------------------
                    | HANYA KOLOM TARGET
                    |--------------------------------------------------------------------------
                    */

                    if (!in_array($column, $targetColumns, true)) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | UID
                    |--------------------------------------------------------------------------
                    */

                    if ($column === strtolower($uidColumn)) {
                        continue;
                    }

                    $data[$column] = $value;
                }

                /*
                |--------------------------------------------------------------------------
                | PERTAHANKAN ID
                |--------------------------------------------------------------------------
                */

                $data[$idColumn] = $oldId;

                /*
                |--------------------------------------------------------------------------
                | UID BARU
                |--------------------------------------------------------------------------
                */

                $data[$uidColumn] = $uid;

                /*
                |--------------------------------------------------------------------------
                | INSERT / UPDATE
                |--------------------------------------------------------------------------
                */

                if ($existing) {
                    DB::table($target)->where($idColumn, $oldId)->update($data);
                } else {
                    DB::table($target)->insert($data);
                }

                $success++;
            } catch (Throwable $e) {
                $failed++;
            }
        }

        return [
            'success' => $success,

            'skip' => $skip,

            'failed' => $failed,

            'message' => 'Import ' . $source . ' selesai.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE INSERT SQL
    |--------------------------------------------------------------------------
    */

    private function parseInsertSql(string $sql, string $table): array
    {
        $pattern = '/insert\s+into\s+[`"]?' . preg_quote($table, '/') . '[`"]?\s*' . '\((.*?)\)\s*values\s*(.*?);/is';

        preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER);

        if (empty($matches)) {
            return [];
        }

        $rows = [];

        foreach ($matches as $match) {
            $columns = $this->parseColumns($match[1]);

            $values = $this->parseValueRows($match[2]);

            foreach ($values as $valueRow) {
                if (count($columns) !== count($valueRow)) {
                    continue;
                }

                $row = [];

                foreach ($columns as $index => $column) {
                    $column = strtolower(trim($column));

                    $row[$column] = $valueRow[$index] ?? null;
                }

                $rows[] = $row;
            }
        }

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE COLUMNS
    |--------------------------------------------------------------------------
    */

    private function parseColumns(string $columns): array
    {
        $columns = str_replace(['`', '"', "\r", "\n"], '', $columns);

        return array_map('trim', explode(',', $columns));
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE VALUES
    |--------------------------------------------------------------------------
    */

    private function parseValueRows(string $values): array
    {
        $rows = [];

        $length = strlen($values);

        $inString = false;

        $quote = '';

        $depth = 0;

        $current = '';

        $currentRow = [];

        for ($i = 0; $i < $length; $i++) {
            $char = $values[$i];

            /*
            |--------------------------------------------------------------------------
            | STRING
            |--------------------------------------------------------------------------
            */

            if (($char === "'" || $char === '"') && ($i === 0 || $values[$i - 1] !== '\\')) {
                if (!$inString) {
                    $inString = true;

                    $quote = $char;
                } elseif ($quote === $char) {
                    if (isset($values[$i + 1]) && $values[$i + 1] === $char) {
                        $current .= $char;

                        $i++;
                    } else {
                        $inString = false;

                        $quote = '';
                    }
                }

                $current .= $char;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | DALAM STRING
            |--------------------------------------------------------------------------
            */

            if ($inString) {
                $current .= $char;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | OPEN ROW
            |--------------------------------------------------------------------------
            */

            if ($char === '(') {
                if ($depth === 0) {
                    $current = '';

                    $currentRow = [];
                }

                $depth++;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CLOSE ROW
            |--------------------------------------------------------------------------
            */

            if ($char === ')') {
                $depth--;

                if ($depth === 0) {
                    $currentRow[] = $this->cleanSqlValue($current);

                    $rows[] = $currentRow;

                    $currentRow = [];

                    $current = '';
                } else {
                    $current .= $char;
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | COMMA
            |--------------------------------------------------------------------------
            */

            if ($char === ',' && $depth === 1) {
                $currentRow[] = $this->cleanSqlValue($current);

                $current = '';

                continue;
            }

            if ($depth > 0) {
                $current .= $char;
            }
        }

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN SQL VALUE
    |--------------------------------------------------------------------------
    */

    private function cleanSqlValue(string $value): ?string
    {
        $value = trim($value);

        if (strtoupper($value) === 'NULL') {
            return null;
        }

        if (strlen($value) >= 2 && (($value[0] === "'" && $value[strlen($value) - 1] === "'") || ($value[0] === '"' && $value[strlen($value) - 1] === '"'))) {
            $value = substr($value, 1, -1);

            $value = str_replace(["\\'", '\\"', '\\\\', "\\0"], ["'", '"', '\\', "\0"], $value);

            $value = str_replace("''", "'", $value);
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | INTEGER
    |--------------------------------------------------------------------------
    */

    private function integerOrNull($value): ?int
    {
        if ($value === null || trim((string) $value) === '' || trim((string) $value) === '-') {
            return null;
        }

        return (int) $value;
    }
}

/*
|--------------------------------------------------------------------------
| HELPER SCHEMA
|--------------------------------------------------------------------------
|
| Dibuat di luar controller agar controller tetap bersih.
|
*/

class SchemaColumns
{
    public static function get(string $table): array
    {
        return DB::getSchemaBuilder()->getColumnListing($table);
    }
}