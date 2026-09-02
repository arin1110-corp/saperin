<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinBidang;
use App\Models\SamperinEselon;
use App\Models\SamperinGolongan;
use App\Models\SamperinJabatan;
use App\Models\SamperinJenisKerja;
use App\Models\SamperinPendidikan;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class SamperinUserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SamperinUser::query()
            ->leftJoin('samperin_jabatan', 'samperin_user.user_jabatan_id', '=', 'samperin_jabatan.jabatan_id')
            ->leftJoin('samperin_bidang', 'samperin_user.user_bidang_id', '=', 'samperin_bidang.bidang_id')
            ->leftJoin('samperin_golongan', 'samperin_user.user_golongan_id', '=', 'samperin_golongan.golongan_id')
            ->leftJoin('samperin_jenis_kerja', 'samperin_user.user_jenis_kerja_id', '=', 'samperin_jenis_kerja.jenis_kerja_id')
            ->select('samperin_user.*');

        /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                /*
        |----------------------------------------------------------------------
        | DATA PEGAWAI
        |----------------------------------------------------------------------
        */

                $q->where('samperin_user.user_nip', 'like', '%' . $search . '%')
                    ->orWhere('samperin_user.user_nik', 'like', '%' . $search . '%')
                    ->orWhere('samperin_user.user_nama', 'like', '%' . $search . '%')
                    ->orWhere('samperin_user.user_email', 'like', '%' . $search . '%')
                    ->orWhere('samperin_user.user_notelp', 'like', '%' . $search . '%')
                    ->orWhere('samperin_user.user_alamat', 'like', '%' . $search . '%')
                    ->orWhere('samperin_user.user_lokasikerja', 'like', '%' . $search . '%');

                /*
        |----------------------------------------------------------------------
        | JABATAN
        |----------------------------------------------------------------------
        */

                $q->orWhere('samperin_jabatan.jabatan_nama', 'like', '%' . $search . '%')
                    ->orWhere('samperin_jabatan.jabatan_kategori', 'like', '%' . $search . '%');

                /*
        |----------------------------------------------------------------------
        | BIDANG
        |----------------------------------------------------------------------
        */

                $q->orWhere('samperin_bidang.bidang_nama', 'like', '%' . $search . '%');

                /*
        |----------------------------------------------------------------------
        | GOLONGAN
        |----------------------------------------------------------------------
        */

                $q->orWhere('samperin_golongan.golongan_nama', 'like', '%' . $search . '%');

                /*
        |----------------------------------------------------------------------
        | JENIS KERJA
        |----------------------------------------------------------------------
        */

                $q->orWhere('samperin_jenis_kerja.jenis_kerja_nama', 'like', '%' . $search . '%');

                /*
        |----------------------------------------------------------------------
        | STATUS
        |----------------------------------------------------------------------
        */

                if (stripos('aktif', $search) !== false) {
                    $q->orWhere('samperin_user.user_status', 1);
                }

                if (stripos('nonaktif', $search) !== false) {
                    $q->orWhere('samperin_user.user_status', 0);
                }
            });
        }

        /*
    |--------------------------------------------------------------------------
    | FILTER STATUS
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {
            $query->where('user_status', (int) $request->status);
        }

        /*
    |--------------------------------------------------------------------------
    | FILTER BIDANG
    |--------------------------------------------------------------------------
    */

        if ($request->filled('bidang')) {
            $query->where('user_bidang_id', (int) $request->bidang);
        }

        /*
    |--------------------------------------------------------------------------
    | FILTER JENIS KERJA
    |--------------------------------------------------------------------------
    */

        if ($request->filled('jenis_kerja')) {
            $query->where('user_jenis_kerja_id', (int) $request->jenis_kerja);
        }
        /*
|--------------------------------------------------------------------------
| FILTER LOKASI KERJA
|--------------------------------------------------------------------------
*/

        if ($request->filled('lokasi_kerja')) {
            $query->where('samperin_user.user_lokasikerja', $request->lokasi_kerja);
        }
        /*
    |--------------------------------------------------------------------------
    | DATA PEGAWAI
    |--------------------------------------------------------------------------
    */

        $pegawais = $query
            ->with('foto')
            ->orderBy('user_nama')
            ->paginate(10)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | MASTER
    |--------------------------------------------------------------------------
    */

        $bidangs = SamperinBidang::query()
            ->where('bidang_status', 1)
            ->orderBy('bidang_nama')
            ->get();

        $jabatans = SamperinJabatan::query()
            ->where('jabatan_status', 1)
            ->orderBy('jabatan_nama')
            ->get();

        $golongans = SamperinGolongan::query()
            ->where('golongan_status', 1)
            ->orderBy('golongan_nama')
            ->get();

        $eselons = SamperinEselon::query()
            ->where('eselon_status', 1)
            ->orderBy('eselon_nama')
            ->get();

        $pendidikans = SamperinPendidikan::query()
            ->where('pendidikan_status', 1)
            ->orderBy('pendidikan_jenjang')
            ->orderBy('pendidikan_jurusan')
            ->get();

        $jenisKerjas = SamperinJenisKerja::query()
            ->where('jenis_kerja_status', 1)
            ->orderBy('jenis_kerja_nama')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | STATISTIK
    |--------------------------------------------------------------------------
    */

        $totalPegawai = SamperinUser::count();

        $pegawaiAktif = SamperinUser::where('user_status', 1)->count();

        $pegawaiNonaktif = SamperinUser::where('user_status', 0)->count();

        /*
    |--------------------------------------------------------------------------
    | STATISTIK JENIS KERJA AKTIF
    |--------------------------------------------------------------------------
    */

        $statJenisKerja = SamperinJenisKerja::query()
            ->where('jenis_kerja_status', 1)
            ->leftJoin('samperin_user', function ($join) {
                $join->on(
                    'samperin_jenis_kerja.jenis_kerja_id',
                    '=',
                    'samperin_user.user_jenis_kerja_id'
                )->where('samperin_user.user_status', 1);
            })
            ->select(
                'samperin_jenis_kerja.jenis_kerja_id',
                'samperin_jenis_kerja.jenis_kerja_nama'
            )
            ->selectRaw('COUNT(samperin_user.user_id) as jumlah')
            ->groupBy(
                'samperin_jenis_kerja.jenis_kerja_id',
                'samperin_jenis_kerja.jenis_kerja_nama'
            )
            ->orderBy('samperin_jenis_kerja.jenis_kerja_id')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | STATISTIK BIDANG AKTIF
    |--------------------------------------------------------------------------
    */

        $statBidang = SamperinBidang::query()
            ->where('bidang_status', 1)
            ->leftJoin('samperin_user', function ($join) {
                $join->on(
                    'samperin_bidang.bidang_id',
                    '=',
                    'samperin_user.user_bidang_id'
                )->where('samperin_user.user_status', 1);
            })
            ->select(
                'samperin_bidang.bidang_id',
                'samperin_bidang.bidang_nama'
            )
            ->selectRaw('COUNT(samperin_user.user_id) as jumlah')
            ->groupBy(
                'samperin_bidang.bidang_id',
                'samperin_bidang.bidang_nama'
            )
            ->orderBy('samperin_bidang.bidang_id')
            ->get();
        /*
|--------------------------------------------------------------------------
| STATISTIK LOKASI KERJA AKTIF
|--------------------------------------------------------------------------
*/

        $statLokasi = SamperinUser::query()
            ->where('user_status', 1)
            ->whereNotNull('user_lokasikerja')
            ->where('user_lokasikerja', '!=', '')
            ->select('user_lokasikerja')
            ->selectRaw('COUNT(user_id) as jumlah')
            ->groupBy('user_lokasikerja')
            ->orderByRaw("
    CASE
        WHEN user_lokasikerja LIKE 'Kota %' THEN 2
        WHEN user_lokasikerja LIKE 'Kabupaten %' THEN 2
        ELSE 1
    END
")
            ->orderByRaw("
    CASE
        WHEN user_lokasikerja LIKE 'Kota %' THEN 1
        ELSE 2
    END
")
            ->orderBy('user_lokasikerja')
            ->get();
        /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

        return view('dashboard.data-pegawai.index', compact(
            'pegawais',
            'bidangs',
            'jabatans',
            'golongans',
            'eselons',
            'pendidikans',
            'jenisKerjas',
            'totalPegawai',
            'pegawaiAktif',
            'pegawaiNonaktif',
            'statJenisKerja',
            'statBidang',
            'statLokasi'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $this->validatePegawai($request);

        /*
        |--------------------------------------------------------------------------
        | CEK NIP
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['user_nip']) &&
            SamperinUser::where('user_nip', trim($validated['user_nip']))->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'user_nip' => 'NIP tersebut sudah digunakan.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK NIK
        |--------------------------------------------------------------------------
        */

        if (
            !empty($validated['user_nik']) &&
            SamperinUser::where('user_nik', trim($validated['user_nik']))->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'user_nik' => 'NIK tersebut sudah digunakan.',
                ]);
        }

        try {
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | ID BARU
            |--------------------------------------------------------------------------
            */

            $nextId = ((int) SamperinUser::max('user_id')) + 1;

            /*
            |--------------------------------------------------------------------------
            | DATA
            |--------------------------------------------------------------------------
            */

            $data = $this->preparePegawaiData($validated);

            $data['user_id'] = $nextId;

            /*
            |--------------------------------------------------------------------------
            | UID
            |--------------------------------------------------------------------------
            */

            $data['user_uid'] = (string) Str::uuid();

            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            if (!empty($validated['user_password'])) {
                $data['user_password'] = Hash::make($validated['user_password']);
            } else {
                $data['user_password'] = null;
            }

            /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

            SamperinUser::create($data);

            DB::commit();

            return redirect()
                ->route('kepeg.pegawai.index')
                ->with('success', 'Data pegawai berhasil ditambahkan.');
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('SAMPERIN PEGAWAI STORE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'pegawai' => 'Gagal menambahkan pegawai: ' . $e->getMessage(),
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
        $pegawai = SamperinUser::where('user_uid', $uid)->firstOrFail();

        $validated = $this->validatePegawai($request, $pegawai);

        /*
        |--------------------------------------------------------------------------
        | CEK NIP
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['user_nip'])) {
            $nipExists = SamperinUser::where(
                'user_nip',
                trim($validated['user_nip'])
            )
                ->where('user_id', '!=', $pegawai->user_id)
                ->exists();

            if ($nipExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'user_nip' => 'NIP tersebut sudah digunakan.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CEK NIK
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['user_nik'])) {
            $nikExists = SamperinUser::where(
                'user_nik',
                trim($validated['user_nik'])
            )
                ->where('user_id', '!=', $pegawai->user_id)
                ->exists();

            if ($nikExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'user_nik' => 'NIK tersebut sudah digunakan.',
                    ]);
            }
        }

        try {
            $data = $this->preparePegawaiData($validated);

            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            if (!empty($validated['user_password'])) {
                $data['user_password'] = Hash::make($validated['user_password']);
            } else {
                unset($data['user_password']);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $pegawai->update($data);

            return redirect()
                ->route('kepeg.pegawai.index')
                ->with('success', 'Data pegawai berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN PEGAWAI UPDATE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'pegawai' => 'Gagal memperbarui pegawai: ' . $e->getMessage(),
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
        $pegawai = SamperinUser::where('user_uid', $uid)->firstOrFail();

        $pegawai->user_status = (int) $pegawai->user_status === 1 ? 0 : 1;

        $pegawai->save();

        return back()->with(
            'success',
            $pegawai->user_status === 1
                ? 'Pegawai berhasil diaktifkan.'
                : 'Pegawai berhasil dinonaktifkan.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $uid)
    {
        $pegawai = SamperinUser::where('user_uid', $uid)->firstOrFail();

        try {
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | HAPUS ROLE
            |--------------------------------------------------------------------------
            */

            if (Schema::hasTable('samperin_user_role')) {
                DB::table('samperin_user_role')
                    ->where('user_role_user_uid', $pegawai->user_uid)
                    ->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | HAPUS FOTO
            |--------------------------------------------------------------------------
            */

            if (Schema::hasTable('samperin_user_foto')) {
                DB::table('samperin_user_foto')
                    ->where('user_foto_user_uid', $pegawai->user_uid)
                    ->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | HAPUS PEGAWAI
            |--------------------------------------------------------------------------
            */

            $pegawai->delete();

            DB::commit();

            return back()->with(
                'success',
                'Data pegawai berhasil dihapus.'
            );
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('SAMPERIN PEGAWAI DELETE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'pegawai' => 'Gagal menghapus pegawai: ' . $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT PAGE
    |--------------------------------------------------------------------------
    */

    public function import()
    {
        return view('dashboard.data-pegawai.import');
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT PROCESS
    |--------------------------------------------------------------------------
    |
    | Mendukung:
    |
    | - SQL dump sadarin_user
    | - XLS
    | - XLSX
    |
    */

    public function importProcess(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI FILE
        |--------------------------------------------------------------------------
        |
        | Jangan menggunakan mimes:xls,xlsx,sql karena MIME SQL
        | sering dianggap text/plain oleh Laravel/PHP.
        |
        */

        $request->validate(
            [
                'file' => [
                    'required',
                    'file',
                    'max:10240',
                ],
            ],
            [
                'file.required' => 'File wajib dipilih.',
                'file.file' => 'File tidak valid.',
                'file.max' => 'Ukuran file maksimal 10 MB.',
            ]
        );

        $file = $request->file('file');

        if (!$file || !$file->isValid()) {
            return back()->withErrors([
                'import' => 'File tidak dapat dibaca.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK EXTENSION
        |--------------------------------------------------------------------------
        */

        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['sql', 'xls', 'xlsx'], true)) {
            return back()->withErrors([
                'import' => 'File harus berformat SQL, XLS, atau XLSX.',
            ]);
        }

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

        return $this->importExcel($file);
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
        | CARI INSERT SADARIN_USER
        |--------------------------------------------------------------------------
        */

            $pattern = '/INSERT\s+INTO\s+(?:`[^`]+`\.)?`?sadarin_user`?\s*'
                . '\((.*?)\)\s*VALUES\s*(.*?);/is';

            preg_match_all(
                $pattern,
                $sql,
                $matches,
                PREG_SET_ORDER
            );

            if (empty($matches)) {
                return back()->withErrors([
                    'import' => 'INSERT INTO sadarin_user tidak ditemukan di dalam file SQL.',
                ]);
            }

            $inserted = 0;
            $updated = 0;
            $skipped = 0;

            /*
        |--------------------------------------------------------------------------
        | KUMPULKAN DATA SQL TERLEBIH DAHULU
        |--------------------------------------------------------------------------
        |
        | Tujuannya supaya kalau ada NIK duplikat di Sadarin,
        | kita bisa memilih record yang benar sebelum masuk Samperin.
        |
        */

            $sources = [];

            foreach ($matches as $statement) {
                $columnString = $statement[1];
                $valuesString = $statement[2];

                /*
            |--------------------------------------------------------------------------
            | PARSE COLUMN
            |--------------------------------------------------------------------------
            */

                $columns = $this->parseSqlColumns($columnString);

                if (empty($columns)) {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | PARSE ROW
            |--------------------------------------------------------------------------
            */

                $rows = $this->parseSqlRows($valuesString);

                foreach ($rows as $values) {
                    if (count($columns) !== count($values)) {
                        $skipped++;

                        continue;
                    }

                    $source = [];

                    foreach ($columns as $index => $column) {
                        $source[$column] = $values[$index] ?? null;
                    }

                    /*
                |--------------------------------------------------------------------------
                | USER ID
                |--------------------------------------------------------------------------
                */

                    $userId = $this->sqlClean(
                        $source['user_id'] ?? null
                    );

                    if (
                        $userId === null ||
                        !is_numeric($userId)
                    ) {
                        $skipped++;

                        continue;
                    }

                    $userId = (int) $userId;

                    /*
                |--------------------------------------------------------------------------
                | NAMA
                |--------------------------------------------------------------------------
                */

                    $nama = $this->sqlClean(
                        $source['user_nama'] ?? null
                    );

                    if ($nama === null) {
                        $skipped++;

                        continue;
                    }

                    /*
                |--------------------------------------------------------------------------
                | NIK
                |--------------------------------------------------------------------------
                */

                    $nik = $this->sqlClean(
                        $source['user_nik'] ?? null
                    );

                    /*
                |--------------------------------------------------------------------------
                | PASSWORD
                |--------------------------------------------------------------------------
                */

                    $password = null;

                    if (
                        array_key_exists(
                            'user_password',
                            $source
                        )
                    ) {
                        $password = $this->sqlPassword(
                            $source['user_password']
                        );
                    }

                    /*
                |--------------------------------------------------------------------------
                | KEY DUPLIKAT
                |--------------------------------------------------------------------------
                |
                | Kalau NIK ada → gunakan NIK sebagai identitas duplikat.
                |
                | Kalau NIK kosong → gunakan user_id.
                |
                */

                    $duplicateKey = $nik !== null
                        ? 'nik:' . $nik
                        : 'id:' . $userId;

                    /*
                |--------------------------------------------------------------------------
                | DATA BARU
                |--------------------------------------------------------------------------
                */

                    $candidate = [
                        'source' => $source,
                        'user_id' => $userId,
                        'password' => $password,
                    ];

                    /*
                |--------------------------------------------------------------------------
                | CEK DUPLIKAT DALAM SQL
                |--------------------------------------------------------------------------
                */

                    if (!isset($sources[$duplicateKey])) {
                        $sources[$duplicateKey] = $candidate;

                        continue;
                    }

                    $existing = $sources[$duplicateKey];

                    /*
                |--------------------------------------------------------------------------
                | PRIORITAS DATA
                |--------------------------------------------------------------------------
                |
                | 1. Punya password lebih tinggi.
                | 2. Kalau sama-sama punya / sama-sama tidak punya password,
                |    pilih user_id paling kecil = data paling lama.
                |
                */

                    $existingHasPassword =
                        !empty($existing['password']);

                    $candidateHasPassword =
                        !empty($candidate['password']);

                    $replace = false;

                    /*
                |--------------------------------------------------------------------------
                | CANDIDATE PUNYA PASSWORD
                |--------------------------------------------------------------------------
                */

                    if (
                        $candidateHasPassword &&
                        !$existingHasPassword
                    ) {
                        $replace = true;
                    }

                    /*
                |--------------------------------------------------------------------------
                | SAMA-SAMA PUNYA PASSWORD
                |--------------------------------------------------------------------------
                |
                | Pilih record paling lama.
                |
                */ elseif (
                        $candidateHasPassword &&
                        $existingHasPassword
                    ) {
                        if (
                            $candidate['user_id'] <
                            $existing['user_id']
                        ) {
                            $replace = true;
                        }
                    }

                    /*
                |--------------------------------------------------------------------------
                | SAMA-SAMA TIDAK PUNYA PASSWORD
                |--------------------------------------------------------------------------
                |
                | Pilih record paling lama.
                |
                */ elseif (
                        !$candidateHasPassword &&
                        !$existingHasPassword
                    ) {
                        if (
                            $candidate['user_id'] <
                            $existing['user_id']
                        ) {
                            $replace = true;
                        }
                    }

                    if ($replace) {
                        $sources[$duplicateKey] = $candidate;
                    }
                }
            }

            /*
        |--------------------------------------------------------------------------
        | IMPORT DATA YANG SUDAH DIBERSIHKAN
        |--------------------------------------------------------------------------
        */

            DB::beginTransaction();

            foreach ($sources as $candidate) {
                $source = $candidate['source'];

                $userId = $candidate['user_id'];

                $password = $candidate['password'];

                /*
            |--------------------------------------------------------------------------
            | BUILD DATA
            |--------------------------------------------------------------------------
            */

                $data = $this->buildSadarinImportData(
                    $source
                );

                /*
            |--------------------------------------------------------------------------
            | CARI BERDASARKAN USER ID
            |--------------------------------------------------------------------------
            */

                $pegawai = SamperinUser::where(
                    'user_id',
                    $userId
                )->first();

                /*
            |--------------------------------------------------------------------------
            | KALAU BELUM ADA, CEK NIK
            |--------------------------------------------------------------------------
            |
            | Ini penting untuk data Samperin yang mungkin sudah
            | memiliki user_id berbeda tetapi NIK sama.
            |
            */

                if (!$pegawai) {
                    $nik = $this->sqlClean(
                        $source['user_nik'] ?? null
                    );

                    if ($nik !== null) {
                        $pegawai = SamperinUser::where(
                            'user_nik',
                            $nik
                        )->first();
                    }
                }

                /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

                if (!$pegawai) {
                    $data['user_id'] = $userId;

                    /*
                |--------------------------------------------------------------------------
                | UID BARU
                |--------------------------------------------------------------------------
                */

                    $data['user_uid'] = (string) Str::uuid();

                    /*
                |--------------------------------------------------------------------------
                | PASSWORD SADARIN
                |--------------------------------------------------------------------------
                |
                | Password sudah hash dari database Sadarin.
                | Jangan Hash::make() lagi.
                |
                */

                    $data['user_password'] = $password;

                    SamperinUser::create($data);

                    $inserted++;
                }

                /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */ else {
                    /*
                |--------------------------------------------------------------------------
                | PASSWORD
                |--------------------------------------------------------------------------
                |
                | Kalau record pilihan punya password,
                | update password Samperin.
                |
                | Kalau tidak punya password, password lama
                | di Samperin jangan dihapus.
                |
                */

                    if ($password !== null) {
                        $data['user_password'] = $password;
                    }

                    /*
                |--------------------------------------------------------------------------
                | JANGAN UBAH ID / UID
                |--------------------------------------------------------------------------
                */

                    unset(
                        $data['user_id'],
                        $data['user_uid']
                    );

                    $pegawai->update($data);

                    $updated++;
                }
            }

            DB::commit();

            return redirect()
                ->route('kepeg.pegawai.index')
                ->with(
                    'success',
                    "Import SQL berhasil. {$inserted} data ditambahkan, {$updated} data diperbarui, {$skipped} data dilewati."
                );
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error(
                'SAMPERIN PEGAWAI IMPORT SQL',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

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

    private function importExcel($file)
    {
        try {
            $spreadsheet = IOFactory::load(
                $file->getRealPath()
            );

            $sheet = $spreadsheet->getActiveSheet();

            $rows = $sheet->toArray(
                null,
                true,
                true,
                true
            );

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

            $columnMap = [];

            foreach ($header as $key => $value) {
                $name = strtolower(
                    trim((string) $value)
                );

                $name = str_replace(
                    [' ', '-', '.'],
                    '_',
                    $name
                );

                $columnMap[$name] = $key;
            }

            /*
            |--------------------------------------------------------------------------
            | NIP TIDAK WAJIB
            |--------------------------------------------------------------------------
            */

            if (
                !isset($columnMap['user_nama']) &&
                !isset($columnMap['nama'])
            ) {
                return back()->withErrors([
                    'import' => 'Excel wajib memiliki kolom user_nama atau nama.',
                ]);
            }

            $inserted = 0;
            $updated = 0;
            $skipped = 0;

            $nextId = (
                (int) SamperinUser::max('user_id')
            ) + 1;

            DB::beginTransaction();

            foreach ($rows as $row) {
                $nip = $this->clean(
                    $this->excelValue(
                        $row,
                        $columnMap,
                        ['user_nip', 'nip']
                    )
                );

                $nama = $this->clean(
                    $this->excelValue(
                        $row,
                        $columnMap,
                        ['user_nama', 'nama']
                    )
                );

                if ($nip === null && $nama === null) {
                    continue;
                }

                if ($nama === null) {
                    $skipped++;

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | USER ID
                |--------------------------------------------------------------------------
                */

                $userIdValue = $this->excelValue(
                    $row,
                    $columnMap,
                    ['user_id', 'id']
                );

                $userId = $this->integerOrNull(
                    $userIdValue
                );

                /*
                |--------------------------------------------------------------------------
                | CARI PEGAWAI
                |--------------------------------------------------------------------------
                */

                $pegawai = null;

                if ($userId !== null) {
                    $pegawai = SamperinUser::where(
                        'user_id',
                        $userId
                    )->first();
                }

                if (!$pegawai && $nip !== null) {
                    $pegawai = SamperinUser::where(
                        'user_nip',
                        $nip
                    )->first();
                }

                /*
                |--------------------------------------------------------------------------
                | INSERT
                |--------------------------------------------------------------------------
                */

                if (!$pegawai) {
                    if ($userId === null) {
                        $userId = $nextId;

                        $nextId++;
                    }

                    $data = $this->buildImportData(
                        $row,
                        $columnMap
                    );

                    $data['user_id'] = $userId;

                    $data['user_uid'] = (string) Str::uuid();

                    /*
                    |--------------------------------------------------------------------------
                    | PASSWORD EXCEL
                    |--------------------------------------------------------------------------
                    */

                    $password = $this->excelValue(
                        $row,
                        $columnMap,
                        ['user_password', 'password']
                    );

                    if (
                        $password !== null &&
                        $password !== ''
                    ) {
                        $data['user_password'] = Hash::make(
                            $password
                        );
                    } else {
                        $data['user_password'] = null;
                    }

                    SamperinUser::create($data);

                    $inserted++;
                } else {
                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */

                    $data = $this->buildImportData(
                        $row,
                        $columnMap
                    );

                    $password = $this->excelValue(
                        $row,
                        $columnMap,
                        ['user_password', 'password']
                    );

                    if (
                        $password !== null &&
                        $password !== ''
                    ) {
                        $data['user_password'] = Hash::make(
                            $password
                        );
                    }

                    unset(
                        $data['user_uid'],
                        $data['user_id']
                    );

                    $pegawai->update($data);

                    $updated++;
                }
            }

            DB::commit();

            return redirect()
                ->route('kepeg.pegawai.index')
                ->with(
                    'success',
                    "Import Excel berhasil. {$inserted} data ditambahkan, {$updated} data diperbarui, {$skipped} data dilewati."
                );
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('SAMPERIN PEGAWAI IMPORT EXCEL', [
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
    | BUILD DATA DARI SADARIN
    |--------------------------------------------------------------------------
    */

    private function buildSadarinImportData(array $source): array
    {
        /*
        |--------------------------------------------------------------------------
        | FIELD SADARIN → SAMPERIN
        |--------------------------------------------------------------------------
        */

        $mapping = [
            'user_nip' => 'user_nip',
            'user_nama' => 'user_nama',
            'user_nik' => 'user_nik',

            'user_tgllahir' => 'user_tgllahir',

            'user_jabatan' => 'user_jabatan_id',

            'user_npwp' => 'user_npwp',

            'user_pendidikan' => 'user_pendidikan_id',

            'user_norek' => 'user_norek_bpd',

            'user_tmt' => 'user_tmt',

            'user_spmt' => 'user_spmt',

            'user_gelardepan' => 'user_gelardepan',

            'user_gelarbelakang' => 'user_gelarbelakang',

            'user_kelasjabatan' => 'user_kelasjabatan',

            'user_eselon' => 'user_eselon_id',

            'user_golongan' => 'user_golongan_id',

            'user_email' => 'user_email',

            'user_notelp' => 'user_notelp',

            'user_alamat' => 'user_alamat',

            'user_jk' => 'user_jk',

            'user_bidang' => 'user_bidang_id',

            'user_jmltanggungan' => 'user_jmltanggungan',

            'user_status' => 'user_status',

            'user_jeniskerja' => 'user_jenis_kerja_id',

            'user_bpjs' => 'user_bpjs',

            'user_tempatlahir' => 'user_tempatlahir',

            'user_ket' => 'user_keterangan',

            'user_lokasikerja' => 'user_lokasikerja',
        ];

        $data = [];

        foreach ($mapping as $sadarinField => $samperinField) {
            $value = $source[$sadarinField] ?? null;

            $value = $this->sqlClean($value);

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY 0 → NULL
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $samperinField,
                    [
                        'user_jabatan_id',
                        'user_bidang_id',
                        'user_golongan_id',
                        'user_eselon_id',
                        'user_pendidikan_id',
                        'user_jenis_kerja_id',
                    ],
                    true
                )
            ) {
                if (
                    $value === null ||
                    $value === '0' ||
                    $value === 0
                ) {
                    $value = null;
                } else {
                    $value = (int) $value;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | JUMLAH TANGGUNGAN
            |--------------------------------------------------------------------------
            */

            if ($samperinField === 'user_jmltanggungan') {
                if ($value === null) {
                    $value = 0;
                } else {
                    $value = (int) $value;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            if ($samperinField === 'user_status') {
                if ($value === null) {
                    $value = 1;
                } else {
                    $value = (int) $value;
                }
            }

            $data[$samperinField] = $value;
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    private function validatePegawai(
        Request $request,
        ?SamperinUser $pegawai = null
    ): array {
        return $request->validate(
            [
                /*
                |--------------------------------------------------------------------------
                | IDENTITAS
                |--------------------------------------------------------------------------
                */

                'user_nip' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_nik' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_nama' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'user_gelardepan' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_gelarbelakang' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_tempatlahir' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_tgllahir' => [
                    'nullable',
                    'date',
                ],

                'user_jk' => [
                    'nullable',
                    'string',
                    'max:1',
                ],

                /*
                |--------------------------------------------------------------------------
                | MASTER
                |--------------------------------------------------------------------------
                */

                'user_jabatan_id' => [
                    'nullable',
                    'integer',
                    'exists:samperin_jabatan,jabatan_id',
                ],

                'user_bidang_id' => [
                    'nullable',
                    'integer',
                    'exists:samperin_bidang,bidang_id',
                ],

                'user_golongan_id' => [
                    'nullable',
                    'integer',
                    'exists:samperin_golongan,golongan_id',
                ],

                'user_eselon_id' => [
                    'nullable',
                    'integer',
                    'exists:samperin_eselon,eselon_id',
                ],

                'user_pendidikan_id' => [
                    'nullable',
                    'integer',
                    'exists:samperin_pendidikan,pendidikan_id',
                ],

                'user_jenis_kerja_id' => [
                    'nullable',
                    'integer',
                    'exists:samperin_jenis_kerja,jenis_kerja_id',
                ],

                /*
                |--------------------------------------------------------------------------
                | KEPEGAWAIAN
                |--------------------------------------------------------------------------
                */

                'user_tmt' => [
                    'nullable',
                    'date',
                ],

                'user_spmt' => [
                    'nullable',
                    'date',
                ],

                'user_npwp' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_bpjs' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_norek_bpd' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_kelasjabatan' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_jmltanggungan' => [
                    'nullable',
                    'integer',
                    'min:0',
                ],

                /*
                |--------------------------------------------------------------------------
                | KONTAK
                |--------------------------------------------------------------------------
                */

                'user_email' => [
                    'nullable',
                    'email',
                    'max:100',
                ],

                'user_notelp' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_alamat' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'user_lokasikerja' => [
                    'nullable',
                    'string',
                    'max:100',
                ],

                'user_keterangan' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                'user_status' => [
                    'required',
                    'in:0,1',
                ],

                /*
                |--------------------------------------------------------------------------
                | PASSWORD
                |--------------------------------------------------------------------------
                */

                'user_password' => [
                    'nullable',
                    'string',
                    'min:6',
                    'max:255',
                ],
            ],
            [
                'user_nama.required' =>
                'Nama pegawai wajib diisi.',

                'user_status.required' =>
                'Status pegawai wajib dipilih.',

                'user_status.in' =>
                'Status pegawai tidak valid.',

                'user_jabatan_id.exists' =>
                'Jabatan tidak ditemukan.',

                'user_bidang_id.exists' =>
                'Bidang tidak ditemukan.',

                'user_golongan_id.exists' =>
                'Golongan tidak ditemukan.',

                'user_eselon_id.exists' =>
                'Eselon tidak ditemukan.',

                'user_pendidikan_id.exists' =>
                'Pendidikan tidak ditemukan.',

                'user_jenis_kerja_id.exists' =>
                'Jenis kerja tidak ditemukan.',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PREPARE DATA
    |--------------------------------------------------------------------------
    */

    private function preparePegawaiData(
        array $validated
    ): array {
        $fields = [
            'user_nip',
            'user_nik',
            'user_nama',

            'user_gelardepan',
            'user_gelarbelakang',

            'user_tempatlahir',
            'user_tgllahir',
            'user_jk',

            'user_jabatan_id',
            'user_bidang_id',
            'user_golongan_id',
            'user_eselon_id',
            'user_pendidikan_id',
            'user_jenis_kerja_id',

            'user_tmt',
            'user_spmt',

            'user_npwp',
            'user_bpjs',
            'user_norek_bpd',

            'user_kelasjabatan',
            'user_jmltanggungan',

            'user_email',
            'user_notelp',
            'user_alamat',

            'user_lokasikerja',
            'user_keterangan',

            'user_status',
        ];

        $data = [];

        foreach ($fields as $field) {
            $value = $validated[$field] ?? null;

            if (is_string($value)) {
                $value = trim($value);
            }

            $data[$field] = $value;
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | BUILD IMPORT EXCEL
    |--------------------------------------------------------------------------
    */

    private function buildImportData(
        array $row,
        array $columnMap
    ): array {
        $data = [];

        $mapping = [
            'user_nip' => [
                'user_nip',
                'nip',
            ],

            'user_nik' => [
                'user_nik',
                'nik',
            ],

            'user_nama' => [
                'user_nama',
                'nama',
            ],

            'user_gelardepan' => [
                'user_gelardepan',
                'gelardepan',
            ],

            'user_gelarbelakang' => [
                'user_gelarbelakang',
                'gelarbelakang',
            ],

            'user_tempatlahir' => [
                'user_tempatlahir',
                'tempatlahir',
            ],

            'user_tgllahir' => [
                'user_tgllahir',
                'tgllahir',
                'tanggal_lahir',
            ],

            'user_jk' => [
                'user_jk',
                'jk',
            ],

            'user_jabatan_id' => [
                'user_jabatan_id',
                'jabatan_id',
                'user_jabatan',
            ],

            'user_bidang_id' => [
                'user_bidang_id',
                'bidang_id',
                'user_bidang',
            ],

            'user_golongan_id' => [
                'user_golongan_id',
                'golongan_id',
                'user_golongan',
            ],

            'user_eselon_id' => [
                'user_eselon_id',
                'eselon_id',
                'user_eselon',
            ],

            'user_pendidikan_id' => [
                'user_pendidikan_id',
                'pendidikan_id',
                'user_pendidikan',
            ],

            'user_jenis_kerja_id' => [
                'user_jenis_kerja_id',
                'jenis_kerja_id',
                'user_jeniskerja',
            ],

            'user_tmt' => [
                'user_tmt',
                'tmt',
            ],

            'user_spmt' => [
                'user_spmt',
                'spmt',
            ],

            'user_npwp' => [
                'user_npwp',
                'npwp',
            ],

            'user_bpjs' => [
                'user_bpjs',
                'bpjs',
            ],

            'user_norek_bpd' => [
                'user_norek_bpd',
                'user_norek',
                'norek',
            ],

            'user_kelasjabatan' => [
                'user_kelasjabatan',
                'kelasjabatan',
            ],

            'user_jmltanggungan' => [
                'user_jmltanggungan',
                'jmltanggungan',
            ],

            'user_email' => [
                'user_email',
                'email',
            ],

            'user_notelp' => [
                'user_notelp',
                'notelp',
                'no_telp',
            ],

            'user_alamat' => [
                'user_alamat',
                'alamat',
            ],

            'user_lokasikerja' => [
                'user_lokasikerja',
                'lokasikerja',
                'lokasi_kerja',
            ],

            'user_keterangan' => [
                'user_keterangan',
                'user_ket',
                'keterangan',
            ],

            'user_status' => [
                'user_status',
                'status',
            ],
        ];

        foreach ($mapping as $field => $aliases) {
            $value = $this->excelValue(
                $row,
                $columnMap,
                $aliases
            );

            if (is_string($value)) {
                $value = trim($value);
            }

            /*
            |--------------------------------------------------------------------------
            | FK 0 → NULL
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $field,
                    [
                        'user_jabatan_id',
                        'user_bidang_id',
                        'user_golongan_id',
                        'user_eselon_id',
                        'user_pendidikan_id',
                        'user_jenis_kerja_id',
                    ],
                    true
                )
            ) {
                if (
                    $value === null ||
                    $value === '' ||
                    (string) $value === '0'
                ) {
                    $value = null;
                } else {
                    $value = (int) $value;
                }
            }

            $data[$field] = $value;
        }

        /*
        |--------------------------------------------------------------------------
        | INTEGER
        |--------------------------------------------------------------------------
        */

        if (
            $data['user_jmltanggungan'] === null ||
            $data['user_jmltanggungan'] === ''
        ) {
            $data['user_jmltanggungan'] = 0;
        } else {
            $data['user_jmltanggungan'] =
                (int) $data['user_jmltanggungan'];
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            $data['user_status'] === null ||
            $data['user_status'] === ''
        ) {
            $data['user_status'] = 1;
        } else {
            $data['user_status'] =
                (int) $data['user_status'];
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE SQL COLUMNS
    |--------------------------------------------------------------------------
    */

    private function parseSqlColumns(string $columnString): array
    {
        $columns = [];

        $parts = $this->splitSqlFields(
            $columnString,
            ','
        );

        foreach ($parts as $column) {
            $column = trim($column);

            $column = trim(
                $column,
                " \t\n\r\0\x0B`"
            );

            if ($column !== '') {
                $columns[] = strtolower($column);
            }
        }

        return $columns;
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE SQL ROWS
    |--------------------------------------------------------------------------
    */

    private function parseSqlRows(string $valuesString): array
    {
        $rows = [];

        $length = strlen($valuesString);

        $inString = false;
        $escape = false;
        $depth = 0;

        $current = '';

        for ($i = 0; $i < $length; $i++) {
            $char = $valuesString[$i];

            if ($escape) {
                $current .= $char;
                $escape = false;

                continue;
            }

            if ($char === '\\' && $inString) {
                $current .= $char;
                $escape = true;

                continue;
            }

            if ($char === "'") {
                $current .= $char;

                /*
                |--------------------------------------------------------------------------
                | DOUBLE SINGLE QUOTE
                |--------------------------------------------------------------------------
                */

                if (
                    $inString &&
                    $i + 1 < $length &&
                    $valuesString[$i + 1] === "'"
                ) {
                    $current .= $valuesString[$i + 1];

                    $i++;

                    continue;
                }

                $inString = !$inString;

                continue;
            }

            if (!$inString) {
                if ($char === '(') {
                    if ($depth === 0) {
                        $current = '';
                    }

                    $depth++;

                    if ($depth > 1) {
                        $current .= $char;
                    }

                    continue;
                }

                if ($char === ')') {
                    $depth--;

                    if ($depth === 0) {
                        $rows[] = $this->parseSqlRow(
                            $current
                        );

                        $current = '';

                        continue;
                    }

                    $current .= $char;

                    continue;
                }
            }

            if ($depth > 0) {
                $current .= $char;
            }
        }

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE SQL ROW
    |--------------------------------------------------------------------------
    */

    private function parseSqlRow(string $row): array
    {
        return $this->splitSqlFields(
            $row,
            ','
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SPLIT SQL FIELDS
    |--------------------------------------------------------------------------
    */

    private function splitSqlFields(
        string $value,
        string $delimiter = ','
    ): array {
        $parts = [];

        $length = strlen($value);

        $current = '';

        $inString = false;
        $escape = false;

        for ($i = 0; $i < $length; $i++) {
            $char = $value[$i];

            if ($escape) {
                $current .= $char;
                $escape = false;

                continue;
            }

            if ($char === '\\' && $inString) {
                $current .= $char;
                $escape = true;

                continue;
            }

            if ($char === "'") {
                $current .= $char;

                /*
                |--------------------------------------------------------------------------
                | SQL '' ESCAPE
                |--------------------------------------------------------------------------
                */

                if (
                    $inString &&
                    $i + 1 < $length &&
                    $value[$i + 1] === "'"
                ) {
                    $current .= $value[$i + 1];

                    $i++;

                    continue;
                }

                $inString = !$inString;

                continue;
            }

            if (
                !$inString &&
                $char === $delimiter
            ) {
                $parts[] = trim($current);

                $current = '';

                continue;
            }

            $current .= $char;
        }

        $parts[] = trim($current);

        return array_map(
            fn($item) => $this->parseSqlValue($item),
            $parts
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE SQL VALUE
    |--------------------------------------------------------------------------
    */

    private function parseSqlValue($value)
    {
        $value = trim((string) $value);

        if (
            strtoupper($value) === 'NULL' ||
            $value === ''
        ) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | QUOTED STRING
        |--------------------------------------------------------------------------
        */

        if (
            strlen($value) >= 2 &&
            $value[0] === "'" &&
            $value[strlen($value) - 1] === "'"
        ) {
            $value = substr(
                $value,
                1,
                -1
            );

            /*
            |--------------------------------------------------------------------------
            | MYSQL ESCAPE
            |--------------------------------------------------------------------------
            */

            $value = str_replace(
                [
                    "\\0",
                    "\\'",
                    '\\"',
                    "\\n",
                    "\\r",
                    "\\t",
                    "\\\\",
                ],
                [
                    "\0",
                    "'",
                    '"',
                    "\n",
                    "\r",
                    "\t",
                    "\\",
                ],
                $value
            );

            /*
            |--------------------------------------------------------------------------
            | SQL DOUBLE QUOTE
            |--------------------------------------------------------------------------
            */

            $value = str_replace(
                "''",
                "'",
                $value
            );
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN SQL
    |--------------------------------------------------------------------------
    */

    private function sqlClean($value)
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | DASH → NULL
        |--------------------------------------------------------------------------
        */

        if ($value === '-') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | NULL STRING → NULL
        |--------------------------------------------------------------------------
        */

        if (strtoupper($value) === 'NULL') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | ZERO DATE
        |--------------------------------------------------------------------------
        */

        if (
            $value === '0000-00-00' ||
            $value === '0000-00-00 00:00:00'
        ) {
            return null;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | PASSWORD SQL
    |--------------------------------------------------------------------------
    |
    | Password dari Sadarin langsung dipindahkan.
    | TIDAK di-Hash::make() lagi.
    |
    */

    private function sqlPassword($value)
    {
        $value = $this->sqlClean($value);

        if ($value === null) {
            return null;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | EXCEL VALUE
    |--------------------------------------------------------------------------
    */

    private function excelValue(
        array $row,
        array $columnMap,
        array $aliases
    ) {
        foreach ($aliases as $alias) {
            if (
                array_key_exists(
                    $alias,
                    $columnMap
                )
            ) {
                return $row[$columnMap[$alias]] ?? null;
            }
        }

        return null;
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

        if ($value === '') {
            return null;
        }

        if ($value === '-') {
            return null;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | INTEGER
    |--------------------------------------------------------------------------
    */

    private function integerOrNull($value)
    {
        if (
            $value === null ||
            $value === ''
        ) {
            return null;
        }

        return (int) $value;
    }
}