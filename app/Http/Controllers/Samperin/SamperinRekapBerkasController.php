<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinApi;
use App\Models\SamperinJenisKerja;
use App\Models\SamperinPermintaanBerkas;
use App\Models\SamperinPengumpulanBerkas;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SamperinRekapBerkasController extends Controller
{
    /**
     * ============================================================
     * REKAP PEMBERKASAN
     * ============================================================
     *
     * Filter:
     * - jenis_kerja
     * - status
     * - search
     *
     * Pagination:
     * - 30 data per halaman
     *
     * ALUR:
     * 1. Ambil target permintaan
     * 2. Ambil seluruh pegawai target
     * 3. Cocokkan dengan berkas
     * 4. Buat rekap per jenis kerja
     * 5. Terapkan filter tab/search/status
     * 6. Pagination
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | PARAMETER
        |--------------------------------------------------------------------------
        */

        $permintaanUid = trim((string) $request->input('permintaanUid'));

        $search = trim((string) $request->input('search'));

        $status = strtolower(trim((string) $request->input('status', 'all')));

        $jenisKerjaFilter = $request->input('jenis_kerja');

        /*
        |--------------------------------------------------------------------------
        | VALIDASI STATUS
        |--------------------------------------------------------------------------
        */

        if (!in_array($status, ['all', 'sudah', 'belum'], true)) {
            $status = 'all';
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI JENIS KERJA
        |--------------------------------------------------------------------------
        */

        if ($jenisKerjaFilter === null || $jenisKerjaFilter === '' || !is_numeric($jenisKerjaFilter)) {
            $jenisKerjaFilter = null;
        } else {
            $jenisKerjaFilter = (int) $jenisKerjaFilter;
        }

        /*
        |--------------------------------------------------------------------------
        | BELUM MEMILIH PERMINTAAN
        |--------------------------------------------------------------------------
        */

        if ($permintaanUid === '') {
            return view('dashboard.admin.rekap-berkas.index', [
                'permintaan' => null,
                'jenisKerjaList' => collect(),
                'perJenisKerja' => collect(),
                'rekap' => collect(),
                'totalPegawai' => 0,
                'totalSudah' => 0,
                'totalBelum' => 0,
                'persentase' => 0,
                'permintaanUid' => null,
                'search' => $search,
                'status' => $status,
                'jenisKerjaFilter' => $jenisKerjaFilter,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL PERMINTAAN
        |--------------------------------------------------------------------------
        */

        $permintaan = SamperinPermintaanBerkas::query()
            ->with(['jenisBerkas.kategori', 'target.jenisKerja', 'target.folder'])
            ->where('permintaan_uid', $permintaanUid)
            ->where('permintaan_status', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | TARGET PERMINTAAN
        |--------------------------------------------------------------------------
        |
        | Struktur target sekarang:
        |
        | target_tipe
        | target_jenis_kerja_id
        | target_folder_id
        |
        */

        $targets = $permintaan
            ->target()
            ->with(['jenisKerja', 'folder'])
            ->where('target_status', true)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TARGET JENIS KERJA
        |--------------------------------------------------------------------------
        */

        $targetJenisKerjaIds = $targets
            ->filter(function ($target) {
                return strtoupper(trim((string) $target->target_tipe)) === 'JENIS_KERJA';
            })
            ->pluck('target_jenis_kerja_id')
            ->filter()
            ->map(function ($id) {
                return (int) $id;
            })
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | CEK TARGET SEMUA
        |--------------------------------------------------------------------------
        */

        $targetSemua = $targets->contains(function ($target) {
            return strtoupper(trim((string) $target->target_tipe)) === 'SEMUA';
        });

        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR PEGAWAI
        |--------------------------------------------------------------------------
        |
        | INI PENTING:
        |
        | Query ini TIDAK BOLEH diberi filter tab,
        | search, ataupun status.
        |
        | Ini adalah DATA MASTER REKAP.
        |
        */

        $pegawaiQuery = SamperinUser::query()
            ->with(['jenisKerja','foto'])
            ->where('user_status', 1);

        /*
        |--------------------------------------------------------------------------
        | TERAPKAN TARGET PERMINTAAN
        |--------------------------------------------------------------------------
        */

        if ($targetSemua) {
            // Semua pegawai aktif menjadi target.
        } elseif ($targetJenisKerjaIds->isNotEmpty()) {
            $pegawaiQuery->whereIn('user_jenis_kerja_id', $targetJenisKerjaIds);
        } else {
            /*
            |--------------------------------------------------------------------------
            | PERMINTAAN TIDAK MEMILIKI TARGET VALID
            |--------------------------------------------------------------------------
            */

            $pegawaiQuery->whereRaw('1 = 0');
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL SEMUA PEGAWAI TARGET
        |--------------------------------------------------------------------------
        */

        $pegawai = $pegawaiQuery->orderBy('user_nama')->get(['user_id', 'user_uid', 'user_nip', 'user_nik', 'user_nama', 'user_jenis_kerja_id', 'user_status']);

        /*
        |--------------------------------------------------------------------------
        | AMBIL BERKAS PERMINTAAN
        |--------------------------------------------------------------------------
        */

        $pengumpulan = SamperinPengumpulanBerkas::query()
            ->where('pengumpulan_berkas_permintaan_id', $permintaan->permintaan_id)
            ->get(['pengumpulan_berkas_id', 'pengumpulan_berkas_uid', 'pengumpulan_berkas_user_uid', 'pengumpulan_berkas_permintaan_id', 'pengumpulan_berkas_file', 'pengumpulan_berkas_nama', 'pengumpulan_berkas_mime', 'pengumpulan_berkas_size', 'pengumpulan_berkas_tanggal', 'pengumpulan_berkas_status', 'pengumpulan_berkas_verified_at', 'pengumpulan_berkas_verified_by', 'pengumpulan_berkas_keterangan', 'pengumpulan_berkas_sumber', 'pengumpulan_berkas_sumber_id'])
            ->keyBy('pengumpulan_berkas_user_uid');

        /*
        |--------------------------------------------------------------------------
        | BENTUK REKAP DASAR
        |--------------------------------------------------------------------------
        */

        $rekapSemua = $pegawai->map(function ($user) use ($pengumpulan) {
            $berkas = $pengumpulan->get($user->user_uid);

            return [
                'user' => $user,

                'berkas' => $berkas,

                'sudah' => $berkas !== null,

                'belum' => $berkas === null,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | TOTAL GLOBAL
        |--------------------------------------------------------------------------
        */

        $totalPegawai = $rekapSemua->count();

        $totalSudah = $rekapSemua->where('sudah', true)->count();

        $totalBelum = $totalPegawai - $totalSudah;

        /*
        |--------------------------------------------------------------------------
        | PERSENTASE GLOBAL
        |--------------------------------------------------------------------------
        */

        $persentase = $totalPegawai > 0 ? round(($totalSudah / $totalPegawai) * 100, 1) : 0;

        /*
        |--------------------------------------------------------------------------
        | REKAP PER JENIS KERJA
        |--------------------------------------------------------------------------
        |
        | SELALU menggunakan $rekapSemua.
        |
        | Tidak terpengaruh:
        | - tab
        | - search
        | - status
        |
        */

        $perJenisKerja = $rekapSemua
            ->groupBy(function ($item) {
                return $item['user']->user_jenis_kerja_id ?? 0;
            })
            ->map(function ($users) {
                $firstUser = $users->first()['user'];

                $jenisKerja = $firstUser->jenisKerja;

                $total = $users->count();

                $sudah = $users->where('sudah', true)->count();

                $belum = $total - $sudah;

                return [
                    'id' => $jenisKerja ? $jenisKerja->jenis_kerja_id : null,

                    'nama' => $jenisKerja ? $jenisKerja->jenis_kerja_nama : 'Tidak Diketahui',

                    'total' => $total,

                    'sudah' => $sudah,

                    'belum' => $belum,

                    'persentase' => $total > 0 ? round(($sudah / $total) * 100, 1) : 0,

                    'pegawai' => $users->values(),
                ];
            })
            ->sortBy('nama')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK TABEL
        |--------------------------------------------------------------------------
        |
        | Mulai dari seluruh target.
        |
        */

        $rekapFiltered = $rekapSemua;

        /*
        |--------------------------------------------------------------------------
        | FILTER TAB JENIS KERJA
        |--------------------------------------------------------------------------
        */

        if ($jenisKerjaFilter !== null) {
            $rekapFiltered = $rekapFiltered
                ->filter(function ($item) use ($jenisKerjaFilter) {
                    return (int) $item['user']->user_jenis_kerja_id === (int) $jenisKerjaFilter;
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        |
        | Nama / NIP / NIK
        |
        */

        if ($search !== '') {
            $searchLower = strtolower($search);

            $rekapFiltered = $rekapFiltered
                ->filter(function ($item) use ($searchLower) {
                    $user = $item['user'];

                    $nama = strtolower((string) $user->user_nama);

                    $nip = strtolower((string) $user->user_nip);

                    $nik = strtolower((string) $user->user_nik);

                    return str_contains($nama, $searchLower) || str_contains($nip, $searchLower) || str_contains($nik, $searchLower);
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($status === 'sudah') {
            $rekapFiltered = $rekapFiltered->where('sudah', true)->values();
        } elseif ($status === 'belum') {
            $rekapFiltered = $rekapFiltered->where('belum', true)->values();
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        |
        | Filter SUDAH SELESAI.
        |
        | Baru sekarang pagination.
        |
        */

        $perPage = 15;

        $currentPage = max(1, (int) $request->input('page', 1));

        $totalFiltered = $rekapFiltered->count();

        /*
        |--------------------------------------------------------------------------
        | JIKA HALAMAN TERLALU BESAR
        |--------------------------------------------------------------------------
        |
        | Misalnya user sedang di halaman 5,
        | lalu melakukan search sehingga hanya
        | ada 1 halaman.
        |
        */

        $lastPage = max(1, (int) ceil($totalFiltered / $perPage));

        if ($currentPage > $lastPage) {
            $currentPage = $lastPage;
        }

        /*
        |--------------------------------------------------------------------------
        | SLICE 30 DATA
        |--------------------------------------------------------------------------
        */

        $rekap = $rekapFiltered->slice(($currentPage - 1) * $perPage, $perPage)->values();

        /*
        |--------------------------------------------------------------------------
        | PAGINATOR
        |--------------------------------------------------------------------------
        */

        $rekapPagination = new LengthAwarePaginator($rekap, $totalFiltered, $perPage, $currentPage, [
            'path' => $request->url(),

            'query' => $request->query(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | DAFTAR JENIS KERJA
        |--------------------------------------------------------------------------
        */

        $jenisKerjaList = SamperinJenisKerja::query()->where('jenis_kerja_status', 1)->orderBy('jenis_kerja_id', 'asc')->get();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('dashboard.admin.rekap-berkas.index', [
            'permintaan' => $permintaan,

            'rekap' => $rekapPagination,

            'perJenisKerja' => $perJenisKerja,

            'jenisKerjaList' => $jenisKerjaList,

            'totalPegawai' => $totalPegawai,

            'totalSudah' => $totalSudah,

            'totalBelum' => $totalBelum,

            'persentase' => $persentase,

            'permintaanUid' => $permintaanUid,

            'search' => $search,

            'status' => $status,

            'jenisKerjaFilter' => $jenisKerjaFilter,
        ]);
    }

    /**
     * ============================================================
     * UPLOAD BERKAS BARU
     * ============================================================
     */

    /**
     * ============================================================
     * SINKRONISASI BERKAS DARI ARINDRIVE
     * ============================================================
     */
    public function sync(string $permintaanUid)
    {
        set_time_limit(0);

        /*
    |--------------------------------------------------------------------------
    | PERMINTAAN
    |--------------------------------------------------------------------------
    */

        $permintaan = SamperinPermintaanBerkas::query()
            ->with([
                'jenisBerkas',
                'target.folder',
                'target.jenisKerja',
            ])
            ->where('permintaan_uid', $permintaanUid)
            ->where('permintaan_status', true)
            ->firstOrFail();

        /*
    |--------------------------------------------------------------------------
    | TARGET AKTIF
    |--------------------------------------------------------------------------
    */

        $targets = $permintaan->target()
            ->with(['folder', 'jenisKerja'])
            ->where('target_status', true)
            ->get();

        if ($targets->isEmpty()) {
            return back()->with(
                'error',
                'Permintaan ini belum memiliki target pegawai.'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | PEGAWAI TARGET
    |--------------------------------------------------------------------------
    */

        $targetJenisKerjaIds = $targets
            ->filter(function ($target) {
                return strtoupper(trim((string) $target->target_tipe)) === 'JENIS_KERJA';
            })
            ->pluck('target_jenis_kerja_id')
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $targetSemua = $targets->contains(function ($target) {
            return strtoupper(trim((string) $target->target_tipe)) === 'SEMUA';
        });

        $pegawaiQuery = SamperinUser::query()
            ->where('user_status', 1);

        if ($targetSemua) {

            // Semua pegawai aktif menjadi target.

        } elseif ($targetJenisKerjaIds->isNotEmpty()) {

            $pegawaiQuery->whereIn(
                'user_jenis_kerja_id',
                $targetJenisKerjaIds
            );
        } else {

            return back()->with(
                'error',
                'Tidak ditemukan target jenis kerja yang valid.'
            );
        }

        $pegawai = $pegawaiQuery
            ->orderBy('user_nama')
            ->get([
                'user_id',
                'user_uid',
                'user_nip',
                'user_nik',
                'user_nama',
                'user_jenis_kerja_id',
                'user_status',
            ]);

        /*
    |--------------------------------------------------------------------------
    | BERKAS YANG SUDAH ADA DI DATABASE
    |--------------------------------------------------------------------------
    |
    | Record yang sudah ada TIDAK disentuh.
    |
    */

        $existing = SamperinPengumpulanBerkas::query()
            ->where(
                'pengumpulan_berkas_permintaan_id',
                $permintaan->permintaan_id
            )
            ->get([
                'pengumpulan_berkas_user_uid',
            ])
            ->pluck('pengumpulan_berkas_user_uid')
            ->flip();

        /*
    |--------------------------------------------------------------------------
    | HANYA PEGAWAI YANG BELUM ADA RECORD
    |--------------------------------------------------------------------------
    */

        $belumAda = $pegawai->filter(function ($user) use ($existing) {
            return !$existing->has($user->user_uid);
        })->values();

        if ($belumAda->isEmpty()) {
            return back()->with(
                'success',
                'Tidak ada berkas yang perlu disinkronkan. Semua pegawai target sudah memiliki record.'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | API ARINDRIVE
    |--------------------------------------------------------------------------
    */

        $api = SamperinApi::query()
            ->where('api_kode', 'ARINDRIVE')
            ->where('api_status', true)
            ->first();

        if (!$api) {
            return back()->with(
                'error',
                'API ArinDrive belum dikonfigurasi.'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | KELOMPOKKAN PEGAWAI BERDASARKAN FOLDER
    |--------------------------------------------------------------------------
    */

        $grouped = collect();

        foreach ($belumAda as $user) {

            /*
        |--------------------------------------------------------------------------
        | CARI TARGET
        |--------------------------------------------------------------------------
        */

            $target = null;

            // Prioritas 1:
            // target khusus jenis kerja pegawai.
            if ($user->user_jenis_kerja_id) {

                $target = $targets->first(function ($item) use ($user) {

                    return strtoupper(trim((string) $item->target_tipe)) === 'JENIS_KERJA'
                        && (int) $item->target_jenis_kerja_id === (int) $user->user_jenis_kerja_id;
                });
            }

            // Prioritas 2:
            // target SEMUA.
            if (!$target && $targetSemua) {

                $target = $targets->first(function ($item) {

                    return strtoupper(trim((string) $item->target_tipe)) === 'SEMUA';
                });
            }

            if (!$target || !$target->folder) {
                continue;
            }

            $folderId = trim((string) $target->folder->folder_drive_id);

            if ($folderId === '') {
                continue;
            }

            if (!$grouped->has($folderId)) {
                $grouped->put($folderId, collect());
            }

            $grouped->get($folderId)->push([
                'user' => $user,
                'target' => $target,
                'folder' => $target->folder,
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | COUNTER
    |--------------------------------------------------------------------------
    */

        $added = 0;
        $notFound = 0;
        $noNip = 0;
        $noFolder = 0;
        $apiError = 0;

        /*
    |--------------------------------------------------------------------------
    | PROSES PER FOLDER
    |--------------------------------------------------------------------------
    */

        foreach ($grouped as $folderId => $items) {

            /*
        |--------------------------------------------------------------------------
        | REQUEST KE ARINDRIVE
        |--------------------------------------------------------------------------
        */

            try {

                $response = Http::withToken($api->api_token)
                    ->acceptJson()
                    ->timeout(120)
                    ->post(
                        rtrim($api->api_url, '/') . '/api/list-drive-files',
                        [
                            'folder_id' => $folderId,
                        ]
                    );

                if (!$response->successful()) {
                    $apiError++;
                    continue;
                }

                $result = $response->json();

                if (!data_get($result, 'success')) {
                    $apiError++;
                    continue;
                }

                $driveFiles = collect(
                    data_get($result, 'data.files', [])
                );
            } catch (\Throwable $e) {

                $apiError++;
                continue;
            }

            /*
        |--------------------------------------------------------------------------
        | INDEX FILE BERDASARKAN NIP
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | 1987654321_nama_berkas.pdf
        |
        */

            $filesByNip = [];

            foreach ($driveFiles as $driveFile) {

                $filename = trim(
                    (string) data_get($driveFile, 'name')
                );

                if ($filename === '') {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | NIP HARUS DI AWAL NAMA FILE
            |--------------------------------------------------------------------------
            */

                if (!preg_match('/^([0-9]+)_/', $filename, $matches)) {
                    continue;
                }

                $nip = trim($matches[1]);

                if ($nip === '') {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | JIKA ADA LEBIH DARI SATU FILE,
            | AMBIL YANG TERBARU
            |--------------------------------------------------------------------------
            */

                $modifiedTime = data_get(
                    $driveFile,
                    'modified_time'
                );

                if (
                    !isset($filesByNip[$nip])
                    || strtotime((string) $modifiedTime)
                    > strtotime(
                        (string) data_get(
                            $filesByNip[$nip],
                            'modified_time'
                        )
                    )
                ) {
                    $filesByNip[$nip] = $driveFile;
                }
            }

            /*
        |--------------------------------------------------------------------------
        | COCOKKAN PEGAWAI
        |--------------------------------------------------------------------------
        */

            foreach ($items as $item) {

                $user = $item['user'];

                $nip = trim((string) $user->user_nip);

                /*
            |--------------------------------------------------------------------------
            | TANPA NIP
            |--------------------------------------------------------------------------
            |
            | Sesuai aturan sync:
            | pencocokan dilakukan berdasarkan NIP.
            |
            */

                if ($nip === '') {
                    $noNip++;
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | CARI FILE
            |--------------------------------------------------------------------------
            */

                $driveFile = $filesByNip[$nip] ?? null;

                if (!$driveFile) {
                    $notFound++;
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | CEK DB LAGI
            |--------------------------------------------------------------------------
            |
            | Pengaman tambahan jika ada proses lain berjalan
            | bersamaan dengan sync.
            |
            */

                $alreadyExists = SamperinPengumpulanBerkas::query()
                    ->where(
                        'pengumpulan_berkas_user_uid',
                        $user->user_uid
                    )
                    ->where(
                        'pengumpulan_berkas_permintaan_id',
                        $permintaan->permintaan_id
                    )
                    ->exists();

                if ($alreadyExists) {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | DATA FILE
            |--------------------------------------------------------------------------
            */

                $googleFileId = data_get(
                    $driveFile,
                    'google_file_id'
                );

                $filename = data_get(
                    $driveFile,
                    'name'
                ) ?: 'Berkas';

                $mime = data_get(
                    $driveFile,
                    'mime_type'
                );

                $size = data_get(
                    $driveFile,
                    'size'
                );

                $url = data_get(
                    $driveFile,
                    'web_view_link'
                );

                /*
            |--------------------------------------------------------------------------
            | FALLBACK URL
            |--------------------------------------------------------------------------
            */

                if (!$url && $googleFileId) {
                    $url = 'https://drive.google.com/file/d/'
                        . $googleFileId
                        . '/view';
                }

                /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */

                $tanggal = data_get(
                    $driveFile,
                    'modified_time'
                );

                try {
                    $tanggal = $tanggal
                        ? \Carbon\Carbon::parse($tanggal)
                        : now();
                } catch (\Throwable $e) {
                    $tanggal = now();
                }

                /*
            |--------------------------------------------------------------------------
            | INSERT
            |--------------------------------------------------------------------------
            */

                SamperinPengumpulanBerkas::create([
                    'pengumpulan_berkas_uid' => (string) Str::uuid(),

                    'pengumpulan_berkas_user_uid' => $user->user_uid,

                    'pengumpulan_berkas_permintaan_id' =>
                    $permintaan->permintaan_id,

                    'pengumpulan_berkas_file' => $url,

                    'pengumpulan_berkas_nama' => $filename,

                    'pengumpulan_berkas_mime' => $mime,

                    'pengumpulan_berkas_size' => $size,

                    'pengumpulan_berkas_tanggal' => $tanggal,

                    'pengumpulan_berkas_status' => 'terkirim',

                    'pengumpulan_berkas_keterangan' =>
                    'Berkas disinkronkan dari ArinDrive.',

                    'pengumpulan_berkas_sumber' => 'ARINDRIVE',

                    'pengumpulan_berkas_sumber_id' => $googleFileId,

                    'pengumpulan_berkas_created_at' => now(),

                    'pengumpulan_berkas_updated_at' => now(),
                ]);

                $added++;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | HASIL
    |--------------------------------------------------------------------------
    */

        $message = "Sinkronisasi selesai. "
            . "Berhasil: {$added}.";

        if ($notFound > 0) {
            $message .= " File tidak ditemukan: {$notFound}.";
        }

        if ($noNip > 0) {
            $message .= " Tanpa NIP: {$noNip}.";
        }

        if ($apiError > 0) {
            $message .= " Folder gagal dibaca: {$apiError}.";
        }

        return redirect()
            ->route('admin.rekap.berkas.index', [
                'permintaanUid' => $permintaan->permintaan_uid,
            ])
            ->with(
                $added > 0 ? 'success' : 'error',
                $message
            );
    }
    public function store(Request $request, string $permintaanUid, string $userUid)
    {
        set_time_limit(0);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'file' => ['required', 'file', 'max:51200'],
            ],
            [
                'file.required' => 'File wajib dipilih.',

                'file.file' => 'File tidak valid.',

                'file.max' => 'Ukuran file maksimal 50 MB.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = SamperinUser::query()->where('user_uid', $userUid)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | PERMINTAAN
        |--------------------------------------------------------------------------
        */

        $permintaan = SamperinPermintaanBerkas::query()
            ->with(['jenisBerkas', 'target.folder'])
            ->where('permintaan_uid', $permintaanUid)
            ->where('permintaan_status', true)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CARI TARGET SESUAI JENIS KERJA
        |--------------------------------------------------------------------------
        */

        $target = $permintaan->target()->with('folder')->where('target_status', true)->where('target_tipe', 'JENIS_KERJA')->where('target_jenis_kerja_id', $user->user_jenis_kerja_id)->first();

        if (!$target) {
            return back()->with('error', 'Pegawai tersebut tidak termasuk target permintaan berkas ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | FOLDER
        |--------------------------------------------------------------------------
        */

        $folder = $target->folder;

        if (!$folder) {
            return back()->with('error', 'Folder ArinDrive untuk jenis kerja pegawai belum terhubung pada permintaan ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | CEK BERKAS LAMA
        |--------------------------------------------------------------------------
        */

        $existing = SamperinPengumpulanBerkas::query()->where('pengumpulan_berkas_user_uid', $user->user_uid)->where('pengumpulan_berkas_permintaan_id', $permintaan->permintaan_id)->first();

        if ($existing) {
            return back()->with('error', 'Pegawai tersebut sudah memiliki berkas. Gunakan Edit/Ganti Berkas.');
        }

        /*
        |--------------------------------------------------------------------------
        | UPLOAD
        |--------------------------------------------------------------------------
        */

        $upload = $this->uploadToDrive($request->file('file'), $user, $permintaan, $folder);

        /*
        |--------------------------------------------------------------------------
        | INSERT DATABASE
        |--------------------------------------------------------------------------
        */

        SamperinPengumpulanBerkas::create([
            'pengumpulan_berkas_uid' => (string) Str::uuid(),

            'pengumpulan_berkas_user_uid' => $user->user_uid,

            'pengumpulan_berkas_permintaan_id' => $permintaan->permintaan_id,

            'pengumpulan_berkas_file' => $upload['url'],

            'pengumpulan_berkas_nama' => $request->file('file')->getClientOriginalName(),

            'pengumpulan_berkas_mime' => $request->file('file')->getMimeType(),

            'pengumpulan_berkas_size' => $request->file('file')->getSize(),

            'pengumpulan_berkas_tanggal' => now(),

            'pengumpulan_berkas_status' => 'terkirim',

            'pengumpulan_berkas_keterangan' => 'Upload dibantu administrator.',

            'pengumpulan_berkas_sumber' => 'ADMIN',

            'pengumpulan_berkas_sumber_id' => null,

            'pengumpulan_berkas_created_at' => now(),

            'pengumpulan_berkas_updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | KEMBALI
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('rekap.berkas', [
                'permintaanUid' => $permintaan->permintaan_uid,
            ])
            ->with('success', 'Berkas berhasil diupload untuk ' . $user->user_nama . '.');
    }

    /**
     * ============================================================
     * UPDATE / GANTI BERKAS
     * ============================================================
     */
    public function update(Request $request, string $uid)
    {
        set_time_limit(0);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'file' => ['required', 'file', 'max:51200'],
            ],
            [
                'file.required' => 'File wajib dipilih.',

                'file.file' => 'File tidak valid.',

                'file.max' => 'Ukuran file maksimal 50 MB.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | BERKAS
        |--------------------------------------------------------------------------
        */

        $berkas = SamperinPengumpulanBerkas::query()
            ->with(['user', 'permintaan.jenisBerkas', 'permintaan.target.folder'])
            ->where('pengumpulan_berkas_uid', $uid)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = $berkas->user;

        /*
        |--------------------------------------------------------------------------
        | TARGET
        |--------------------------------------------------------------------------
        */

        $target = $berkas->permintaan->target()->with('folder')->where('target_status', true)->where('target_tipe', 'JENIS_KERJA')->where('target_jenis_kerja_id', $user->user_jenis_kerja_id)->first();

        if (!$target || !$target->folder) {
            return back()->with('error', 'Folder ArinDrive untuk jenis kerja pegawai belum terhubung pada permintaan ini.');
        }

        /*
        |--------------------------------------------------------------------------
        | UPLOAD BARU
        |--------------------------------------------------------------------------
        */

        $upload = $this->uploadToDrive($request->file('file'), $user, $berkas->permintaan, $target->folder);

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $berkas->update([
            'pengumpulan_berkas_file' => $upload['url'],

            'pengumpulan_berkas_nama' => $request->file('file')->getClientOriginalName(),

            'pengumpulan_berkas_mime' => $request->file('file')->getMimeType(),

            'pengumpulan_berkas_size' => $request->file('file')->getSize(),

            'pengumpulan_berkas_tanggal' => now(),

            'pengumpulan_berkas_status' => 'terkirim',

            'pengumpulan_berkas_keterangan' => 'Berkas diperbarui oleh administrator.',

            'pengumpulan_berkas_updated_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | KEMBALI
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('rekap.berkas', [
                'permintaanUid' => $berkas->permintaan->permintaan_uid,
            ])
            ->with('success', 'Berkas berhasil diperbarui untuk ' . $berkas->user?->user_nama . '.');
    }

    /**
     * ============================================================
     * UPLOAD KE ARINDRIVE
     * ============================================================
     */
    private function uploadToDrive($file, SamperinUser $user, SamperinPermintaanBerkas $permintaan, $folder): array
    {
        /*
        |--------------------------------------------------------------------------
        | API
        |--------------------------------------------------------------------------
        */

        $api = SamperinApi::query()->where('api_kode', 'ARINDRIVE')->where('api_status', true)->first();

        if (!$api) {
            throw new \Exception('API ArinDrive belum dikonfigurasi.');
        }

        /*
        |--------------------------------------------------------------------------
        | FOLDER
        |--------------------------------------------------------------------------
        */

        if (!$folder || !$folder->folder_drive_id) {
            throw new \Exception('Folder ArinDrive untuk permintaan ini belum memiliki Drive Folder ID.');
        }

        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $extension = $file->getClientOriginalExtension();

        $filename = ($user->user_nip ?: $user->user_uid) . '_' . Str::slug($permintaan->permintaan_judul) . '_' . now()->format('YmdHis') . '.' . $extension;

        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

        $fileContent = file_get_contents($file->getRealPath());

        /*
        |--------------------------------------------------------------------------
        | UPLOAD
        |--------------------------------------------------------------------------
        */

        $response = Http::withToken($api->api_token)
            ->timeout(120)
            ->attach('file', $fileContent, $filename)
            ->post(rtrim($api->api_url, '/') . '/api/upload-drive', [
                'folder_id' => $folder->folder_drive_id,

                'filename' => $filename,

                'source_app' => 'samperin',

                'folder' => $folder->folder_prefix ?: 'berkas-pegawai',

                'reference_id' => $user->user_uid . '-berkas-' . $permintaan->permintaan_uid,
            ]);

        /*
        |--------------------------------------------------------------------------
        | RESPONSE GAGAL
        |--------------------------------------------------------------------------
        */

        if (!$response->successful()) {
            throw new \Exception('Upload ke ArinDrive gagal: ' . $response->body());
        }

        /*
        |--------------------------------------------------------------------------
        | JSON
        |--------------------------------------------------------------------------
        */

        $result = $response->json();

        /*
        |--------------------------------------------------------------------------
        | URL
        |--------------------------------------------------------------------------
        */

        $url = data_get($result, 'url') ?? (data_get($result, 'file_url') ?? (data_get($result, 'data.url') ?? (data_get($result, 'data.file_url') ?? data_get($result, 'data.web_view_link'))));

        if (!$url) {
            throw new \Exception('Upload berhasil tetapi URL file dari ArinDrive tidak ditemukan.');
        }

        return [
            'url' => $url,

            'response' => $result,
        ];
    }
}