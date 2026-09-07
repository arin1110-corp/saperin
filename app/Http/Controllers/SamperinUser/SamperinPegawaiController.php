<?php

namespace App\Http\Controllers\SamperinUser;

use App\Http\Controllers\Controller;
use App\Models\SamperinApi;
use App\Models\SamperinBerkasKategori;
use App\Models\SamperinFolder;
use App\Models\SamperinJenisBerkas;
use App\Models\SamperinPengumpulanBerkas;
use App\Models\SamperinPermintaanBerkas;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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
                $query->where('target_status', true)->where('target_jenis_kerja_id', $user->user_jenis_kerja_id)->with('folder');
                },
            ])

            /*
        |--------------------------------------------------------------------------
        | PERMINTAAN HARUS AKTIF
        |--------------------------------------------------------------------------
        */

            ->where('permintaan_status', true)

            /*
        |--------------------------------------------------------------------------
        | SUDAH MEMASUKI MASA PENGUMPULAN
        |--------------------------------------------------------------------------
        */

            ->where(function ($query) {
            $query->whereNull('permintaan_mulai')->orWhere('permintaan_mulai', '<=', now());
            })

            /*
        |--------------------------------------------------------------------------
        | BELUM MELEWATI DEADLINE
        |--------------------------------------------------------------------------
        */

            ->where(function ($query) {
            $query->whereNull('permintaan_expired')->orWhere('permintaan_expired', '>=', now());
            })

            /*
        |--------------------------------------------------------------------------
        | TARGET HARUS SESUAI JENIS KERJA PEGAWAI
        |--------------------------------------------------------------------------
        */

            ->whereHas('target', function ($query) use ($user) {
                $query->where('target_status', true)->where('target_jenis_kerja_id', $user->user_jenis_kerja_id);
        })

            ->orderByDesc('permintaan_mulai')
            ->orderByDesc('permintaan_id')
            ->get();

        return view('pegawai.index', compact('user', 'permintaanAktif'));
    }

    /*
    |--------------------------------------------------------------------------
    | PROFIL
    |--------------------------------------------------------------------------
    */

    public function profil()
    {
        $user = $this->getLoginUser();

        return view('pegawai.profil', compact('user'));
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

        $kategoriUid = trim((string) $request->input('kategori'));

        $search = trim((string) $request->input('search'));

        $statusFilter = trim((string) $request->input('status'));

        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

        $kategoriList = SamperinBerkasKategori::query()->where('kategori_status', true)->orderBy('kategori_nama')->get();

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
                $query->where('target_status', true)->where('target_jenis_kerja_id', $user->user_jenis_kerja_id)->with('folder');
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
            ->where('pengumpulan_berkas_user_uid', $user->user_uid)
            ->orderByDesc('pengumpulan_berkas_id')
            ->get()
            ->groupBy('pengumpulan_berkas_permintaan_id')
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

            $pengumpulan = $pengumpulanByPermintaan->get($permintaan->permintaan_id);

            /*
                |--------------------------------------------------------------------------
                | JUDUL PERMINTAAN
                |--------------------------------------------------------------------------
                */

            $judul = trim((string) $permintaan->permintaan_judul);

                if ($judul === '') {
                $judul = trim((string) ($jenis?->jenis_berkas_nama ?? 'Berkas'));
                }

            /*
                |--------------------------------------------------------------------------
                | TAHUN
                |--------------------------------------------------------------------------
                */

            $tahun = $permintaan->permintaan_tahun;

            if ($tahun && !str_contains(strtolower($judul), (string) $tahun)) {
                    $judul .= ' ' . $tahun;
                }

            /*
                |--------------------------------------------------------------------------
                | PERIODE
                |--------------------------------------------------------------------------
                */

            $periode = trim((string) $permintaan->permintaan_periode);

                if ($periode !== '') {
                $periodeLabel = $this->formatPeriode($periode);

                if (!str_contains(strtolower($judul), strtolower($periodeLabel))) {
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

                'permintaan_uid' => $permintaan->permintaan_uid,

                'permintaan_id' => $permintaan->permintaan_id,

                'judul' => $judul,

                'kategori' => $kategori,

                'kategori_uid' => $kategori?->kategori_uid,

                'kategori_nama' => $kategori?->kategori_nama ?? 'Lainnya',

                'jenis_nama' => $jenis?->jenis_berkas_nama ?? null,

                'sudah' => $sudah,

                'status' => $sudah ? 'sudah' : 'belum',

                'pengumpulan' => $pengumpulan,

                'tanggal_upload' => $pengumpulan?->pengumpulan_berkas_tanggal,

                'file_url' => $pengumpulan?->pengumpulan_berkas_file,

                'file_nama' => $pengumpulan?->pengumpulan_berkas_nama,
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
            return strtolower((string) $item['kategori_uid']);
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
                return str_contains(strtolower($item['judul']), $searchLower) || str_contains(strtolower($item['kategori_nama']), $searchLower) || str_contains(strtolower((string) $item['jenis_nama']), $searchLower);
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER KATEGORI
        |--------------------------------------------------------------------------
        */

        if ($kategoriUid !== '') {
            $kategoriLower = strtolower($kategoriUid);

            $berkasRows = $berkasRows
                ->filter(function ($item) use ($kategoriLower) {
                return strtolower((string) $item['kategori_uid']) === $kategoriLower;
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

        $currentItems = $berkasRows->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $berkasList = new LengthAwarePaginator($currentItems, $totalFiltered, $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalSudah = $berkasRows->where('sudah', true)->count();

        $totalBelum = $berkasRows->where('sudah', false)->count();

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return view('pegawai.berkas', compact('user', 'kategoriList', 'kategoriCounts', 'berkasList', 'totalSemua', 'totalSudah', 'totalBelum', 'kategoriUid', 'search', 'statusFilter'));
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT PERIODE
    |--------------------------------------------------------------------------
    */

    private function formatPeriode(?string $periode): string
    {
        $periode = trim((string) $periode);

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

        $key = strtoupper(preg_replace('/\s+/', ' ', $periode));

        return $map[$key] ?? $periode;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */

    private function getLoginUser(): SamperinUser
    {
        $userId = session('samperin_user_id');

        if (!$userId) {
            abort(redirect()->route('samperin.login'));
        }

        $user = SamperinUser::query()
            ->with(['foto', 'jenisKerja', 'jabatan', 'bidang', 'golongan', 'eselon', 'pendidikan'])
            ->find($userId);

        if (!$user) {
            session()->invalidate();
            session()->regenerateToken();

            abort(redirect()->route('samperin.login'));
        }

        if ((int) $user->user_status !== 1) {
            session()->invalidate();
            session()->regenerateToken();

            abort(
                redirect()
                    ->route('samperin.login')
                    ->withErrors([
                        'login' => 'Akun Anda sudah tidak aktif.',
                    ]),
            );
        }

        return $user;
    }
    /*
|--------------------------------------------------------------------------
| UPLOAD BERKAS PEGAWAI
|--------------------------------------------------------------------------
|
| Pegawai hanya bisa upload:
|
| - untuk dirinya sendiri
| - pada permintaan yang aktif
| - pada target jenis kerja miliknya
| - menggunakan folder Drive yang sudah ditentukan admin
|
*/

    public function upload(Request $request, string $permintaanUid)
    {
        set_time_limit(0);

        /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */

        $user = $this->getLoginUser();

        /*
    |--------------------------------------------------------------------------
    | VALIDASI FILE
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
    | PERMINTAAN
    |--------------------------------------------------------------------------
    */

        $permintaan = SamperinPermintaanBerkas::query()
            ->with([
                'jenisBerkas',
                'target' => function ($query) use ($user) {
                    $query->where('target_jenis_kerja_id', $user->user_jenis_kerja_id)->where('target_status', true)->with('folder');
                },
            ])
            ->where('permintaan_uid', $permintaanUid)
            ->where('permintaan_status', true)
            ->firstOrFail();

        /*
    |--------------------------------------------------------------------------
    | CEK WAKTU MULAI
    |--------------------------------------------------------------------------
    */

        if ($permintaan->permintaan_mulai && now()->lt($permintaan->permintaan_mulai)) {
            return back()->with('error', 'Permintaan berkas belum dibuka.');
        }

        /*
    |--------------------------------------------------------------------------
    | CEK DEADLINE
    |--------------------------------------------------------------------------
    */

        if ($permintaan->permintaan_expired && now()->gt($permintaan->permintaan_expired)) {
            return back()->with('error', 'Batas waktu pengumpulan berkas sudah berakhir.');
        }

        /*
    |--------------------------------------------------------------------------
    | TARGET PEGAWAI
    |--------------------------------------------------------------------------
    */

        $target = $permintaan->target->first();

        if (!$target) {
            return back()->with('error', 'Anda tidak termasuk target permintaan berkas ini.');
        }

        /*
    |--------------------------------------------------------------------------
    | FOLDER DRIVE
    |--------------------------------------------------------------------------
    |
    | Folder berasal dari target_folder_id yang dipilih
    | administrator saat membuat/edit permintaan.
    |
    */

        $folder = $target->folder;

        if (!$folder || !$folder->folder_status || !$folder->folder_drive_id) {
            return back()->with('error', 'Folder penyimpanan berkas belum dikonfigurasi.');
        }

        /*
    |--------------------------------------------------------------------------
    | CEK APAKAH SUDAH PERNAH UPLOAD
    |--------------------------------------------------------------------------
    */

        $existing = SamperinPengumpulanBerkas::query()->where('pengumpulan_berkas_user_uid', $user->user_uid)->where('pengumpulan_berkas_permintaan_id', $permintaan->permintaan_id)->first();

        // if ($existing) {
        //     return back()->with('error', 'Berkas untuk permintaan ini sudah pernah dikirim.');
        // }

        /*
    |--------------------------------------------------------------------------
    | UPLOAD KE ARINDRIVE
    |--------------------------------------------------------------------------
    */

        try {
            $upload = $this->uploadToDrive($request->file('file'), $user, $permintaan, $folder);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        /*
|--------------------------------------------------------------------------
| SIMPAN / REPLACE DATABASE
|--------------------------------------------------------------------------
*/

        if ($existing) {
            $existing->update([
                'pengumpulan_berkas_file' => $upload['url'],

                'pengumpulan_berkas_nama' => $request->file('file')->getClientOriginalName(),

                'pengumpulan_berkas_mime' => $request->file('file')->getMimeType(),

                'pengumpulan_berkas_size' => $request->file('file')->getSize(),

                'pengumpulan_berkas_tanggal' => now(),

                'pengumpulan_berkas_status' => 'terkirim',

                'pengumpulan_berkas_keterangan' => 'Berkas diganti oleh pegawai.',

                'pengumpulan_berkas_sumber' => 'PEGAWAI',

                'pengumpulan_berkas_sumber_id' => null,

                'pengumpulan_berkas_updated_at' => now(),
            ]);
        } else {
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

                'pengumpulan_berkas_keterangan' => 'Berkas dikirim oleh pegawai.',

                'pengumpulan_berkas_sumber' => 'PEGAWAI',

                'pengumpulan_berkas_sumber_id' => null,

                'pengumpulan_berkas_created_at' => now(),

                'pengumpulan_berkas_updated_at' => now(),
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | KEMBALI
    |--------------------------------------------------------------------------
    */

        return redirect()->route('pegawai.index')->with('success', 'Berkas berhasil dikirim.');
    }
    /*
|--------------------------------------------------------------------------
| UPLOAD KE ARINDRIVE
|--------------------------------------------------------------------------
*/

    private function uploadToDrive($file, SamperinUser $user, SamperinPermintaanBerkas $permintaan, SamperinFolder $folder): array
    {
        /*
    |--------------------------------------------------------------------------
    | API ARINDRIVE
    |--------------------------------------------------------------------------
    */

        $api = SamperinApi::query()->where('api_kode', 'ARINDRIVE')->where('api_status', true)->first();

        if (!$api) {
            throw new \Exception('API ArinDrive belum dikonfigurasi.');
        }

        /*
    |--------------------------------------------------------------------------
    | EXTENSION FILE
    |--------------------------------------------------------------------------
    */

        $extension = strtolower($file->getClientOriginalExtension());

        if ($extension === '') {
            throw new \Exception('Format file tidak ditemukan.');
        }

        /*
    |--------------------------------------------------------------------------
    | JUDUL PERMINTAAN
    |--------------------------------------------------------------------------
    */

        $judul = trim((string) $permintaan->permintaan_judul);

        /*
    | Jika judul kosong, gunakan nama jenis berkas.
    */

        if ($judul === '') {
            $judul = trim((string) ($permintaan->jenisBerkas?->jenis_berkas_nama ?? 'Berkas'));
        }

        /*
    |--------------------------------------------------------------------------
    | BERSIHKAN JUDUL
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | Pakta Integritas
    | ↓
    | Pakta_Integritas
    |
    */

        $judul = preg_replace('/[^A-Za-z0-9]+/', '_', $judul);

        $judul = trim($judul, '_');

        /*
    |--------------------------------------------------------------------------
    | TAHUN
    |--------------------------------------------------------------------------
    */

        $tahun = trim((string) $permintaan->permintaan_tahun);

        /*
    |--------------------------------------------------------------------------
    | IDENTITAS PEGAWAI
    |--------------------------------------------------------------------------
    |
    | Utamakan NIP.
    | Jika NIP kosong, gunakan UID.
    |
    */

        $identitas = trim((string) ($user->user_nip ?: $user->user_uid));

        /*
    |--------------------------------------------------------------------------
    | NAMA FILE
    |--------------------------------------------------------------------------
    |
    | Format:
    |
    | NIP_Judul_Tahun.ext
    |
    | Contoh:
    |
    | 199510112020121001_Pakta_Integritas_2026.pdf
    |
    */

        $parts = [$identitas, $judul];

        if ($tahun !== '') {
            $parts[] = $tahun;
        }

        $filename = implode('_', array_filter($parts)) . '.' . $extension;

        /*
    |--------------------------------------------------------------------------
    | BACA FILE
    |--------------------------------------------------------------------------
    */

        $fileContent = file_get_contents($file->getRealPath());

        if ($fileContent === false) {
            throw new \Exception('File tidak dapat dibaca.');
        }

        /*
    |--------------------------------------------------------------------------
    | UPLOAD KE ARINDRIVE
    |--------------------------------------------------------------------------
    |
    | API dan folder semuanya berasal dari database.
    |
    */

        $response = Http::withToken($api->api_token)
            ->timeout(120)
            ->attach('file', $fileContent, $filename)
            ->post(rtrim($api->api_url, '/') . '/api/upload-drive', [
                /*
                |--------------------------------------------------------------------------
                | FOLDER DRIVE
                |--------------------------------------------------------------------------
                |
                | Menggunakan folder_drive_id dari
                | samperin_folder yang dipilih pada target permintaan.
                |
                */

                'folder_id' => $folder->folder_drive_id,

            /*
                |--------------------------------------------------------------------------
                | NAMA FILE
                |--------------------------------------------------------------------------
                */

            'filename' => $filename,

            /*
                |--------------------------------------------------------------------------
                | SOURCE APPLICATION
                |--------------------------------------------------------------------------
                */

            'source_app' => 'samperin',

            /*
                |--------------------------------------------------------------------------
                | PREFIX FOLDER
                |--------------------------------------------------------------------------
                */

            'folder' => $folder->folder_prefix ?: 'berkas-pegawai',

                /*
                |--------------------------------------------------------------------------
                | REFERENCE ID
                |--------------------------------------------------------------------------
                */

                'reference_id' => $user->user_uid . '-berkas-' . $permintaan->permintaan_uid,
            ]);

        /*
    |--------------------------------------------------------------------------
    | CEK RESPONSE
    |--------------------------------------------------------------------------
    */

        if (!$response->successful()) {
            throw new \Exception('Upload ke ArinDrive gagal. ' . 'HTTP ' . $response->status() . ': ' . $response->body());
        }

        /*
    |--------------------------------------------------------------------------
    | RESPONSE JSON
    |--------------------------------------------------------------------------
    */

        $result = $response->json();

        /*
    |--------------------------------------------------------------------------
    | AMBIL URL FILE
    |--------------------------------------------------------------------------
    */

        $url = data_get($result, 'url') ?? (data_get($result, 'file_url') ?? (data_get($result, 'data.url') ?? (data_get($result, 'data.file_url') ?? data_get($result, 'data.web_view_link'))));

        /*
    |--------------------------------------------------------------------------
    | URL TIDAK DITEMUKAN
    |--------------------------------------------------------------------------
    */

        if (!$url) {
            throw new \Exception('Upload ke ArinDrive berhasil, ' . 'tetapi URL file tidak ditemukan. ' . 'Response: ' . json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        }

        /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */

        return [
            'url' => $url,
            'response' => $result,
            'filename' => $filename,
        ];
    }
}