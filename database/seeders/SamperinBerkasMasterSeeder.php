<?php

namespace Database\Seeders;

use App\Models\SamperinBerkasKategori;
use App\Models\SamperinFolder;
use App\Models\SamperinJenisBerkas;
use App\Models\SamperinJenisKerja;
use App\Models\SamperinPermintaanBerkas;
use App\Models\SamperinPermintaanTarget;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SamperinBerkasMasterSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | RESET MASTER PEMBERKASAN
            |--------------------------------------------------------------------------
            |
            | Yang dihapus:
            | - samperin_permintaan_target
            | - samperin_permintaan_berkas
            | - samperin_jenis_berkas
            | - samperin_berkas_kategori
            |
            | Yang TIDAK dihapus:
            | - samperin_user
            | - samperin_role
            | - samperin_folder
            | - data pegawai
            | - folder Drive
            |
            */

            SamperinPermintaanTarget::query()->delete();

            SamperinPermintaanBerkas::query()->delete();

            SamperinJenisBerkas::query()->delete();

            SamperinBerkasKategori::query()->delete();


            /*
            |--------------------------------------------------------------------------
            | 1. KATEGORI
            |--------------------------------------------------------------------------
            */

            $kategoriData = [
                [
                    'kode' => 'ADMINISTRASI_PEGAWAI',
                    'nama' => 'Administrasi Pegawai',
                    'keterangan' => 'Dokumen administrasi dan identitas pegawai.',
                ],
                [
                    'kode' => 'KINERJA',
                    'nama' => 'Kinerja',
                    'keterangan' => 'Dokumen terkait kinerja pegawai.',
                ],
                [
                    'kode' => 'PERJANJIAN_KINERJA',
                    'nama' => 'Perjanjian Kinerja',
                    'keterangan' => 'Dokumen perjanjian dan komitmen kinerja.',
                ],
                [
                    'kode' => 'PERENCANAAN',
                    'nama' => 'Perencanaan',
                    'keterangan' => 'Dokumen perencanaan kegiatan.',
                ],
                [
                    'kode' => 'KEPATUHAN',
                    'nama' => 'Kepatuhan',
                    'keterangan' => 'Dokumen kepatuhan dan integritas pegawai.',
                ],
            ];

            $kategoriMap = [];

            foreach ($kategoriData as $item) {

                $kategori = SamperinBerkasKategori::create([
                    'kategori_uid' => (string) Str::uuid(),
                    'kategori_kode' => $item['kode'],
                    'kategori_nama' => $item['nama'],
                    'kategori_keterangan' => $item['keterangan'],
                    'kategori_status' => true,
                    'kategori_created_at' => now(),
                    'kategori_updated_at' => now(),
                ]);

                $kategoriMap[$item['kode']] =
                    $kategori->kategori_id;
            }


            /*
            |--------------------------------------------------------------------------
            | 2. JENIS BERKAS
            |--------------------------------------------------------------------------
            */

            $jenisData = [
                [
                    'kode' => 'CORETAX',
                    'nama' => 'Coretax',
                    'kategori' => 'ADMINISTRASI_PEGAWAI',
                    'sifat' => 'tahunan',
                ],
                [
                    'kode' => 'LAPORAN_IKD',
                    'nama' => 'Laporan IKD',
                    'kategori' => 'ADMINISTRASI_PEGAWAI',
                    'sifat' => 'tahunan',
                ],
                [
                    'kode' => 'KTP',
                    'nama' => 'KTP',
                    'kategori' => 'ADMINISTRASI_PEGAWAI',
                    'sifat' => 'tetap',
                ],
                [
                    'kode' => 'NPWP',
                    'nama' => 'NPWP',
                    'kategori' => 'ADMINISTRASI_PEGAWAI',
                    'sifat' => 'tetap',
                ],
                [
                    'kode' => 'BUKU_REKENING',
                    'nama' => 'Buku Rekening',
                    'kategori' => 'ADMINISTRASI_PEGAWAI',
                    'sifat' => 'tetap',
                ],
                [
                    'kode' => 'BPJS_KESEHATAN',
                    'nama' => 'BPJS Kesehatan',
                    'kategori' => 'ADMINISTRASI_PEGAWAI',
                    'sifat' => 'tetap',
                ],
                [
                    'kode' => 'KARTU_KELUARGA',
                    'nama' => 'Kartu Keluarga',
                    'kategori' => 'ADMINISTRASI_PEGAWAI',
                    'sifat' => 'tetap',
                ],
                [
                    'kode' => 'IJAZAH_TERAKHIR',
                    'nama' => 'Ijazah Terakhir',
                    'kategori' => 'ADMINISTRASI_PEGAWAI',
                    'sifat' => 'tetap',
                ],

                [
                    'kode' => 'EVALUASI_KINERJA',
                    'nama' => 'Evaluasi Kinerja',
                    'kategori' => 'KINERJA',
                    'sifat' => 'periodik',
                ],
                [
                    'kode' => 'UMPAN_BALIK',
                    'nama' => 'Umpan Balik',
                    'kategori' => 'KINERJA',
                    'sifat' => 'periodik',
                ],
                [
                    'kode' => 'EVALUASI_KINERJA_TAHUNAN',
                    'nama' => 'Evaluasi Kinerja Tahunan',
                    'kategori' => 'KINERJA',
                    'sifat' => 'tahunan',
                ],
                [
                    'kode' => 'SKP',
                    'nama' => 'SKP',
                    'kategori' => 'KINERJA',
                    'sifat' => 'tahunan',
                ],
                [
                    'kode' => 'MODEL_C',
                    'nama' => 'Model C',
                    'kategori' => 'KINERJA',
                    'sifat' => 'tahunan',
                ],

                [
                    'kode' => 'PERJANJIAN_KINERJA',
                    'nama' => 'Perjanjian Kinerja',
                    'kategori' => 'PERJANJIAN_KINERJA',
                    'sifat' => 'tahunan',
                ],

                [
                    'kode' => 'RENCANA_AKSI',
                    'nama' => 'Rencana Aksi',
                    'kategori' => 'PERENCANAAN',
                    'sifat' => 'tahunan',
                ],

                [
                    'kode' => 'PAKTA_INTEGRITAS',
                    'nama' => 'Pakta Integritas',
                    'kategori' => 'KEPATUHAN',
                    'sifat' => 'periodik',
                ],
            ];

            $jenisMap = [];

            foreach ($jenisData as $item) {

                $jenis = SamperinJenisBerkas::create([
                    'jenis_berkas_uid' => (string) Str::uuid(),
                    'jenis_berkas_kategori_id' =>
                        $kategoriMap[$item['kategori']],
                    'jenis_berkas_kode' => $item['kode'],
                    'jenis_berkas_nama' => $item['nama'],
                    'jenis_berkas_sifat' => $item['sifat'],
                    'jenis_berkas_status' => true,
                    'jenis_berkas_created_at' => now(),
                    'jenis_berkas_updated_at' => now(),
                ]);

                $jenisMap[$item['kode']] =
                    $jenis->jenis_berkas_id;
            }


            /*
            |--------------------------------------------------------------------------
            | 3. PERMINTAAN BERKAS
            |--------------------------------------------------------------------------
            */

            $permintaanData = [

                [
                    'kode' => 'CORETAX_2026',
                    'jenis' => 'CORETAX',
                    'tahun' => 2026,
                    'periode' => null,
                    'judul' => 'Coretax 2026',
                    'tombol' => '2026',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'LAPORAN_IKD',
                    'jenis' => 'LAPORAN_IKD',
                    'tahun' => null,
                    'periode' => null,
                    'judul' => 'Laporan IKD',
                    'tombol' => 'IKD',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'DATA_KTP',
                    'jenis' => 'KTP',
                    'tahun' => null,
                    'periode' => null,
                    'judul' => 'Data KTP',
                    'tombol' => 'KTP',
                    'expired' => '2025-05-01',
                ],

                [
                    'kode' => 'DATA_NPWP',
                    'jenis' => 'NPWP',
                    'tahun' => null,
                    'periode' => null,
                    'judul' => 'Data NPWP',
                    'tombol' => 'NPWP',
                    'expired' => '2026-05-01',
                ],

                [
                    'kode' => 'DATA_BUKU_REKENING',
                    'jenis' => 'BUKU_REKENING',
                    'tahun' => null,
                    'periode' => null,
                    'judul' => 'Data Buku Rekening',
                    'tombol' => 'Rek',
                    'expired' => '2026-05-01',
                ],

                [
                    'kode' => 'DATA_BPJS_KESEHATAN',
                    'jenis' => 'BPJS_KESEHATAN',
                    'tahun' => null,
                    'periode' => null,
                    'judul' => 'Data BPJS Kesehatan',
                    'tombol' => 'BPJS',
                    'expired' => '2026-05-01',
                ],

                [
                    'kode' => 'DATA_KARTU_KELUARGA',
                    'jenis' => 'KARTU_KELUARGA',
                    'tahun' => null,
                    'periode' => null,
                    'judul' => 'Data Kartu Keluarga',
                    'tombol' => 'KK',
                    'expired' => '2026-05-01',
                ],

                [
                    'kode' => 'DATA_IJAZAH_TERAKHIR',
                    'jenis' => 'IJAZAH_TERAKHIR',
                    'tahun' => null,
                    'periode' => null,
                    'judul' => 'Data Ijazah Terakhir',
                    'tombol' => 'Ijazah',
                    'expired' => '2026-05-01',
                ],

                [
                    'kode' => 'EVKIN_2026_TW_1',
                    'jenis' => 'EVALUASI_KINERJA',
                    'tahun' => 2026,
                    'periode' => 'TW I',
                    'judul' => 'Evaluasi Kinerja Tahun 2026 Triwulan I',
                    'tombol' => 'TW I',
                    'expired' => '2026-08-10',
                ],

                [
                    'kode' => 'EVKIN_2026_TW_2',
                    'jenis' => 'EVALUASI_KINERJA',
                    'tahun' => 2026,
                    'periode' => 'TW II',
                    'judul' => 'Evaluasi Kinerja Tahun 2026 Triwulan II',
                    'tombol' => 'TW II',
                    'expired' => '2026-08-10',
                ],

                [
                    'kode' => 'EVKIN_2026_TW_3',
                    'jenis' => 'EVALUASI_KINERJA',
                    'tahun' => 2026,
                    'periode' => 'TW III',
                    'judul' => 'Evaluasi Kinerja Tahun 2026 Triwulan III',
                    'tombol' => 'TW III',
                    'expired' => null,
                ],

                [
                    'kode' => 'EVKIN_2026_TW_4',
                    'jenis' => 'EVALUASI_KINERJA',
                    'tahun' => 2026,
                    'periode' => 'TW IV',
                    'judul' => 'Evaluasi Kinerja Tahun 2026 Triwulan IV',
                    'tombol' => 'TW IV',
                    'expired' => null,
                ],

                [
                    'kode' => 'EVKIN_TW_1',
                    'jenis' => 'EVALUASI_KINERJA',
                    'tahun' => null,
                    'periode' => 'TW I',
                    'judul' => 'Evaluasi Kinerja Triwulan I',
                    'tombol' => 'TW I',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'EVKIN_TW_2',
                    'jenis' => 'EVALUASI_KINERJA',
                    'tahun' => null,
                    'periode' => 'TW II',
                    'judul' => 'Evaluasi Kinerja Triwulan II',
                    'tombol' => 'TW II',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'EVKIN_TW_3',
                    'jenis' => 'EVALUASI_KINERJA',
                    'tahun' => null,
                    'periode' => 'TW III',
                    'judul' => 'Evaluasi Kinerja Triwulan III',
                    'tombol' => 'TW III',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'EVKIN_TW_4',
                    'jenis' => 'EVALUASI_KINERJA',
                    'tahun' => null,
                    'periode' => 'TW IV',
                    'judul' => 'Evaluasi Kinerja Triwulan IV',
                    'tombol' => 'TW IV',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'UMBAL_2026_TW_1',
                    'jenis' => 'UMPAN_BALIK',
                    'tahun' => 2026,
                    'periode' => 'TW I',
                    'judul' => 'Rekaman Umpan Balik Tahun 2026 Triwulan I',
                    'tombol' => 'TW I',
                    'expired' => '2026-08-10',
                ],

                [
                    'kode' => 'UMBAL_2026_TW_2',
                    'jenis' => 'UMPAN_BALIK',
                    'tahun' => 2026,
                    'periode' => 'TW II',
                    'judul' => 'Rekaman Umpan Balik Tahun 2026 Triwulan II',
                    'tombol' => 'TW II',
                    'expired' => '2026-08-10',
                ],

                [
                    'kode' => 'UMBAL_TW_1',
                    'jenis' => 'UMPAN_BALIK',
                    'tahun' => null,
                    'periode' => 'TW I',
                    'judul' => 'Umpan Balik Triwulan I',
                    'tombol' => 'TW I',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'UMBAL_TW_2',
                    'jenis' => 'UMPAN_BALIK',
                    'tahun' => null,
                    'periode' => 'TW II',
                    'judul' => 'Umpan Balik Triwulan II',
                    'tombol' => 'TW II',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'UMBAL_TW_3',
                    'jenis' => 'UMPAN_BALIK',
                    'tahun' => null,
                    'periode' => 'TW III',
                    'judul' => 'Umpan Balik Triwulan III',
                    'tombol' => 'TW III',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'UMBAL_TW_4',
                    'jenis' => 'UMPAN_BALIK',
                    'tahun' => null,
                    'periode' => 'TW IV',
                    'judul' => 'Umpan Balik Triwulan IV',
                    'tombol' => 'TW IV',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'EVKIN_TAHUNAN_2025',
                    'jenis' => 'EVALUASI_KINERJA_TAHUNAN',
                    'tahun' => 2025,
                    'periode' => null,
                    'judul' => 'Evaluasi Kinerja Tahunan 2025',
                    'tombol' => '2025',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'SKP_2025',
                    'jenis' => 'SKP',
                    'tahun' => 2025,
                    'periode' => null,
                    'judul' => 'SKP 2025',
                    'tombol' => '2025',
                    'expired' => '2026-04-01',
                ],

                [
                    'kode' => 'SKP_2026',
                    'jenis' => 'SKP',
                    'tahun' => 2026,
                    'periode' => null,
                    'judul' => 'SKP 2026',
                    'tombol' => '2026',
                    'expired' => '2026-04-01',
                ],

                [
                    'kode' => 'MODEL_C_2025',
                    'jenis' => 'MODEL_C',
                    'tahun' => 2025,
                    'periode' => null,
                    'judul' => 'Model C 2025',
                    'tombol' => '2025',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'MODEL_C_2026',
                    'jenis' => 'MODEL_C',
                    'tahun' => 2026,
                    'periode' => null,
                    'judul' => 'Model C 2026',
                    'tombol' => '2026',
                    'expired' => '2026-03-31',
                ],

                [
                    'kode' => 'PERJANJIAN_KINERJA_2026',
                    'jenis' => 'PERJANJIAN_KINERJA',
                    'tahun' => 2026,
                    'periode' => null,
                    'judul' => 'Perjanjian Kinerja 2026',
                    'tombol' => '2026',
                    'expired' => '2026-04-01',
                ],

                [
                    'kode' => 'RENCANA_AKSI_2026',
                    'jenis' => 'RENCANA_AKSI',
                    'tahun' => 2026,
                    'periode' => null,
                    'judul' => 'Rencana Aksi Tahun 2026',
                    'tombol' => '2026',
                    'expired' => '2026-04-25',
                ],

                [
                    'kode' => 'PAKTA_INTEGRITAS',
                    'jenis' => 'PAKTA_INTEGRITAS',
                    'tahun' => null,
                    'periode' => null,
                    'judul' => 'Pakta Integritas',
                    'tombol' => '2025',
                    'expired' => '2026-05-01',
                ],

                [
                    'kode' => 'PAKTA_INTEGRITAS_1_DESEMBER_2025',
                    'jenis' => 'PAKTA_INTEGRITAS',
                    'tahun' => 2025,
                    'periode' => '1 Desember 2025',
                    'judul' => 'Pakta Integritas 1 Desember 2025',
                    'tombol' => '1 Des',
                    'expired' => '2026-05-01',
                ],

                [
                    'kode' => 'PAKTA_INTEGRITAS_2026',
                    'jenis' => 'PAKTA_INTEGRITAS',
                    'tahun' => 2026,
                    'periode' => null,
                    'judul' => 'Pakta Integritas 2026',
                    'tombol' => '2026',
                    'expired' => '2026-09-02',
                ],
            ];

            $permintaanMap = [];

            foreach ($permintaanData as $item) {

                $permintaan = SamperinPermintaanBerkas::create([
                    'permintaan_uid' => (string) Str::uuid(),

                    'permintaan_jenis_berkas_id' =>
                        $jenisMap[$item['jenis']],

                    'permintaan_tahun' =>
                        $item['tahun'],

                    'permintaan_periode' =>
                        $item['periode'],

                    'permintaan_judul' =>
                        $item['judul'],

                    'permintaan_tombol' =>
                        $item['tombol'],

                    'permintaan_keterangan' =>
                        'Master pemberkasan SAMPERIN.',

                    'permintaan_mulai' =>
                        now(),

                    'permintaan_expired' =>
                        $item['expired']
                            ? $item['expired'] . ' 23:59:59'
                            : null,

                    'permintaan_status' =>
                        true,

                    'permintaan_created_at' =>
                        now(),

                    'permintaan_updated_at' =>
                        now(),
                ]);

                $permintaanMap[$item['kode']] =
                    $permintaan->permintaan_id;
            }


            /*
            |--------------------------------------------------------------------------
            | 4. MAPPING TOMBOL SADARIN -> PERMINTAAN SAMPERIN
            |--------------------------------------------------------------------------
            */

            $mappingPermintaan = [

                1  => 'CORETAX_2026',
                2  => 'LAPORAN_IKD',

                3  => 'EVKIN_2026_TW_1',
                4  => 'UMBAL_2026_TW_1',

                5  => 'MODEL_C_2025',
                6  => 'MODEL_C_2026',

                7  => 'EVKIN_TW_1',
                8  => 'EVKIN_TW_2',
                9  => 'EVKIN_TW_3',
                10 => 'EVKIN_TW_4',

                11 => 'UMBAL_TW_1',
                12 => 'UMBAL_TW_2',
                13 => 'UMBAL_TW_3',
                14 => 'UMBAL_TW_4',

                15 => 'EVKIN_TAHUNAN_2025',

                16 => 'PERJANJIAN_KINERJA_2026',

                17 => 'SKP_2025',

                18 => 'DATA_KTP',

                19 => 'SKP_2026',

                20 => 'RENCANA_AKSI_2026',

                21 => 'DATA_NPWP',

                22 => 'DATA_BUKU_REKENING',

                23 => 'DATA_BPJS_KESEHATAN',

                24 => 'DATA_KARTU_KELUARGA',

                25 => 'DATA_IJAZAH_TERAKHIR',

                26 => 'PAKTA_INTEGRITAS',

                27 => 'PAKTA_INTEGRITAS_1_DESEMBER_2025',

                29 => 'EVKIN_2026_TW_2',

                30 => 'UMBAL_2026_TW_2',

                31 => 'PAKTA_INTEGRITAS_2026',
            ];


            /*
            |--------------------------------------------------------------------------
            | 5. JENIS KERJA
            |--------------------------------------------------------------------------
            |
            | ID SADARIN = ID SAMPERIN
            |
            | 1 = Pegawai Negeri Sipil
            | 2 = Pegawai Pemerintah dengan Perjanjian Kerja
            | 3 = PPPK Paruh Waktu
            | 4 = Pegawai dengan Jabatan Lingkungan Pemerintah
            |
            */

            $jenisKerjaMap = [
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
            ];

            foreach ($jenisKerjaMap as $sadarinId => $samperinId) {

                $jenisKerja = SamperinJenisKerja::query()
                    ->where('jenis_kerja_id', $samperinId)
                    ->where('jenis_kerja_status', true)
                    ->first();

                if (!$jenisKerja) {
                    throw new \RuntimeException(
                        'Jenis kerja SAMPERIN ID ' .
                        $samperinId .
                        ' tidak ditemukan.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | 6. FOLDER DRIVE
            |--------------------------------------------------------------------------
            |
            | Tidak membuat folder Google Drive.
            |
            | Data di bawah hanya mendaftarkan folder Drive yang
            | sudah ada berdasarkan mapping SADARIN.
            |
            | Format:
            |
            | [mapping_tombol, jenis_kerja_sadarin, drive_id, prefix]
            |
            */

            $folderData = [

                // 1 - CORETAX 2026
                [1, 1, '1Bnkvb_2h8tTAC4YZC9j9Mqo1Ajka4xfg', 'assets/coretax2026/pns'],
                [1, 2, '1v2vvmFr4-LVKj1GbpVnzxE9cvpXZbYSs', 'assets/coretax2026/pppk'],
                [1, 3, '10A_dFsO4QtemcVT6AmBAeW0AfE9d8MJI', 'assets/coretax2026/paruhwaktu'],
                [1, 4, '1uWT2TFdavqyx0MxItpBFP_S-4MGJg8B7', 'assets/coretax2026/nonasn'],

                // 2 - LAPORAN IKD
                [2, 1, '1rmbM5F6R5_hWUV3JQLQiKc3-HGh-wXtE', 'assets/laporanikd/pns'],
                [2, 2, '19B-MVQct1GpH2UsPwGpx9wLed56BX_eJ', 'assets/laporanikd/pppk'],
                [2, 3, '1bjKQgpRB7pMB9lB1Xktyf4eEJXLAS95m', 'assets/laporanikd/paruhwaktu'],
                [2, 4, '1wX7-LpskaOHPRh1ByLp8OoHYdI67JwEK', 'assets/laporanikd/nonasn'],

                // 3 - EVKIN 2026 TW I
                [3, 1, '1chWJwMT7EJjSKwYcfLVJrqeK5oMxGCZu', 'assets/2026/evkin/tw1/pns'],
                [3, 2, '1gpyU0zxb99qZvVcIvuO4qqz9E3ljogFJ', 'assets/2026/evkin/tw1/pppk'],
                [3, 3, '1mwwSrjPyOgh-Oku5VGxq_TjijSoErSOE', 'assets/2026/evkin/tw1/paruhwaktu'],
                [3, 4, '17mGlcZGP-5QoRMhGE5ZY3VGe3qx1At_B', 'assets/2026/evkin/tw1/pjlp'],

                // 4 - UMPAN BALIK 2026 TW I
                [4, 1, '1XM_1lJbyAKJ59Bjdu64i1xznmfALhikg', 'assets/umbal_2026_tw1/pns'],
                [4, 2, '1LQ6_f3j01Ckqhin5_FiJzTZtA1V87kS6', 'assets/umbal_2026_tw1/pppk'],
                [4, 3, '10Eyl2PhD5DwmlgVybREP9eoboiL7jx77', 'assets/umbal_2026_tw1/paruhwaktu'],
                [4, 4, '1RSBrqjx9D--hT4jlAfbbNYPBTl_j1sFI', 'assets/umbal_2026_tw1/pjlp'],

                // 5 - MODEL C 2025
                [5, 1, '15VtRNPfq38eA8O-9fQe-VwM7BIe6bpFV', 'assets/modelc2025/pns'],
                [5, 2, '15uiuETdBwQY9xaiviShq-E7Pd5zI4FRh', 'assets/modelc2025/pppk'],
                [5, 3, '1syPeSzUfMbbernohE5GjmVgWd96rQcjI', 'assets/modelc2025/paruhwaktu'],
                [5, 4, '19eawOb_JhuHJ-OEsG_hzgLWhA3UcCmxK', 'assets/modelc2025/nonasn'],

                // 6 - MODEL C 2026
                [6, 1, '1iPxuamuwSVOFyBv39VHW9pax9NVeud4_', 'assets/modelc2026/pns'],
                [6, 2, '1dRRYgbWfGIRsltoWvF2VT2nFUZkw6Vu8', 'assets/modelc2026/pppk'],
                [6, 3, '1e34Zj4IaRMh5Lb8JIAb01Vg3bL1UZkqp', 'assets/modelc2026/paruhwaktu'],
                [6, 4, '1n8KTScpHwv2ueiPRYBnvqn96eE_uw7cH', 'assets/modelc2026/nonasn'],

                // 7 - EVKIN LAMA TW I
                [7, 1, '1uyaZXov70QRSV5h8wkaXzKBvY3LF512Y', 'assets/evkintw1/pns'],
                [7, 2, '12q8Bzii9Hx9kboTiS3DJfLvCYexu0GG3', 'assets/evkintw1/pppk'],
                [7, 3, '1NF2Br_KKUqpjoE3SyhFNm0_Lad9LGgpa', 'assets/evkintw1/paruhwaktu'],
                [7, 4, '1xXt-m4eXRm9SSwUnYXD2lYa8IYnxESVS', 'assets/evkintw1/nonasn'],

                // 8 - EVKIN LAMA TW II
                [8, 1, '1QNeg-Cb2kMGKehxAQ7Jpuima4_kssDg9', 'assets/evkintw2/pns'],
                [8, 2, '1DQBoHJ9vyA1gh8YUsSm1J88-MuNMCSa1', 'assets/evkintw2/pppk'],
                [8, 3, '1u-Zx83v7Mah0S05tMTxSxpLuPPL0uUC2', 'assets/evkintw2/paruhwaktu'],
                [8, 4, '1fS1vp1VUl5MafTLIo2AL3_0mthytoyLg', 'assets/evkintw2/nonasn'],

                // 9 - EVKIN LAMA TW III
                [9, 1, '1XFwh0IkJTfCDlgsAvg_rToABfjtlOBkx', 'assets/evkintw3/pns'],
                [9, 2, '1DT86UoJTtuYCgf0biU-fwdUWrp6lGb8e', 'assets/evkintw3/pppk'],
                [9, 3, '1gD7gm581jqYOyXleAhsWJeBjfkzECEDE', 'assets/evkintw3/paruhwaktu'],
                [9, 4, '1jm8YchDiPrB7olTeEODaLpYzVRJAB7tR', 'assets/evkintw3/nonasn'],

                // 10 - EVKIN LAMA TW IV
                [10, 1, '1PKQ4e7wGotF6tDraFjza_ruI5pw4ebGo', 'assets/evkintw4/pns'],
                [10, 2, '1PqOsbH7rH05KWls1J-ztRddb-7StVLte', 'assets/evkintw4/pppk'],

                // 11 - UMPAN BALIK LAMA TW I
                [11, 1, '1GdPCz7vN-2rjNHL84qjr-Juxb4YfWb6H', 'assets/2025/umbal/tw1/pns'],
                [11, 2, '1G8x_ciQmpGhxqxkCIewZ_RA8eSNBJ4ua', 'assets/2025/umbal/tw1/pppk'],

                // 12 - UMPAN BALIK LAMA TW II
                [12, 1, '1JqF7x_bro1r1B-Sy76xesqEiCU1W2ApG', 'assets/2025/umbal/tw2/pns'],
                [12, 2, '105htvYSvUq1mrM3cF8Ei9vSWX9TkAltr', 'assets/2025/umbal/tw2/pppk'],

                // 13 - UMPAN BALIK LAMA TW III
                [13, 1, '1qqtWyhCVRChGr1FYlDwxLaI-odkUTagk', 'assets/2025/umbal/tw3/pns'],
                [13, 2, '1Vzf91MeKHRxYH5TPPtEGhVoun6Tk7vU3', 'assets/2025/umbal/tw3/pppk'],

                // 14 - UMPAN BALIK LAMA TW IV
                [14, 1, '18dlCDByEfptCEIYmSegX1WyhKE9w3xGx', 'assets/2025/umbal/tw4/pns'],
                [14, 2, '1vmZOok_7fAjO2xEi_a36aYYBmN4c_Gey', 'assets/2025/umbal/tw4/pppk'],

                // 15 - EVKIN TAHUNAN 2025
                [15, 1, '1_LUM3L5BcbKN7lXHESOwXnfxiSvJYqOw', 'assets/2025/evkintahunan/pns'],
                [15, 2, '1ZpCVTN4iEU2CFl4b6d_pbWLqM6FwG8Ya', 'assets/2025/evkintahunan/pppk'],

                // 16 - PERJANJIAN KINERJA 2026
                [16, 1, '17cFcbVqLRybUxZ5N2r-v5LjpW_eXq624', 'assets/perjanjiankinerja2026/pns'],
                [16, 2, '1jb1zlgfsrykYojScNuwGdvciTklAB1k2', 'assets/perjanjiankinerja2026/pppk'],
                [16, 3, '1wbEef508cEoazAg2BkRNCugZ4q-hTeV0', 'assets/perjanjiankinerja2026/paruhwaktu'],
                [16, 4, '1z_7f5-r7Iy2P-B3DLgF2GfjOEUBB5nnA', 'assets/perjanjiankinerja2026/nonasn'],

                // 17 - SKP 2025
                [17, 1, '1EBa1iDLlqE-bjwgO-cnnxHe1dhjnM-5B', 'assets/skp2025/pns'],
                [17, 2, '1jr6aUODpoGrfvgpT45pbZlXBw_TkFZKD', 'assets/skp2025/pppk'],

                // 18 - KTP
                [18, 1, '1G4VgYnXhx0d3pQmUsn-2TdHtwr2jfPvD', 'assets/dataktp/pns'],
                [18, 2, '1phXsVR3YKWQfat3XNQnMkAl3M7FC3WCV', 'assets/dataktp/pppk'],
                [18, 3, '1QzRJutHFuc6pp8C4HV82qZlMlVHo7iIb', 'assets/dataktp/paruhwaktu'],
                [18, 4, '1TZvWI5yduE-2ZnmWGoNitNJ33J9Cj2Kj', 'assets/dataktp/nonasn'],

                // 19 - SKP 2026
                [19, 1, '1gkX9UNQs06HyZOoqIEdqErNUFdMf8dw2', 'assets/2026/skp2026/pns'],
                [19, 2, '1Zn2PaAvYdraYGggo9QsaKEihWB5iiusp', 'assets/2026/skp2026/pppk'],
                [19, 3, '1nTDq6uhMwlMM6PXV1IUtt5XGZPVep9r4', 'assets/2026/skp2026/paruhwaktu'],
                [19, 4, '1gI7BJrxrnAXI8mKxUvvHeYCNBYrgAWmw', 'assets/2026/skp2026/pjlp'],

                // 20 - RENAKSI 2026
                [20, 1, '18S9Le7iocPVykSUaBneYETLaf2uA3kNg', 'assets/2026/renaksi2026/pns'],
                [20, 2, '1_nOTH7a-trkZZFUUGLy-Q7x7jr76oh2k', 'assets/2026/renaksi2026/pppk'],
                [20, 3, '1H_FGjbAXeSCrluDJVnGwEYL9qljlEm0A', 'assets/2026/renaksi2026/paruhwaktu'],
                [20, 4, '1GEs8ntfRA6VSo8HjpXJkw9zW-iWHoek0', 'assets/2026/renaksi2026/pjlp'],

                // 21 - NPWP
                [21, 1, '1uR_Laz-Tc7powHhTDsKz8TMzNMY2e4NS', 'assets/datanpwp/pns'],
                [21, 2, '1pExNEG3YkbYIxYKcX_BsecwoIS6vVb3s', 'assets/datanpwp/pppk'],
                [21, 3, '1QVjGf56fQb8ubVTflQMHp_aJJXQXqcZY', 'assets/datanpwp/paruhwaktu'],
                [21, 4, '1GhzGBTtX9ROzXvbhk8nHElihom-wvFBb', 'assets/datanpwp/nonasn'],

                // 22 - BUKU REKENING
                [22, 1, '13X8SXtPMf4d_7aewCwuSn-JswtFetlVO', 'assets/databukurekening/pns'],
                [22, 2, '130kF1lSee-8Rt6J8brq5asAMhMETZen2', 'assets/databukurekening/pppk'],
                [22, 3, '1mFgmnfQIMRuwijFli1tfSPJr6jJW55n0', 'assets/databukurekening/paruhwaktu'],
                [22, 4, '1zgIVsnhKvX0khV8ZXO0OqPYkYAl3lGoT', 'assets/databukurekening/nonasn'],

                // 23 - BPJS
                [23, 1, '1gRopXyOXpZsIRY7K0JzEHnA4JJfciLxy', 'assets/databpjskesehatan/pns'],
                [23, 2, '1Pe-1B3Y4lEMrxkMH_fUxAP7obxbvfLN_', 'assets/databpjskesehatan/pppk'],
                [23, 3, '1ITcLSLoPIsmLF-2Qao-RHzD1v9y36QLX', 'assets/databpjskesehatan/paruhwaktu'],
                [23, 4, '1eBYW96WEJyxI5wX-E7cf4pwG1kj0fs7A', 'assets/databpjskesehatan/nonasn'],

                // 24 - KARTU KELUARGA
                [24, 1, '1tD-wl3q0lQ3YoIFryam-hec14j-D4S1o', 'assets/datakartukeluarga/pns'],
                [24, 2, '1tNI1ieKpg2hkOiYDKQ3Jm_SOY8AJfVYb', 'assets/datakartukeluarga/pppk'],
                [24, 3, '1qtO3HjHuQdlFyjW7o4_BkO3M4QEgDLm2', 'assets/datakartukeluarga/paruhwaktu'],
                [24, 4, '1bWxdALd0mhvy1OV8rccZYiLlvtAo6Ldw', 'assets/datakartukeluarga/nonasn'],

                // 25 - IJAZAH
                [25, 1, '1EU3RYJo3WaDOSQElEA3ObOyP9L3p55Mj', 'assets/dataijazah/pns'],
                [25, 2, '1Jd2pza1Ssb6xyoRRbsZHOd3Z-tSkfL4A', 'assets/dataijazah/pppk'],
                [25, 3, '1DcW31gOB3L9OhCIElO_eqBy3Wy5e2LB2', 'assets/dataijazah/paruhwaktu'],
                [25, 4, '1WIwKh8S3PnKpg5jtuWMv8yzqQrcfApzR', 'assets/dataijazah/nonasn'],

                // 26 - PAKTA INTEGRITAS
                [26, 1, '1gn625Xj0b-021ZqbME2ml-OoHKoDM8K4', 'assets/2025/paktaintegritas/pns'],
                [26, 2, '1lYscQwL53lMlQu0zHpUt0bSnJME5XI9V', 'assets/2025/paktaintegritas/pppk'],
                [26, 3, '1l19yqKm_u1fnwt6Z6WjMcSaGve1wpion', 'assets/2025/paktaintegritas/paruhwaktu'],
                [26, 4, '1E6ecKZBrB-FU_x0bMVPn4jcnoKlr98l7', 'assets/2025/paktaintegritas/nonasn'],

                // 27 - PAKTA INTEGRITAS 1 DESEMBER
                [27, 1, '1j8fQap2zgK4BdSaQr7mzgeR47mrGJ46h', 'assets/2025/paktaintegritas1desember/pns'],
                [27, 2, '1JJXpKuUdk9B2YE5ojS9wpMCCdNElzUa6', 'assets/2025/paktaintegritas1desember/pppk'],
                [27, 3, '1xIJt9q6ntgx9cEpJj47blprtNonoTR7H', 'assets/2025/paktaintegritas1desember/paruhwaktu'],
                [27, 4, '1Z2_7_PUK0JTKjiGIRlBDnvbGxC1rvkyU', 'assets/2025/paktaintegritas1desember/nonasn'],

                // 29 - EVKIN 2026 TW II
                [29, 1, '1NRohZhLm7_1_3oQW2S4rCddbnmD76QwB', 'assets/2026/evkin/tw2/pns'],
                [29, 2, '1C8PYQoM92c-4KYPxf8brZNh4VFSkAmFD', 'assets/2026/evkin/tw2/pppk'],
                [29, 3, '1a-UNbh7LWXWqXAJRucY8QTqvjtL8Qa4F', 'assets/2026/evkin/tw2/paruhwaktu'],

                // 30 - UMPAN BALIK 2026 TW II
                [30, 1, '12mJ5K8IwbgZ-3pBIWq1rAAefvZdXVFFW', 'assets/2026/umpan/tw2/pns'],
                [30, 2, '15YVpyxmG3wEUT72Nd-bRmwYyttOCwWAv', 'assets/2026/umpan/tw2/pppk'],
                [30, 3, '1ezi6kRKC9MvepfRALV2GZoEeUwCSr-7X', 'assets/2026/umpan/tw2/paruhwaktu'],

                // 31 - PAKTA INTEGRITAS 2026
                [31, 1, '1Z2Irro9Ez-Om6Fsqp6ZIyMHPLxocZ3hp', 'assets/2026/pakta/pns'],
                [31, 2, '1MHlt41AC1IpffnbDiswVjkZtGndg_mPI', 'assets/2026/pakta/pppk'],
                [31, 3, '1r9tOhxnajkISsmxoa0k4k7By1wWYvehB', 'assets/2026/pakta/pppkpw'],
                [31, 4, '1mNMVk4YrYWonXTwAdLsPI-JNxSqkdTqH', 'assets/2026/pakta/pjlp'],
            ];


            /*
            |--------------------------------------------------------------------------
            | 7. REGISTER FOLDER + TARGET
            |--------------------------------------------------------------------------
            */

            $folderCount = 0;
            $targetCount = 0;

            foreach ($folderData as [$mappingTombol, $sadarinJenisKerja, $driveId, $prefix]) {

                if (!isset($mappingPermintaan[$mappingTombol])) {
                    continue;
                }

                $permintaanKode =
                    $mappingPermintaan[$mappingTombol];

                if (!isset($permintaanMap[$permintaanKode])) {
                    throw new \RuntimeException(
                        'Permintaan "' .
                        $permintaanKode .
                        '" tidak ditemukan.'
                    );
                }

                $jenisKerjaId =
                    $jenisKerjaMap[$sadarinJenisKerja];

                $jenisKerja =
                    SamperinJenisKerja::find($jenisKerjaId);

                if (!$jenisKerja) {
                    throw new \RuntimeException(
                        'Jenis kerja ID ' .
                        $jenisKerjaId .
                        ' tidak ditemukan.'
                    );
                }

                /*
                 * Nama folder di SAMPERIN.
                 *
                 * Drive ID tetap menggunakan folder Drive lama.
                 */

                $folderNama =
                    $jenisKerja->jenis_kerja_nama .
                    ' - ' .
                    $permintaanKode;


                /*
                 * Cari berdasarkan Drive Folder ID.
                 *
                 * Jadi kalau folder sudah pernah didaftarkan,
                 * tidak dibuat duplikat.
                 */

                $folder = SamperinFolder::firstOrNew([
                    'folder_drive_id' => $driveId,
                ]);

                if (!$folder->exists) {

                    $folder->folder_uid =
                        (string) Str::uuid();

                    $folder->folder_created_at =
                        now();

                    $folderCount++;
                }

                $folder->folder_kode =
                    'PERMINTAAN_' .
                    $mappingTombol .
                    '_' .
                    $sadarinJenisKerja;

                $folder->folder_nama =
                    $folderNama;

                $folder->folder_jenis =
                    'berkas';

                $folder->folder_jenis_kerja_id =
                    $jenisKerjaId;

                $folder->folder_prefix =
                    $prefix;

                $folder->folder_status =
                    true;

                $folder->folder_updated_at =
                    now();

                $folder->save();


                /*
                 * Target permintaan.
                 *
                 * Satu target = satu jenis kerja + satu folder Drive.
                 */

                SamperinPermintaanTarget::create([
                    'target_uid' =>
                        (string) Str::uuid(),

                    'target_permintaan_id' =>
                        $permintaanMap[$permintaanKode],

                    'target_tipe' =>
                        'JENIS_KERJA',

                    'target_jenis_kerja_id' =>
                        $jenisKerjaId,

                    'target_folder_id' =>
                        $folder->folder_id,

                    'target_status' =>
                        true,

                    'target_created_at' =>
                        now(),

                    'target_updated_at' =>
                        now(),
                ]);

                $targetCount++;
            }


            /*
            |--------------------------------------------------------------------------
            | 8. INFORMASI SEEDER
            |--------------------------------------------------------------------------
            */

            $this->command?->info('');
            $this->command?->info(
                '=============================================='
            );
            $this->command?->info(
                ' SAMPERIN BERKAS MASTER SEEDER'
            );
            $this->command?->info(
                '=============================================='
            );

            $this->command?->info(
                'Kategori : ' . count($kategoriData)
            );

            $this->command?->info(
                'Jenis    : ' . count($jenisData)
            );

            $this->command?->info(
                'Permintaan : ' . count($permintaanData)
            );

            $this->command?->info(
                'Folder baru : ' . $folderCount
            );

            $this->command?->info(
                'Target : ' . $targetCount
            );

            $this->command?->info(
                'Folder Drive existing TIDAK dihapus.'
            );

            $this->command?->info(
                '=============================================='
            );
        });
    }
}