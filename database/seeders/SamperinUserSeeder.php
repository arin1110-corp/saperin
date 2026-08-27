<?php

namespace Database\Seeders;

use App\Models\SamperinUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SamperinUserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CARI USER BERDASARKAN USER ID
        |--------------------------------------------------------------------------
        |
        | user_id 1919 merupakan ID lama dari SADARIN.
        | ID ini sengaja dipertahankan di SAMPERIN.
        |
        */

        $user = SamperinUser::where('user_id', 1919)->first();

        /*
        |--------------------------------------------------------------------------
        | USER UID
        |--------------------------------------------------------------------------
        |
        | Gunakan UUID standar.
        |
        | Contoh:
        |
        | e4a57e87-9e51-45a6-a450-cb2d1d96f347
        |
        | Jika user sudah ada, UID lama dipertahankan
        | supaya tidak berubah setiap seeder dijalankan.
        |
        */

        $userUid = $user?->user_uid ?? (string) Str::uuid();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN USER
        |--------------------------------------------------------------------------
        */

        SamperinUser::updateOrCreate(
            [
                'user_id' => 1919,
            ],
            [
                /*
                |--------------------------------------------------------------------------
                | UID
                |--------------------------------------------------------------------------
                */

                'user_uid' => $userUid,

                /*
                |--------------------------------------------------------------------------
                | IDENTITAS PEGAWAI
                |--------------------------------------------------------------------------
                */

                'user_nip' => '199510112020121001',

                'user_nik' => '5103031110950001',

                'user_nama' => 'I PUTU INDRA ARDIKA PUTRA',

                'user_gelardepan' => null,

                'user_gelarbelakang' => 'S.Kom',

                'user_tempatlahir' => 'Denpasar',

                'user_tgllahir' => '1995-10-11',

                'user_jk' => 'L',

                /*
                |--------------------------------------------------------------------------
                | MASTER KEPEGAWAIAN
                |--------------------------------------------------------------------------
                |
                | Sementara NULL karena master SAMPERIN
                | belum dimigrasikan.
                |
                | ID berikut adalah ID dari SADARIN:
                |
                | Jabatan     = 52
                | Bidang      = 1
                | Golongan    = 10
                | Eselon      = 9
                | Pendidikan  = 65
                | Jenis Kerja = 1
                |
                | JANGAN memasukkan ID tersebut langsung
                | ke FK SAMPERIN.
                |
                */

                'user_jabatan_id' => null,

                'user_bidang_id' => null,

                'user_golongan_id' => null,

                'user_eselon_id' => null,

                'user_pendidikan_id' => null,

                'user_jenis_kerja_id' => null,

                /*
                |--------------------------------------------------------------------------
                | DATA KEPEGAWAIAN
                |--------------------------------------------------------------------------
                */

                'user_tmt' => '2025-12-01',

                'user_spmt' => '2021-01-02',

                'user_npwp' => '83.626.966.2-906.000',

                'user_bpjs' => '0000142569448',

                'user_norek_bpd' => '0100215119001',

                /*
                |--------------------------------------------------------------------------
                | KELAS JABATAN
                |--------------------------------------------------------------------------
                |
                | Nilai berupa angka.
                |
                */

                'user_kelasjabatan' => 8,

                /*
                |--------------------------------------------------------------------------
                | JUMLAH TANGGUNGAN
                |--------------------------------------------------------------------------
                */

                'user_jmltanggungan' => 0,

                /*
                |--------------------------------------------------------------------------
                | KONTAK
                |--------------------------------------------------------------------------
                */

                'user_email' => 'indraardika@gmail.com',

                'user_notelp' => '081246205273',

                'user_alamat' => 'BR.KERAMAN ABIANSEMAL BADUNG',

                'user_lokasikerja' => 'Kantor Dinas Kebudayaan Provinsi Bali',

                'user_keterangan' => 'Aktif',

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                |
                | 1 = Aktif
                | 0 = Nonaktif
                |
                */

                'user_status' => 1,
            ],
        );
    }
}