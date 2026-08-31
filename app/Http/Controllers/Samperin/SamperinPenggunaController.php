<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinRole;
use App\Models\SamperinUser;
use App\Models\SamperinUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class SamperinPenggunaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY PENGGUNA
        |--------------------------------------------------------------------------
        |
        | Pengguna = pegawai yang sudah memiliki minimal 1 role.
        |
        */

        $query = SamperinUser::query()
            ->whereHas('roles')
            ->with([
                'roles' => function ($query) {
                    $query->where('role_status', 1);
                },
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('user_nip', 'like', '%' . $search . '%')
                    ->orWhere('user_nik', 'like', '%' . $search . '%')
                    ->orWhere('user_nama', 'like', '%' . $search . '%')
                    ->orWhere('user_email', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('user_status', (int) $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        if ($request->filled('role')) {
            $roleUid = trim($request->role);

            $query->whereHas('roles', function ($q) use ($roleUid) {
                $q->where('role_uid', $roleUid)->where('role_status', 1);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $users = $query->orderBy('user_nama')->paginate(20)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | SEMUA ROLE AKTIF
        |--------------------------------------------------------------------------
        */

        $roles = SamperinRole::query()->where('role_status', 1)->orderBy('role_nama')->get();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalPegawai = SamperinUser::count();

        $totalPengguna = SamperinUser::query()->whereHas('roles')->count();

        $belumPengguna = max(0, $totalPegawai - $totalPengguna);

        $penggunaAktif = SamperinUser::query()->where('user_status', 1)->whereHas('roles')->count();

        $penggunaNonaktif = SamperinUser::query()->where('user_status', 0)->whereHas('roles')->count();

        /*
        |--------------------------------------------------------------------------
        | PEGAWAI UNTUK MODAL TAMBAH
        |--------------------------------------------------------------------------
        |
        | Tidak mengambil pegawai yang sudah menjadi pengguna.
        |
        */

        $pegawai = SamperinUser::query()
            ->where('user_status', 1)
            ->whereDoesntHave('roles')
            ->orderBy('user_nama')
            ->get(['user_id', 'user_uid', 'user_nip', 'user_nik', 'user_nama', 'user_email']);

        /*
        |--------------------------------------------------------------------------
        | ROLE DEFAULT
        |--------------------------------------------------------------------------
        */

        $rolePegawai = SamperinRole::query()->where('role_slug', 'pegawai')->where('role_status', 1)->first();

        return view('dashboard.pengguna.index', compact('users', 'roles', 'pegawai', 'rolePegawai', 'totalPegawai', 'totalPengguna', 'belumPengguna', 'penggunaAktif', 'penggunaNonaktif'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Menambahkan role kepada pegawai yang sudah ada.
    |
    */

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'user_uid' => ['required', 'string'],

                'roles' => ['required', 'array', 'min:1'],

                'roles.*' => ['required', 'string', 'exists:samperin_role,role_uid'],
            ],
            [
                'user_uid.required' => 'Pegawai wajib dipilih.',

                'roles.required' => 'Minimal satu role harus dipilih.',

                'roles.min' => 'Minimal satu role harus dipilih.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | CARI PEGAWAI
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::query()->where('user_uid', $validated['user_uid'])->first();

        if (!$user) {
            return back()
                ->withInput()
                ->withErrors([
                    'user' => 'Data pegawai tidak ditemukan.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI STATUS PEGAWAI
        |--------------------------------------------------------------------------
        */

        if ((int) $user->user_status !== 1) {
            return back()
                ->withInput()
                ->withErrors([
                    'user' => 'Pegawai tersebut sedang tidak aktif.',
                ]);
        }

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | TAMBAHKAN ROLE
            |--------------------------------------------------------------------------
            */

            $jumlahRole = $this->addRoles($user, $validated['roles']);

            DB::commit();

            return redirect()
                ->route('samperin.admin.users.index')
                ->with('success', 'Pengguna berhasil ditambahkan. ' . $jumlahRole . ' role ditambahkan.');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('SAMPERIN USER ROLE STORE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'user' => 'Gagal menambahkan pengguna: ' . $e->getMessage(),
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE ROLE
    |--------------------------------------------------------------------------
    |
    | Tidak mengubah data pegawai.
    | Hanya mengganti daftar role.
    |
    */

    public function update(Request $request, string $uid)
    {
        $user = SamperinUser::query()->where('user_uid', $uid)->firstOrFail();

        $validated = $request->validate(
            [
                'roles' => ['required', 'array', 'min:1'],

                'roles.*' => ['required', 'string', 'exists:samperin_role,role_uid'],
            ],
            [
                'roles.required' => 'Minimal satu role harus dipilih.',

                'roles.min' => 'Minimal satu role harus dipilih.',
            ],
        );

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | HAPUS ROLE LAMA
            |--------------------------------------------------------------------------
            */

            SamperinUserRole::query()->where('user_role_user_uid', $user->user_uid)->delete();

            /*
            |--------------------------------------------------------------------------
            | TAMBAHKAN ROLE BARU
            |--------------------------------------------------------------------------
            */

            $jumlahRole = $this->addRoles($user, $validated['roles']);

            DB::commit();

            return redirect()->route('samperin.admin.users.index')->with('success', 'Role pengguna berhasil diperbarui.');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('SAMPERIN USER ROLE UPDATE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'user' => 'Gagal memperbarui role: ' . $e->getMessage(),
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    |
    | Status akun sebenarnya adalah status pegawai.
    |
    */

    public function status(string $uid)
    {
        $user = SamperinUser::query()->where('user_uid', $uid)->firstOrFail();

        $currentUserId = session('samperin_user_id');

        if ((int) $user->user_id === (int) $currentUserId) {
            return back()->withErrors([
                'user' => 'Anda tidak dapat menonaktifkan akun sendiri.',
            ]);
        }

        $user->user_status = (int) $user->user_status === 1 ? 0 : 1;

        $user->save();

        return back()->with('success', $user->user_status === 1 ? 'Pengguna berhasil diaktifkan.' : 'Pengguna berhasil dinonaktifkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    |
    | PENTING:
    |
    | Karena pegawai adalah master,
    | tombol hapus pengguna TIDAK menghapus pegawai.
    |
    | Yang dihapus hanya seluruh role pengguna.
    |
    */

    public function destroy(string $uid)
    {
        $user = SamperinUser::query()->where('user_uid', $uid)->firstOrFail();

        $currentUserId = session('samperin_user_id');

        if ((int) $user->user_id === (int) $currentUserId) {
            return back()->withErrors([
                'user' => 'Anda tidak dapat menghapus role akun sendiri.',
            ]);
        }

        DB::beginTransaction();

        try {
            SamperinUserRole::query()->where('user_role_user_uid', $user->user_uid)->delete();

            DB::commit();

            return back()->with('success', 'Pengguna berhasil dihapus dari manajemen pengguna. Data pegawai tetap tersimpan.');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('SAMPERIN USER ROLE DELETE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'user' => 'Gagal menghapus pengguna: ' . $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAHKAN SEMUA PEGAWAI SEBAGAI ROLE PEGAWAI
    |--------------------------------------------------------------------------
    |
    | Aman dijalankan berulang kali.
    |
    | Kalau pegawai sudah mempunyai role Pegawai,
    | tidak akan dibuat duplikat.
    |
    */

    public function assignDefaultPegawai()
    {
        /*
        |--------------------------------------------------------------------------
        | ROLE PEGAWAI
        |--------------------------------------------------------------------------
        */

        $rolePegawai = SamperinRole::query()->where('role_slug', 'pegawai')->where('role_status', 1)->first();

        if (!$rolePegawai) {
            return back()->withErrors([
                'role' => 'Role "Pegawai" belum tersedia atau tidak aktif.',
            ]);
        }

        $totalPegawai = 0;
        $baru = 0;
        $sudahAda = 0;

        DB::beginTransaction();

        try {
            /*
            |--------------------------------------------------------------------------
            | AMBIL PEGAWAI AKTIF
            |--------------------------------------------------------------------------
            */

            SamperinUser::query()
                ->where('user_status', 1)
                ->orderBy('user_id')
                ->chunkById(
                    200,
                    function ($pegawai) use ($rolePegawai, &$totalPegawai, &$baru, &$sudahAda) {
                        foreach ($pegawai as $user) {
                            $totalPegawai++;

                            /*
                            |--------------------------------------------------------------------------
                            | CEK ROLE SUDAH ADA
                            |--------------------------------------------------------------------------
                            */

                            $exists = SamperinUserRole::query()->where('user_role_user_uid', $user->user_uid)->where('user_role_role_uid', $rolePegawai->role_uid)->exists();

                            if ($exists) {
                                $sudahAda++;
                                continue;
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | INSERT ROLE
                            |--------------------------------------------------------------------------
                            */

                            SamperinUserRole::create([
                                'user_role_uid' => (string) Str::uuid(),

                                'user_role_user_id' => $user->user_id,

                                'user_role_user_uid' => $user->user_uid,

                                'user_role_role_id' => $rolePegawai->role_id,

                                'user_role_role_uid' => $rolePegawai->role_uid,
                            ]);

                            $baru++;
                        }
                    },
                    'user_id',
                );

            DB::commit();

            return back()->with('success', 'Sinkronisasi role Pegawai selesai. ' . 'Total pegawai aktif: ' . $totalPegawai . ' | Ditambahkan: ' . $baru . ' | Sudah memiliki role: ' . $sudahAda);
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('SAMPERIN ASSIGN DEFAULT PEGAWAI', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'user' => 'Gagal menambahkan role Pegawai: ' . $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER TAMBAH ROLE
    |--------------------------------------------------------------------------
    */

    private function addRoles(SamperinUser $user, array $roleUids): int
    {
        $roleUids = array_values(array_unique(array_filter($roleUids)));

        if (empty($roleUids)) {
            return 0;
        }

        $roles = SamperinRole::query()->whereIn('role_uid', $roleUids)->where('role_status', 1)->get();

        $jumlah = 0;

        foreach ($roles as $role) {
            $exists = SamperinUserRole::query()->where('user_role_user_uid', $user->user_uid)->where('user_role_role_uid', $role->role_uid)->exists();

            if ($exists) {
                continue;
            }

            SamperinUserRole::create([
                'user_role_uid' => (string) Str::uuid(),

                'user_role_user_id' => $user->user_id,

                'user_role_user_uid' => $user->user_uid,

                'user_role_role_id' => $role->role_id,

                'user_role_role_uid' => $role->role_uid,
            ]);

            $jumlah++;
        }

        return $jumlah;
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT PAGE
    |--------------------------------------------------------------------------
    */

    public function import()
    {
        $roles = SamperinRole::query()->where('role_status', 1)->orderBy('role_nama')->get();

        return view('dashboard.pengguna.import', compact('roles'));
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT SQL
    |--------------------------------------------------------------------------
    */

    public function importSql(Request $request)
    {
        $request->validate(
            [
                'file' => ['required', 'file', 'mimes:sql', 'max:51200'],
            ],
            [
                'file.required' => 'File SQL wajib dipilih.',

                'file.mimes' => 'File harus berformat SQL.',

                'file.max' => 'Ukuran file maksimal 50 MB.',
            ],
        );

        try {
            $file = $request->file('file');

            $sql = file_get_contents($file->getRealPath());

            if ($sql === false || trim($sql) === '') {
                return back()->withErrors([
                    'import' => 'File SQL kosong atau tidak dapat dibaca.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | IMPORT DATA PEGAWAI
            |--------------------------------------------------------------------------
            |
            | Import SQL tetap diarahkan ke
            | samperin_user.
            |
            */

            $rows = $this->parseInsert($sql, 'samperin_user');

            if (empty($rows)) {
                return back()->withErrors([
                    'import' => 'Tidak ditemukan INSERT INTO samperin_user pada file SQL.',
                ]);
            }

            $result = $this->processImportedRows($rows);

            return back()->with('success', 'Import SQL berhasil. ' . 'Baru: ' . $result['baru'] . ' | Update: ' . $result['update'] . ' | Skip: ' . $result['skip']);
        } catch (Throwable $e) {
            Log::error('SAMPERIN USER SQL IMPORT', [
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
    | IMPORT EXCEL
    |--------------------------------------------------------------------------
    */

    public function importExcel(Request $request)
    {
        $request->validate(
            [
                'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:51200'],
            ],
            [
                'file.required' => 'File Excel wajib dipilih.',

                'file.mimes' => 'File harus berformat XLSX, XLS atau CSV.',

                'file.max' => 'Ukuran file maksimal 50 MB.',
            ],
        );

        try {
            $sheets = Excel::toArray([], $request->file('file'));

            if (empty($sheets) || empty($sheets[0])) {
                return back()->withErrors([
                    'import' => 'File Excel kosong.',
                ]);
            }

            $rows = [];

            $headers = array_map(function ($header) {
                return strtolower(trim((string) $header));
            }, $sheets[0][0]);

            foreach (array_slice($sheets[0], 1) as $row) {
                $data = [];

                foreach ($headers as $index => $header) {
                    if ($header === '') {
                        continue;
                    }

                    $data[$header] = $row[$index] ?? null;
                }

                if (!empty(array_filter($data, fn($value) => $value !== null && $value !== ''))) {
                    $rows[] = $data;
                }
            }

            if (empty($rows)) {
                return back()->withErrors([
                    'import' => 'Tidak ditemukan data pada Excel.',
                ]);
            }

            $result = $this->processImportedRows($rows);

            return back()->with('success', 'Import Excel berhasil. ' . 'Baru: ' . $result['baru'] . ' | Update: ' . $result['update'] . ' | Skip: ' . $result['skip']);
        } catch (Throwable $e) {
            Log::error('SAMPERIN USER EXCEL IMPORT', [
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
    | PROCESS IMPORT
    |--------------------------------------------------------------------------
    */

    private function processImportedRows(array $rows): array
    {
        $baru = 0;
        $update = 0;
        $skip = 0;

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $userId = $this->toInteger($row['user_id'] ?? null);

                $uid = $this->clean($row['user_uid'] ?? null);

                $nip = $this->clean($row['user_nip'] ?? null);

                $nik = $this->clean($row['user_nik'] ?? null);

                $nama = $this->clean($row['user_nama'] ?? null);

                if (!$nama) {
                    $skip++;
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | CARI USER
                |--------------------------------------------------------------------------
                */

                $user = null;

                if ($uid) {
                    $user = SamperinUser::query()->where('user_uid', $uid)->first();
                }

                if (!$user && $userId) {
                    $user = SamperinUser::query()->where('user_id', $userId)->first();
                }

                if (!$user && $nip) {
                    $user = SamperinUser::query()->where('user_nip', $nip)->first();
                }

                /*
                |--------------------------------------------------------------------------
                | UID BARU
                |--------------------------------------------------------------------------
                */

                $uid = $user?->user_uid ?? ($uid ?? (string) Str::uuid());

                /*
                |--------------------------------------------------------------------------
                | USER ID
                |--------------------------------------------------------------------------
                */

                if (!$userId) {
                    $userId = $user?->user_id ?? ((int) SamperinUser::max('user_id')) + 1;
                }

                /*
                |--------------------------------------------------------------------------
                | DATA
                |--------------------------------------------------------------------------
                |
                | Password TIDAK disentuh.
                |
                */

                $data = [
                    'user_id' => $userId,

                    'user_uid' => $uid,

                    'user_nip' => $nip,

                    'user_nik' => $nik,

                    'user_nama' => $nama,

                    'user_email' => $this->clean($row['user_email'] ?? null),

                    'user_status' => isset($row['user_status']) ? (int) $row['user_status'] : $user?->user_status ?? 1,
                ];

                /*
                |--------------------------------------------------------------------------
                | SIMPAN
                |--------------------------------------------------------------------------
                */

                if ($user) {
                    $user->update($data);
                    $update++;
                } else {
                    $user = SamperinUser::create($data);

                    $baru++;
                }
            }

            DB::commit();

            return [
                'baru' => $baru,
                'update' => $update,
                'skip' => $skip,
            ];
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE SQL INSERT
    |--------------------------------------------------------------------------
    */

    private function parseInsert(string $sql, string $table): array
    {
        $rows = [];

        $sql = preg_replace('/^\xEF\xBB\xBF/', '', $sql);

        $pattern = '/INSERT\s+INTO\s+[`"\']?' . preg_quote($table, '/') . '[`"\']?\s*' . '\((.*?)\)' . '\s*VALUES\s*/is';

        preg_match_all($pattern, $sql, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[0])) {
            return [];
        }

        foreach ($matches[0] as $index => $match) {
            $start = $match[1];

            $headerLength = strlen($match[0]);

            $columnsText = $matches[1][$index][0];

            $valuesStart = $start + $headerLength;

            $valuesText = $this->extractValuesUntilSemicolon($sql, $valuesStart);

            if ($valuesText === '') {
                continue;
            }

            $columns = array_map(function ($column) {
                return trim($column, " \t\n\r\0\x0B`\"'");
            }, explode(',', $columnsText));

            $valueGroups = $this->splitValueGroups($valuesText);

            foreach ($valueGroups as $group) {
                $values = $this->splitValues(trim($group, "() \t\n\r"));

                if (count($columns) !== count($values)) {
                    continue;
                }

                $row = [];

                foreach ($columns as $i => $column) {
                    $row[$column] = $this->parseSqlValue($values[$i]);
                }

                $rows[] = $row;
            }
        }

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | EXTRACT VALUES
    |--------------------------------------------------------------------------
    */

    private function extractValuesUntilSemicolon(string $sql, int $start): string
    {
        $length = strlen($sql);

        $quote = null;
        $depth = 0;

        for ($i = $start; $i < $length; $i++) {
            $char = $sql[$i];

            if ($quote !== null) {
                if ($char === $quote && ($i === 0 || $sql[$i - 1] !== '\\')) {
                    $quote = null;
                }

                continue;
            }

            if ($char === "'" || $char === '"') {
                $quote = $char;
                continue;
            }

            if ($char === '(') {
                $depth++;
            } elseif ($char === ')') {
                $depth--;
            } elseif ($char === ';' && $depth === 0) {
                return substr($sql, $start, $i - $start);
            }
        }

        return substr($sql, $start);
    }

    /*
    |--------------------------------------------------------------------------
    | SPLIT VALUE GROUP
    |--------------------------------------------------------------------------
    */

    private function splitValueGroups(string $text): array
    {
        $groups = [];

        $length = strlen($text);

        $depth = 0;
        $quote = null;
        $start = null;

        for ($i = 0; $i < $length; $i++) {
            $char = $text[$i];

            if ($quote !== null) {
                if ($char === $quote && ($i === 0 || $text[$i - 1] !== '\\')) {
                    $quote = null;
                }

                continue;
            }

            if ($char === "'" || $char === '"') {
                $quote = $char;
                continue;
            }

            if ($char === '(') {
                if ($depth === 0) {
                    $start = $i;
                }

                $depth++;
            }

            if ($char === ')') {
                $depth--;

                if ($depth === 0 && $start !== null) {
                    $groups[] = substr($text, $start, $i - $start + 1);

                    $start = null;
                }
            }
        }

        return $groups;
    }

    /*
    |--------------------------------------------------------------------------
    | SPLIT VALUES
    |--------------------------------------------------------------------------
    */

    private function splitValues(string $text): array
    {
        $values = [];

        $length = strlen($text);

        $quote = null;
        $start = 0;
        $depth = 0;

        for ($i = 0; $i < $length; $i++) {
            $char = $text[$i];

            if ($quote !== null) {
                if ($char === $quote && ($i === 0 || $text[$i - 1] !== '\\')) {
                    $quote = null;
                }

                continue;
            }

            if ($char === "'" || $char === '"') {
                $quote = $char;
                continue;
            }

            if ($char === '(') {
                $depth++;
                continue;
            }

            if ($char === ')') {
                $depth--;
                continue;
            }

            if ($char === ',' && $depth === 0) {
                $values[] = trim(substr($text, $start, $i - $start));

                $start = $i + 1;
            }
        }

        $values[] = trim(substr($text, $start));

        return $values;
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE SQL VALUE
    |--------------------------------------------------------------------------
    */

    private function parseSqlValue(string $value)
    {
        $value = trim($value);

        if (strtoupper($value) === 'NULL') {
            return null;
        }

        if (strlen($value) >= 2 && (($value[0] === "'" && $value[strlen($value) - 1] === "'") || ($value[0] === '"' && $value[strlen($value) - 1] === '"'))) {
            $value = substr($value, 1, -1);

            return stripslashes(str_replace("''", "'", $value));
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN
    |--------------------------------------------------------------------------
    */

    private function clean($value)
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | INTEGER
    |--------------------------------------------------------------------------
    */

    private function toInteger($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (!is_numeric($value)) {
            return null;
        }

        return (int) $value;
    }
}