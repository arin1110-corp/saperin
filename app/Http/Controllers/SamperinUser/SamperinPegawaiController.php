<?php

namespace App\Http\Controllers\SamperinUser;

use App\Http\Controllers\Controller;
use App\Models\SamperinBerkasKategori;
use App\Models\SamperinJenisBerkas;
use App\Models\SamperinPengumpulanBerkas;
use App\Models\SamperinPermintaanBerkas;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SamperinPegawaiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN UTAMA PEGAWAI
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = $this->getLoginUser();

        $permintaanAktif = SamperinPermintaanBerkas::query()
            ->with([
                'jenisBerkas.kategori',
                'target' => function ($query) use ($user) {
                    $query
                        ->where('target_jenis_kerja_id', $user->user_jenis_kerja_id)
                        ->where('target_status', true)
                        ->with('folder');
                },
            ])
            ->where('permintaan_status', true)
            ->where(function ($query) {
                $query
                    ->whereNull('permintaan_mulai')
                    ->orWhere('permintaan_mulai', '<=', now());
            })
            ->where(function ($query) {
                $query
                    ->whereNull('permintaan_expired')
                    ->orWhere('permintaan_expired', '>=', now());
            })
            ->get()
            ->filter(function ($permintaan) {
                return $permintaan->target->isNotEmpty();
            })
            ->values();

        return view(
            'pegawai.index',
            compact(
                'user',
                'permintaanAktif'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */

    public function profil()
    {
        $user = $this->getLoginUser();

        return view(
            'pegawai.profil',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BERKAS PEGAWAI
    |--------------------------------------------------------------------------
    |
    | DATA BERASAL DARI PERMINTAAN BERKAS.
    |
    | Contoh:
    |
    | Evaluasi Kinerja
    | Tahun      : 2026
    | Periode    : TW II
    |
    | Akan tampil:
    |
    | Evaluasi Kinerja 2026 Triwulan II
    |
    | Status dikaitkan dengan:
    | samperin_pengumpulan_berkas
    |
    */

    public function berkas(Request $request)
    {
        $user = $this->getLoginUser();

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $kategoriUid = trim(
            (string) $request->input('kategori')
        );

        $search = trim(
            (string) $request->input('search')
        );

        $statusFilter = trim(
            (string) $request->input('status')
        );


        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

        $kategoriList = SamperinBerkasKategori::query()
            ->where('kategori_status', true)
            ->orderBy('kategori_nama')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SEMUA PERMINTAAN
        |--------------------------------------------------------------------------
        |
        | Jangan dibatasi tanggal expired.
        |
        | Karena berkas yang sudah pernah dikumpulkan tetap harus
        | bisa dilihat oleh pegawai.
        |
        */

        $permintaanList = SamperinPermintaanBerkas::query()
            ->with([
                'jenisBerkas.kategori',
                'target' => function ($query) use ($user) {
                    $query
                        ->where('target_status', true)
                        ->where(
                            'target_jenis_kerja_id',
                            $user->user_jenis_kerja_id
                        )
                        ->with('folder');
                },
            ])
            ->where('permintaan_status', true)
            ->orderByDesc('permintaan_tahun')
            ->orderByDesc('permintaan_id')
            ->get()
            ->filter(function ($permintaan) {
                return $permintaan->target->isNotEmpty();
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PENGUMPULAN MILIK PEGAWAI LOGIN
        |--------------------------------------------------------------------------
        */

        $pengumpulanByPermintaan = SamperinPengumpulanBerkas::query()
            ->where(
                'pengumpulan_berkas_user_uid',
                $user->user_uid
            )
            ->orderByDesc('pengumpulan_berkas_id')
            ->get()
            ->groupBy(
                'pengumpulan_berkas_permintaan_id'
            )
            ->map(function ($items) {
                return $items->first();
            });


        /*
        |--------------------------------------------------------------------------
        | BENTUK DATA UNTUK VIEW
        |--------------------------------------------------------------------------
        */

        $berkasRows = $permintaanList
            ->map(function ($permintaan) use ($pengumpulanByPermintaan) {

                $jenis = $permintaan->jenisBerkas;

                $kategori = $jenis?->kategori;

                $pengumpulan = $pengumpulanByPermintaan->get(
                    $permintaan->permintaan_id
                );


                /*
                |--------------------------------------------------------------------------
                | JUDUL PERMINTAAN
                |--------------------------------------------------------------------------
                */

                $judul = trim(
                    (string) $permintaan->permintaan_judul
                );

                if ($judul === '') {
                    $judul = trim(
                        (string) ($jenis?->jenis_berkas_nama ?? 'Berkas')
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | TAHUN
                |--------------------------------------------------------------------------
                */

                $tahun = $permintaan->permintaan_tahun;

                if (
                    $tahun &&
                    !str_contains(
                        strtolower($judul),
                        (string) $tahun
                    )
                ) {
                    $judul .= ' ' . $tahun;
                }


                /*
                |--------------------------------------------------------------------------
                | PERIODE
                |--------------------------------------------------------------------------
                */

                $periode = trim(
                    (string) $permintaan->permintaan_periode
                );

                if ($periode !== '') {

                    $periodeLabel = $this->formatPeriode(
                        $periode
                    );

                    if (
                        !str_contains(
                            strtolower($judul),
                            strtolower($periodeLabel)
                        )
                    ) {
                        $judul .= ' ' . $periodeLabel;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                $sudah = $pengumpulan !== null;


                return [
                    'permintaan' => $permintaan,

                    'permintaan_uid' =>
                        $permintaan->permintaan_uid,

                    'permintaan_id' =>
                        $permintaan->permintaan_id,

                    'judul' =>
                        $judul,

                    'kategori' =>
                        $kategori,

                    'kategori_uid' =>
                        $kategori?->kategori_uid,

                    'kategori_nama' =>
                        $kategori?->kategori_nama ?? 'Lainnya',

                    'jenis_nama' =>
                        $jenis?->jenis_berkas_nama ?? null,

                    'sudah' =>
                        $sudah,

                    'status' =>
                        $sudah
                            ? 'sudah'
                            : 'belum',

                    'pengumpulan' =>
                        $pengumpulan,

                    'tanggal_upload' =>
                        $pengumpulan?->pengumpulan_berkas_tanggal,

                    'file_url' =>
                        $pengumpulan?->pengumpulan_berkas_file,

                    'file_nama' =>
                        $pengumpulan?->pengumpulan_berkas_nama,
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | JUMLAH PER KATEGORI
        |--------------------------------------------------------------------------
        |
        | PENTING:
        | Penghitungan dilakukan SEBELUM filter kategori.
        |
        | Jadi ketika klik Pendidikan:
        | tab Identitas, Kepegawaian, dll tetap tampil.
        |
        */

        $kategoriCounts = $berkasRows
            ->groupBy(function ($item) {
                return strtolower(
                    (string) $item['kategori_uid']
                );
            })
            ->map(function ($items) {
                return $items->count();
            });


        $totalSemua = $berkasRows->count();


        /*
        |--------------------------------------------------------------------------
        | FILTER SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $searchLower = strtolower($search);

            $berkasRows = $berkasRows
                ->filter(function ($item) use ($searchLower) {

                    return
                        str_contains(
                            strtolower($item['judul']),
                            $searchLower
                        )
                        ||
                        str_contains(
                            strtolower($item['kategori_nama']),
                            $searchLower
                        )
                        ||
                        str_contains(
                            strtolower((string) $item['jenis_nama']),
                            $searchLower
                        );
                })
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER KATEGORI
        |--------------------------------------------------------------------------
        */

        if ($kategoriUid !== '') {

            $kategoriLower = strtolower(
                $kategoriUid
            );

            $berkasRows = $berkasRows
                ->filter(function ($item) use ($kategoriLower) {

                    return strtolower(
                        (string) $item['kategori_uid']
                    ) === $kategoriLower;
                })
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($statusFilter === 'sudah') {

            $berkasRows = $berkasRows
                ->filter(function ($item) {
                    return $item['sudah'] === true;
                })
                ->values();
        }

        if ($statusFilter === 'belum') {

            $berkasRows = $berkasRows
                ->filter(function ($item) {
                    return $item['sudah'] === false;
                })
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        |
        | 5 DATA PER HALAMAN
        |
        */

        $perPage = 5;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $totalFiltered = $berkasRows->count();

        $currentItems = $berkasRows
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
            ->values();

        $berkasList = new LengthAwarePaginator(
            $currentItems,
            $totalFiltered,
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalSudah = $berkasRows
            ->where('sudah', true)
            ->count();

        $totalBelum = $berkasRows
            ->where('sudah', false)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return view(
            'pegawai.berkas',
            compact(
                'user',
                'kategoriList',
                'kategoriCounts',
                'berkasList',
                'totalSemua',
                'totalSudah',
                'totalBelum',
                'kategoriUid',
                'search',
                'statusFilter'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT PERIODE
    |--------------------------------------------------------------------------
    */

    private function formatPeriode(?string $periode): string
    {
        $periode = trim(
            (string) $periode
        );

        if ($periode === '') {
            return '';
        }

        $map = [
            'TW I' => 'Triwulan I',
            'TW II' => 'Triwulan II',
            'TW III' => 'Triwulan III',
            'TW IV' => 'Triwulan IV',

            'TWI' => 'Triwulan I',
            'TWII' => 'Triwulan II',
            'TWIII' => 'Triwulan III',
            'TWIV' => 'Triwulan IV',
        ];

        $key = strtoupper(
            preg_replace(
                '/\s+/',
                ' ',
                $periode
            )
        );

        return $map[$key] ?? $periode;
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */

    private function getLoginUser(): SamperinUser
    {
        $userId = session(
            'samperin_user_id'
        );

        if (!$userId) {

            abort(
                redirect()->route(
                    'samperin.login'
                )
            );
        }


        $user = SamperinUser::query()
            ->with([
                'foto',
                'jenisKerja',
                'jabatan',
                'bidang',
                'golongan',
                'eselon',
                'pendidikan',
            ])
            ->find($userId);


        if (!$user) {

            session()->invalidate();
            session()->regenerateToken();

            abort(
                redirect()->route(
                    'samperin.login'
                )
            );
        }


        if ((int) $user->user_status !== 1) {

            session()->invalidate();
            session()->regenerateToken();

            abort(
                redirect()
                    ->route('samperin.login')
                    ->withErrors([
                        'login' =>
                            'Akun Anda sudah tidak aktif.',
                    ])
            );
        }


        return $user;
    }
}