<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinFolder;
use App\Models\SamperinJenisBerkas;
use App\Models\SamperinJenisKerja;
use App\Models\SamperinPermintaanBerkas;
use App\Models\SamperinPermintaanTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SamperinPermintaanBerkasController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Menampilkan seluruh permintaan berkas yang sudah dibuat.
    |
    */

    public function index()
    {
        $permintaan = SamperinPermintaanBerkas::query()
            ->with(['jenisBerkas.kategori', 'target.jenisKerja', 'target.folder'])
            ->where('permintaan_status', true)
            ->orderByDesc('permintaan_tahun')
            ->orderByDesc('permintaan_created_at')
            ->orderBy('permintaan_judul')
            ->paginate(10)
            ->withQueryString();
        $jenisBerkas = SamperinJenisBerkas::query()->where('jenis_berkas_status', true)->orderBy('jenis_berkas_nama')->get();

        $jenisKerja = SamperinJenisKerja::query()->where('jenis_kerja_status', true)->orderBy('jenis_kerja_nama')->get();

        $folders = SamperinFolder::query()->where('folder_status', true)->orderBy('folder_nama')->get();


        return view('dashboard.admin.permintaan-berkas.index', compact('permintaan', 'jenisBerkas', 'jenisKerja', 'folders'));
    }
    /**
     * Form membuat permintaan berkas.
     */
    public function create()
    {
        $jenisBerkas = SamperinJenisBerkas::query()->where('jenis_berkas_status', true)->orderBy('jenis_berkas_nama')->get();

        $jenisKerja = SamperinJenisKerja::query()->where('jenis_kerja_status', true)->orderBy('jenis_kerja_nama')->get();

        $folders = SamperinFolder::query()->where('folder_status', true)->orderBy('folder_nama')->get();

        return view('dashboard.admin.permintaan-berkas.create', compact('jenisBerkas', 'jenisKerja', 'folders'));
    }

    /**
     * Simpan permintaan berkas.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'permintaan_jenis_berkas_id' => ['required', 'integer', 'exists:samperin_jenis_berkas,jenis_berkas_id'],

            'permintaan_tahun' => ['required', 'integer', 'min:2000', 'max:2100'],

            'permintaan_periode' => ['nullable', 'string', 'max:100'],

            'permintaan_judul' => ['required', 'string', 'max:255'],

            'permintaan_tombol' => ['required', 'string', 'max:100'],

            'permintaan_mulai' => ['required', 'date'],

            'permintaan_expired' => ['required', 'date', 'after_or_equal:permintaan_mulai'],

            'permintaan_keterangan' => ['nullable', 'string'],

            'jenis_kerja' => ['required', 'array', 'min:1'],

            'jenis_kerja.*' => ['required', 'integer', 'distinct', 'exists:samperin_jenis_kerja,jenis_kerja_id'],

            'folder_id' => ['required', 'array', 'min:1'],

            'folder_id.*' => ['required', 'integer', 'distinct', 'exists:samperin_folder,folder_id'],
        ]);

        $jenisKerjaIds = $validated['jenis_kerja'];
        $folderIds = $validated['folder_id'];

        if (count($jenisKerjaIds) !== count($folderIds)) {
            throw ValidationException::withMessages([
                'folder_id' => 'Folder untuk setiap jenis kerja wajib dipilih.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi jenis kerja
        |--------------------------------------------------------------------------
        */

        $jenisKerjaAktif = SamperinJenisKerja::query()->where('jenis_kerja_status', true)->whereIn('jenis_kerja_id', $jenisKerjaIds)->get()->keyBy('jenis_kerja_id');

        if ($jenisKerjaAktif->count() !== count($jenisKerjaIds)) {
            throw ValidationException::withMessages([
                'jenis_kerja' => 'Terdapat jenis kerja yang tidak aktif atau tidak valid.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil folder yang dipilih
        |--------------------------------------------------------------------------
        */

        $folders = SamperinFolder::query()->where('folder_status', true)->where('folder_jenis', 'berkas')->whereIn('folder_id', $folderIds)->get()->keyBy('folder_id');

        if ($folders->count() !== count($folderIds)) {
            throw ValidationException::withMessages([
                'folder_id' => 'Terdapat folder yang tidak aktif atau bukan folder berkas.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi folder harus sesuai jenis kerja
        |--------------------------------------------------------------------------
        */

        foreach ($jenisKerjaIds as $index => $jenisKerjaId) {
            $folderId = $folderIds[$index];

            $folder = $folders->get($folderId);

            if (!$folder) {
                throw ValidationException::withMessages([
                    'folder_id' => 'Folder pada baris ' . ($index + 1) . ' tidak ditemukan.',
                ]);
            }

            if ((int) $folder->folder_jenis_kerja_id !== (int) $jenisKerjaId) {
                throw ValidationException::withMessages([
                    'folder_id' => sprintf('Folder "%s" tidak sesuai dengan jenis kerja yang dipilih.', $folder->folder_nama),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan dalam transaction
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($validated, $jenisKerjaIds, $folderIds) {
            $permintaan = SamperinPermintaanBerkas::create([
                'permintaan_uid' => (string) Str::uuid(),

                'permintaan_jenis_berkas_id' => $validated['permintaan_jenis_berkas_id'],

                'permintaan_tahun' => $validated['permintaan_tahun'],

                'permintaan_periode' => $validated['permintaan_periode'] ?? null,

                'permintaan_judul' => $validated['permintaan_judul'],

                'permintaan_tombol' => $validated['permintaan_tombol'],

                'permintaan_mulai' => $validated['permintaan_mulai'],

                'permintaan_expired' => $validated['permintaan_expired'],

                'permintaan_keterangan' => $validated['permintaan_keterangan'] ?? null,

                'permintaan_status' => true,

                'permintaan_created_at' => now(),
                'permintaan_updated_at' => now(),
            ]);

            foreach ($jenisKerjaIds as $index => $jenisKerjaId) {
                SamperinPermintaanTarget::create([
                    'target_uid' => (string) Str::uuid(),

                    'target_permintaan_id' => $permintaan->permintaan_id,

                    'target_tipe' => 'JENIS_KERJA',

                    'target_jenis_kerja_id' => $jenisKerjaId,

                    'target_folder_id' => $folderIds[$index],

                    'target_status' => true,

                    'target_created_at' => now(),
                    'target_updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('rekap.berkas')->with('success', 'Permintaan berkas berhasil dibuat.');
    }
}