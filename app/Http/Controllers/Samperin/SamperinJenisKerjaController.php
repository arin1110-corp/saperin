<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinJenisKerja;
use App\Models\SamperinUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SamperinJenisKerjaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SamperinJenisKerja::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('jenis_kerja_kode', 'like', '%' . $search . '%')->orWhere('jenis_kerja_nama', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('jenis_kerja_status', (int) $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $jenisKerjas = $query->orderBy('jenis_kerja_id')->paginate(5)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalJenisKerja = SamperinJenisKerja::count();

        $jenisKerjaAktif = SamperinJenisKerja::where('jenis_kerja_status', 1)->count();

        $jenisKerjaNonaktif = SamperinJenisKerja::where('jenis_kerja_status', 0)->count();

        return view('dashboard.master.jenis-kerja', compact('jenisKerjas', 'totalJenisKerja', 'jenisKerjaAktif', 'jenisKerjaNonaktif'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'jenis_kerja_kode' => ['required', 'string', 'max:20'],

                'jenis_kerja_nama' => ['required', 'string', 'max:100'],

                'jenis_kerja_status' => ['required', 'in:0,1'],
            ],
            [
                'jenis_kerja_kode.required' => 'Kode jenis kerja wajib diisi.',

                'jenis_kerja_kode.max' => 'Kode jenis kerja maksimal 20 karakter.',

                'jenis_kerja_nama.required' => 'Nama jenis kerja wajib diisi.',

                'jenis_kerja_nama.max' => 'Nama jenis kerja maksimal 100 karakter.',

                'jenis_kerja_status.required' => 'Status jenis kerja wajib dipilih.',

                'jenis_kerja_status.in' => 'Status jenis kerja tidak valid.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | NORMALISASI
        |--------------------------------------------------------------------------
        */

        $kode = trim($validated['jenis_kerja_kode']);

        $nama = trim($validated['jenis_kerja_nama']);

        /*
        |--------------------------------------------------------------------------
        | CEK KODE DUPLIKAT
        |--------------------------------------------------------------------------
        */

        if (SamperinJenisKerja::where('jenis_kerja_kode', $kode)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'jenis_kerja_kode' => 'Kode jenis kerja tersebut sudah digunakan.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK NAMA DUPLIKAT
        |--------------------------------------------------------------------------
        */

        if (SamperinJenisKerja::where('jenis_kerja_nama', $nama)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'jenis_kerja_nama' => 'Nama jenis kerja tersebut sudah digunakan.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        try {
            SamperinJenisKerja::create([
                'jenis_kerja_uid' => (string) Str::uuid(),

                'jenis_kerja_kode' => $kode,

                'jenis_kerja_nama' => $nama,

                'jenis_kerja_status' => (int) $validated['jenis_kerja_status'],
            ]);

            return redirect()->route('master.jenis-kerja.index')->with('success', 'Jenis kerja berhasil ditambahkan.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN JENIS KERJA STORE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'jenis_kerja' => 'Gagal menambahkan jenis kerja: ' . $e->getMessage(),
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
        $jenisKerja = SamperinJenisKerja::where('jenis_kerja_uid', $uid)->firstOrFail();

        $validated = $request->validate(
            [
                'jenis_kerja_kode' => ['required', 'string', 'max:20'],

                'jenis_kerja_nama' => ['required', 'string', 'max:100'],

                'jenis_kerja_status' => ['required', 'in:0,1'],
            ],
            [
                'jenis_kerja_kode.required' => 'Kode jenis kerja wajib diisi.',

                'jenis_kerja_nama.required' => 'Nama jenis kerja wajib diisi.',

                'jenis_kerja_status.required' => 'Status jenis kerja wajib dipilih.',

                'jenis_kerja_status.in' => 'Status jenis kerja tidak valid.',
            ],
        );

        $kode = trim($validated['jenis_kerja_kode']);

        $nama = trim($validated['jenis_kerja_nama']);

        /*
        |--------------------------------------------------------------------------
        | CEK KODE DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $kodeExists = SamperinJenisKerja::where('jenis_kerja_kode', $kode)->where('jenis_kerja_id', '!=', $jenisKerja->jenis_kerja_id)->exists();

        if ($kodeExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'jenis_kerja_kode' => 'Kode jenis kerja tersebut sudah digunakan.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CEK NAMA DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $namaExists = SamperinJenisKerja::where('jenis_kerja_nama', $nama)->where('jenis_kerja_id', '!=', $jenisKerja->jenis_kerja_id)->exists();

        if ($namaExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'jenis_kerja_nama' => 'Nama jenis kerja tersebut sudah digunakan.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        try {
            $jenisKerja->update([
                'jenis_kerja_kode' => $kode,

                'jenis_kerja_nama' => $nama,

                'jenis_kerja_status' => (int) $validated['jenis_kerja_status'],
            ]);

            return redirect()->route('master.jenis-kerja.index')->with('success', 'Jenis kerja berhasil diperbarui.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN JENIS KERJA UPDATE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->withErrors([
                    'jenis_kerja' => 'Gagal memperbarui jenis kerja: ' . $e->getMessage(),
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
        $jenisKerja = SamperinJenisKerja::where('jenis_kerja_uid', $uid)->firstOrFail();

        $jenisKerja->jenis_kerja_status = (int) $jenisKerja->jenis_kerja_status === 1 ? 0 : 1;

        $jenisKerja->save();

        return back()->with('success', $jenisKerja->jenis_kerja_status === 1 ? 'Jenis kerja berhasil diaktifkan.' : 'Jenis kerja berhasil dinonaktifkan.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $uid)
    {
        $jenisKerja = SamperinJenisKerja::where('jenis_kerja_uid', $uid)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | CEK RELASI PEGAWAI
        |--------------------------------------------------------------------------
        |
        | Jika kolom user_jenis_kerja_id memang digunakan pada
        | samperin_user, maka data tidak boleh dihapus.
        |
        */

        $jumlahPegawai = SamperinUser::where('user_jenis_kerja_id', $jenisKerja->jenis_kerja_id)->count();

        if ($jumlahPegawai > 0) {
            return back()->withErrors([
                'jenis_kerja' => 'Jenis kerja tidak dapat dihapus karena masih digunakan oleh ' . $jumlahPegawai . ' pegawai.',
            ]);
        }

        try {
            $jenisKerja->delete();

            return back()->with('success', 'Jenis kerja berhasil dihapus.');
        } catch (Throwable $e) {
            Log::error('SAMPERIN JENIS KERJA DELETE', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'jenis_kerja' => 'Gagal menghapus jenis kerja: ' . $e->getMessage(),
            ]);
        }
    }
}