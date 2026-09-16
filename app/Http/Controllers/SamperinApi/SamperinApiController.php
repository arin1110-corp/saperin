<?php

namespace App\Http\Controllers\SamperinApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SamperinApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    |
    | SAPLARIN tetap mengirim:
    |
    | {
    |     "nip": "...",
    |     "password": "..."
    | }
    |
    */

    public function login(Request $request)
    {
        $nip = $request->json('nip') ?? $request->input('nip');

        $password = $request->json('password') ?? $request->input('password');

        if (!$nip || !$password) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'NIP dan password wajib diisi',
                ],
                400,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CARI PEGAWAI
        |--------------------------------------------------------------------------
        |
        | Bisa login menggunakan:
        | - NIP
        | - NIK
        |
        */

        $user = DB::table('samperin_user')

            ->leftJoin('samperin_jabatan', 'samperin_user.user_jabatan_id', '=', 'samperin_jabatan.jabatan_id')

            ->leftJoin('samperin_bidang', 'samperin_user.user_bidang_id', '=', 'samperin_bidang.bidang_id')

            ->leftJoin('samperin_eselon', 'samperin_user.user_eselon_id', '=', 'samperin_eselon.eselon_id')

            ->leftJoin('samperin_pendidikan', 'samperin_user.user_pendidikan_id', '=', 'samperin_pendidikan.pendidikan_id')

            ->leftJoin('samperin_golongan', 'samperin_user.user_golongan_id', '=', 'samperin_golongan.golongan_id')

            ->leftJoin('samperin_jenis_kerja', 'samperin_user.user_jenis_kerja_id', '=', 'samperin_jenis_kerja.jenis_kerja_id')

            ->where(function ($q) use ($nip) {
                $q->where(function ($q2) use ($nip) {
                    $q2->where('samperin_user.user_nip', '!=', '-')->where('samperin_user.user_nip', $nip);
                })->orWhere('samperin_user.user_nik', $nip);
            })

            ->select(
                'samperin_user.*',

                'samperin_jabatan.jabatan_nama',
                'samperin_jabatan.jabatan_id',

                'samperin_bidang.bidang_id',
                'samperin_bidang.bidang_nama',

                'samperin_eselon.eselon_nama',

                'samperin_pendidikan.pendidikan_jenjang',
                'samperin_pendidikan.pendidikan_jurusan',

                'samperin_golongan.golongan_nama',
                'samperin_golongan.golongan_pangkat',

                'samperin_jenis_kerja.jenis_kerja_nama',
            )

            ->first();

        /*
        |--------------------------------------------------------------------------
        | CEK LOGIN
        |--------------------------------------------------------------------------
        */

        if (!$user || !Hash::check($password, $user->user_password)) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Login gagal',
                ],
                401,
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        |
        | Struktur dibuat SAMA dengan API SADARIN
        | agar SAPLARIN tidak perlu diubah.
        |
        */

        return response()->json([
            'status' => true,

            'data' => [
                'id' => $user->user_id,

                'nama' => $user->user_nama,

                'nip' => $user->user_nip,

                'jabatan' => $user->jabatan_nama,

                'jabatan_id' => $user->jabatan_id,

                'bidang_id' => $user->bidang_id,

                'bidang' => $user->bidang_nama,

                'eselon' => $user->eselon_nama,

                'email' => $user->user_email,

                'hp' => $user->user_notelp,

                'pendidikan_jenjang' => $user->pendidikan_jenjang,

                'pendidikan_jurusan' => $user->pendidikan_jurusan,

                'golongan_nama' => $user->golongan_nama,

                'golongan_pangkat' => $user->golongan_pangkat,

                'jeniskerja' => $user->jenis_kerja_nama,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SEMUA PEGAWAI
    |--------------------------------------------------------------------------
    |
    | Response tetap sama dengan API SADARIN.
    |
    */

    public function allPegawai()
    {
        $pegawai = DB::table('samperin_user')

            ->leftJoin('samperin_jabatan', 'samperin_user.user_jabatan_id', '=', 'samperin_jabatan.jabatan_id')

            ->leftJoin('samperin_bidang', 'samperin_user.user_bidang_id', '=', 'samperin_bidang.bidang_id')

            ->leftJoin('samperin_eselon', 'samperin_user.user_eselon_id', '=', 'samperin_eselon.eselon_id')

            ->leftJoin('samperin_pendidikan', 'samperin_user.user_pendidikan_id', '=', 'samperin_pendidikan.pendidikan_id')

            ->leftJoin('samperin_golongan', 'samperin_user.user_golongan_id', '=', 'samperin_golongan.golongan_id')

            ->leftJoin('samperin_jenis_kerja', 'samperin_user.user_jenis_kerja_id', '=', 'samperin_jenis_kerja.jenis_kerja_id')

            ->select(
                'samperin_user.user_id as id',

                'samperin_user.user_nama as nama',

                'samperin_user.user_nip as nip',

                'samperin_user.user_nik as nik',

                'samperin_user.user_email as email',

                'samperin_user.user_notelp as hp',

                'samperin_user.user_jabatan_id as jabatan_id',

                'samperin_user.user_bidang_id as bidang_id',

                'samperin_jabatan.jabatan_nama as jabatan',

                'samperin_bidang.bidang_nama as bidang',

                'samperin_eselon.eselon_nama as eselon',

                'samperin_pendidikan.pendidikan_jenjang',

                'samperin_pendidikan.pendidikan_jurusan',

                'samperin_golongan.golongan_nama',

                'samperin_golongan.golongan_pangkat',

                'samperin_jenis_kerja.jenis_kerja_nama as jeniskerja',
            )

            ->orderBy('samperin_user.user_nama', 'asc')

            ->get();

        return response()->json([
            'status' => true,

            'total' => $pegawai->count(),

            'data' => $pegawai,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SEMUA BIDANG
    |--------------------------------------------------------------------------
    */

    public function allBidang()
    {
        $bidang = DB::table('samperin_bidang')

            ->select('bidang_id as id', 'bidang_nama as nama')

            ->orderBy('bidang_nama', 'asc')

            ->get();

        return response()->json([
            'status' => true,

            'total' => $bidang->count(),

            'data' => $bidang,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PEGAWAI BERDASARKAN ID
    |--------------------------------------------------------------------------
    |
    | Tetap menggunakan user_id sebagai ID
    | karena SAPLARIN sudah menggunakan ID tersebut.
    |
    */

    public function pegawaiByID($id)
    {
        $pegawai = DB::table('samperin_user as u')

            ->leftJoin('samperin_jabatan as j', 'j.jabatan_id', '=', 'u.user_jabatan_id')

            ->leftJoin('samperin_bidang as b', 'b.bidang_id', '=', 'u.user_bidang_id')

            ->select(
            // =====================================================
            // ID
            // =====================================================
            'u.user_id',

            // =====================================================
            // IDENTITAS
            // =====================================================
            'u.user_nama',
            'u.user_nip',
            'u.user_nik',
            'u.user_email',

            // =====================================================
            // JABATAN
            // Field lama TETAP dipertahankan
            // =====================================================
            'u.user_jabatan_id as user_jabatan',
            'j.jabatan_nama as user_jabatan_nama',

            // =====================================================
            // BIDANG
            // Field lama TETAP dipertahankan
            // =====================================================
            'u.user_bidang_id as user_bidang',
            'b.bidang_nama as user_bidang_nama',

            // =====================================================
            // DATA TAMBAHAN
            // Tambahkan sesuai kebutuhan SAMPERIN
            // =====================================================
            'u.user_status',
            'u.user_golongan_id',
            'u.user_eselon_id',
            'u.user_pendidikan_id',
            'u.user_jenis_kerja_id',
            )

            ->where('u.user_id', $id)

            ->first();

        if (!$pegawai) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Pegawai tidak ditemukan',
                ],
                404,
            );
        }

        return response()->json([
            'success' => true,
            'data' => $pegawai,
        ]);
    }
}