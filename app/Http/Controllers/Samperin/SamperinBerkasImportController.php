<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinJenisBerkas;
use App\Models\SamperinPermintaanBerkas;
use App\Models\SamperinPengumpulanBerkas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class SamperinBerkasImportController extends Controller
{
    /**
     * Halaman utama import.
     */
    public function index()
    {
        $permintaan = SamperinPermintaanBerkas::with('jenisBerkas.kategori')
            ->orderBy('permintaan_tahun')
            ->orderBy('permintaan_judul')
            ->get();

        return view('dashboard.admin.import-berkas.index', compact('permintaan'));
    }


    /**
     * Analisis data SADARIN sebelum import.
     *
     * Tidak ada data yang ditulis ke database SAMPERIN.
     */
    public function preview(Request $request)
    {
        try {
            $sadarin = DB::connection('sadarin');

            /*
            |--------------------------------------------------------------------------
            | CEK KONEKSI DAN TABEL
            |--------------------------------------------------------------------------
            */

            $sadarin->table('sadarin_pengumpulanberkas')
                ->limit(1)
                ->get();

            $sadarin->table('sadarin_user')
                ->limit(1)
                ->get();


            /*
            |--------------------------------------------------------------------------
            | TOTAL DATA
            |--------------------------------------------------------------------------
            */

            $total = $sadarin
                ->table('sadarin_pengumpulanberkas')
                ->count();


            /*
            |--------------------------------------------------------------------------
            | DATA YANG MEMILIKI FILE
            |--------------------------------------------------------------------------
            */

            $denganFile = $sadarin
                ->table('sadarin_pengumpulanberkas')
                ->whereNotNull('kumpulan_file')
                ->where('kumpulan_file', '<>', '')
                ->where('kumpulan_file', '<>', 'null')
                ->where('kumpulan_file', '<>', '-')
                ->count();


            /*
            |--------------------------------------------------------------------------
            | DATA KOSONG
            |--------------------------------------------------------------------------
            */

            $kosong = $total - $denganFile;


            /*
            |--------------------------------------------------------------------------
            | DAFTAR JENIS LAMA
            |--------------------------------------------------------------------------
            */

            $jenisLama = $sadarin
                ->table('sadarin_pengumpulanberkas')
                ->select('kumpulan_jenis')
                ->selectRaw('COUNT(*) as jumlah')
                ->groupBy('kumpulan_jenis')
                ->orderBy('kumpulan_jenis')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | MAPPING JENIS
            |--------------------------------------------------------------------------
            |
            | Kita cocokkan kumpulan_jenis dengan judul permintaan SAMPERIN.
            |
            */

            $permintaan = SamperinPermintaanBerkas::with('jenisBerkas')
                ->get();

            $permintaanMap = [];

            foreach ($permintaan as $item) {
                $permintaanMap[
                    $this->normalisasi($item->permintaan_judul)
                ] = $item;
            }

            $jenisCocok = 0;
            $jenisTidakCocok = 0;

            foreach ($jenisLama as $jenis) {
                $key = $this->normalisasi($jenis->kumpulan_jenis);

                if (isset($permintaanMap[$key])) {
                    $jenisCocok++;
                } else {
                    $jenisTidakCocok++;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | CEK PEGAWAI
            |--------------------------------------------------------------------------
            |
            | kumpulan_user → sadarin_user.user_nip
            |              → sadarin_user.user_id
            |              → samperin_user.user_id
            |
            */

            $userLama = $sadarin
                ->table('sadarin_pengumpulanberkas as p')
                ->whereNotNull('p.kumpulan_file')
                ->where('p.kumpulan_file', '<>', '')
                ->where('p.kumpulan_file', '<>', 'null')
                ->where('p.kumpulan_file', '<>', '-')
                ->select('p.kumpulan_user')
                ->distinct()
                ->pluck('p.kumpulan_user');

            $userLama = $userLama
                ->map(fn ($value) => trim((string) $value))
                ->filter(fn ($value) => $value !== '' && $value !== '-')
                ->values();

            $sadarinUsers = collect();

            if ($userLama->isNotEmpty()) {
                $sadarinUsers = $sadarin
                    ->table('sadarin_user')
                    ->whereIn('user_nip', $userLama->all())
                    ->pluck('user_id', 'user_nip');
            }

            $sadarinUserIds = $sadarinUsers
                ->values()
                ->map(fn ($id) => (int) $id)
                ->filter()
                ->unique()
                ->values();

            $samperinUserIds = collect();

            if ($sadarinUserIds->isNotEmpty()) {
                $samperinUserIds = DB::table('samperin_user')
                    ->whereIn('user_id', $sadarinUserIds->all())
                    ->pluck('user_id')
                    ->map(fn ($id) => (int) $id);
            }

            $pegawaiCocok = $samperinUserIds->count();

            $pegawaiTidakCocok =
                $userLama->count() - $pegawaiCocok;


            /*
            |--------------------------------------------------------------------------
            | HASIL
            |--------------------------------------------------------------------------
            */

            return back()
                ->with('preview', [
                    'total' => $total,
                    'dengan_file' => $denganFile,
                    'kosong' => $kosong,
                    'pegawai_cocok' => $pegawaiCocok,
                    'pegawai_tidak_cocok' => max(0, $pegawaiTidakCocok),
                    'jenis_cocok' => $jenisCocok,
                    'jenis_tidak_cocok' => $jenisTidakCocok,
                ])
                ->with('jenis_lama', $jenisLama);

        } catch (Throwable $e) {

            return back()
                ->with('error', 'Gagal menganalisis database SADARIN: ' . $e->getMessage());
        }
    }


    /**
     * Jalankan proses import.
     */
    public function import(Request $request)
    {
        set_time_limit(0);

        try {

            $sadarin = DB::connection('sadarin');


            /*
            |--------------------------------------------------------------------------
            | CACHE MAPPING PERMINTAAN
            |--------------------------------------------------------------------------
            */

            $permintaan = SamperinPermintaanBerkas::with('jenisBerkas')
                ->get();

            $permintaanMap = [];

            foreach ($permintaan as $item) {
                $permintaanMap[
                    $this->normalisasi($item->permintaan_judul)
                ] = $item;
            }


            /*
            |--------------------------------------------------------------------------
            | CACHE MAPPING USER
            |--------------------------------------------------------------------------
            */

            $userMap = $sadarin
                ->table('sadarin_user')
                ->whereNotNull('user_nip')
                ->pluck('user_id', 'user_nip')
                ->mapWithKeys(function ($userId, $nip) {
                    return [
                        trim((string) $nip) => (int) $userId,
                    ];
                });


            /*
            |--------------------------------------------------------------------------
            | DATA STATISTIK
            |--------------------------------------------------------------------------
            */

            $stats = [
                'dibaca' => 0,
                'diimport' => 0,
                'dilewati_kosong' => 0,
                'pegawai_tidak_ditemukan' => 0,
                'jenis_tidak_ditemukan' => 0,
                'duplikat' => 0,
                'gagal' => 0,
            ];


            /*
            |--------------------------------------------------------------------------
            | IMPORT BERTAHAP
            |--------------------------------------------------------------------------
            |
            | Tidak mengambil seluruh 52 ribu record ke memory.
            |
            */

            $sadarin
                ->table('sadarin_pengumpulanberkas')
                ->orderBy('kumpulan_id')
                ->chunkById(500, function ($rows) use (
                    &$stats,
                    $userMap,
                    $permintaanMap
                ) {

                    foreach ($rows as $row) {

                        $stats['dibaca']++;


                        /*
                        |--------------------------------------------------------------------------
                        | FILE VALID
                        |--------------------------------------------------------------------------
                        */

                        $file = trim((string) $row->kumpulan_file);

                        if (
                            $file === '' ||
                            strtolower($file) === 'null' ||
                            $file === '-'
                        ) {
                            $stats['dilewati_kosong']++;
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | JENIS BERKAS
                        |--------------------------------------------------------------------------
                        */

                        $jenisKey = $this->normalisasi(
                            $row->kumpulan_jenis
                        );

                        $permintaan =
                            $permintaanMap[$jenisKey] ?? null;

                        if (!$permintaan) {
                            $stats['jenis_tidak_ditemukan']++;
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | USER SADARIN
                        |--------------------------------------------------------------------------
                        */

                        $kumpulanUser =
                            trim((string) $row->kumpulan_user);

                        if (
                            $kumpulanUser === '' ||
                            $kumpulanUser === '-'
                        ) {
                            $stats['pegawai_tidak_ditemukan']++;
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SADARIN NIP → SADARIN USER ID
                        |--------------------------------------------------------------------------
                        */

                        $sadarinUserId =
                            $userMap[$kumpulanUser] ?? null;

                        if (!$sadarinUserId) {
                            $stats['pegawai_tidak_ditemukan']++;
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SADARIN USER ID → SAMPERIN USER ID
                        |--------------------------------------------------------------------------
                        |
                        | Ini sengaja STRICT.
                        |
                        */

                        $samperinUserExists = DB::table('samperin_user')
                            ->where('user_id', $sadarinUserId)
                            ->exists();

                        if (!$samperinUserExists) {
                            $stats['pegawai_tidak_ditemukan']++;
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | CEK DUPLIKAT
                        |--------------------------------------------------------------------------
                        */

                        $exists = SamperinPengumpulanBerkas::query()
                            ->where(
                                'pengumpulan_berkas_user_uid',
                                function ($query) use ($sadarinUserId) {
                                    $query->select('user_uid')
                                        ->from('samperin_user')
                                        ->where('user_id', $sadarinUserId)
                                        ->limit(1);
                                }
                            )
                            ->where(
                                'pengumpulan_berkas_permintaan_id',
                                $permintaan->permintaan_id
                            )
                            ->exists();

                        if ($exists) {
                            $stats['duplikat']++;
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | AMBIL USER UID
                        |--------------------------------------------------------------------------
                        */

                        $userUid = DB::table('samperin_user')
                            ->where('user_id', $sadarinUserId)
                            ->value('user_uid');

                        if (!$userUid) {
                            $stats['pegawai_tidak_ditemukan']++;
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | INSERT
                        |--------------------------------------------------------------------------
                        */

                        try {

                            SamperinPengumpulanBerkas::create([
                                'pengumpulan_berkas_uid' =>
                                    (string) Str::uuid(),

                                'pengumpulan_berkas_user_uid' =>
                                    $userUid,

                                'pengumpulan_berkas_permintaan_id' =>
                                    $permintaan->permintaan_id,

                                'pengumpulan_berkas_file' =>
                                    $file,

                                'pengumpulan_berkas_nama' =>
                                    null,

                                'pengumpulan_berkas_mime' =>
                                    null,

                                'pengumpulan_berkas_size' =>
                                    null,

                                'pengumpulan_berkas_tanggal' =>
                                    $row->created_at,

                                'pengumpulan_berkas_status' =>
                                    'terkirim',

                                'pengumpulan_berkas_verified_at' =>
                                    null,

                                'pengumpulan_berkas_verified_by' =>
                                    null,

                                'pengumpulan_berkas_keterangan' =>
                                    $row->kumpulan_keterangan,

                                'pengumpulan_berkas_sumber' =>
                                    'SADARIN',

                                'pengumpulan_berkas_sumber_id' =>
                                    $row->kumpulan_id,

                                'pengumpulan_berkas_created_at' =>
                                    $row->created_at ?? now(),

                                'pengumpulan_berkas_updated_at' =>
                                    $row->updated_at ?? now(),
                            ]);

                            $stats['diimport']++;

                        } catch (Throwable $e) {

                            /*
                             * Jika ada satu row gagal,
                             * proses keseluruhan tetap lanjut.
                             */

                            $stats['gagal']++;
                        }
                    }
                }, 'kumpulan_id');


            /*
            |--------------------------------------------------------------------------
            | HASIL
            |--------------------------------------------------------------------------
            */

            return back()
                ->with(
                    'success',
                    'Import pemberkasan SADARIN selesai.'
                )
                ->with('import_stats', $stats);

        } catch (Throwable $e) {

            return back()
                ->with(
                    'error',
                    'Import gagal: ' . $e->getMessage()
                );
        }
    }


    /**
     * Normalisasi teks untuk pencocokan.
     */
    private function normalisasi(?string $value): string
    {
        $value = trim((string) $value);

        $value = mb_strtolower(
            $value,
            'UTF-8'
        );

        $value = preg_replace(
            '/\s+/',
            ' ',
            $value
        );

        return $value;
    }
}