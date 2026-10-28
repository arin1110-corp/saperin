<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinApi;
use App\Models\SamperinFolder;
use App\Models\SamperinPengaturan;
use App\Models\SamperinJenisKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SamperinPengaturanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PENGATURAN SISTEM
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $pengaturan = SamperinPengaturan::orderBy('pengaturan_nama')->get();

        return view('dashboard.pengaturan.index', compact('pengaturan'));
    }

    public function pengaturanStore(Request $request)
    {
        $validated = $request->validate([
            'pengaturan_kode' => ['required', 'string', 'max:100', 'unique:samperin_pengaturan,pengaturan_kode'],
            'pengaturan_nama' => 'required|string|max:150',
            'pengaturan_nilai' => 'nullable|string',
            'pengaturan_tipe' => 'required|string|max:30',
            'pengaturan_keterangan' => 'nullable|string',
            'pengaturan_status' => 'nullable|boolean',
        ]);

        $validated['pengaturan_uid'] = (string) Str::uuid();

        $validated['pengaturan_status'] = $request->boolean('pengaturan_status');

        SamperinPengaturan::create($validated);

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil ditambahkan.');
    }

    public function pengaturanUpdate(Request $request, $uid)
    {
        $pengaturan = SamperinPengaturan::where('pengaturan_uid', $uid)->firstOrFail();

        $validated = $request->validate([
            'pengaturan_kode' => ['required', 'string', 'max:100', 'unique:samperin_pengaturan,pengaturan_kode,' . $pengaturan->pengaturan_id . ',pengaturan_id'],
            'pengaturan_nama' => 'required|string|max:150',
            'pengaturan_nilai' => 'nullable|string',
            'pengaturan_tipe' => 'required|string|max:30',
            'pengaturan_keterangan' => 'nullable|string',
            'pengaturan_status' => 'nullable|boolean',
        ]);

        $validated['pengaturan_status'] = $request->boolean('pengaturan_status');

        $pengaturan->update($validated);

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function pengaturanDelete($uid)
    {
        $pengaturan = SamperinPengaturan::where('pengaturan_uid', $uid)->firstOrFail();

        $pengaturan->delete();

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | SETTING API
    |--------------------------------------------------------------------------
    */

    public function api()
    {
        $apis = SamperinApi::orderBy('api_nama')->get();

        return view('dashboard.pengaturan.api', compact('apis'));
    }

    public function apiStore(Request $request)
    {
        $validated = $request->validate([
            'api_kode' => ['required', 'string', 'max:100', 'unique:samperin_api,api_kode'],
            'api_nama' => 'required|string|max:150',
            'api_url' => 'nullable|string|max:1000',
            'api_token' => 'nullable|string',
            'api_status' => 'nullable|boolean',
            'api_keterangan' => 'nullable|string',
        ]);

        $validated['api_uid'] = (string) Str::uuid();

        $validated['api_status'] = $request->boolean('api_status');

        SamperinApi::create($validated);

        return redirect()->route('pengaturan.api')->with('success', 'API berhasil ditambahkan.');
    }

    public function apiUpdate(Request $request, $uid)
    {
        $api = SamperinApi::where('api_uid', $uid)->firstOrFail();

        $validated = $request->validate([
            'api_kode' => ['required', 'string', 'max:100', 'unique:samperin_api,api_kode,' . $api->api_id . ',api_id'],
            'api_nama' => 'required|string|max:150',
            'api_url' => 'nullable|string|max:1000',
            'api_token' => 'nullable|string',
            'api_status' => 'nullable|boolean',
            'api_keterangan' => 'nullable|string',
        ]);

        $validated['api_status'] = $request->boolean('api_status');

        $api->update($validated);

        return redirect()->route('pengaturan.api')->with('success', 'API berhasil diperbarui.');
    }

    public function apiDelete($uid)
    {
        $api = SamperinApi::where('api_uid', $uid)->firstOrFail();

        $api->delete();

        return redirect()->route('pengaturan.api')->with('success', 'API berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | SETTING FOLDER
    |--------------------------------------------------------------------------
    */

    public function folder(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $folders = SamperinFolder::query()
            ->when($search !== '', function ($query) use ($search) {
                $like = '%' . $search . '%';

                $query->where(function ($q) use ($like) {
                    $q->where('folder_kode', 'like', $like)
                        ->orWhere('folder_nama', 'like', $like)
                        ->orWhere('folder_jenis', 'like', $like)
                        ->orWhere('folder_prefix', 'like', $like)
                        ->orWhere('folder_drive_id', 'like', $like)
                        ->orWhereIn(
                            'folder_jenis_kerja_id',
                            SamperinJenisKerja::where('jenis_kerja_nama', 'like', $like)
                                ->select('jenis_kerja_id')
                        );
                });
            })
            ->orderBy('folder_id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $jenisKerja = SamperinJenisKerja::orderBy('jenis_kerja_nama')->get();

        return view('dashboard.pengaturan.folder', compact('folders', 'jenisKerja', 'search'));
    }

    public function folderStore(Request $request)
    {
        $validated = $request->validate([
            'folder_kode' => 'required|string|max:100',

            'folder_nama' => 'required|string|max:150',

            'folder_jenis' => 'required|string|max:50',

            'folder_jenis_kerja_id' => ['required', 'integer'],

            'folder_prefix' => 'nullable|string|max:50',

            'folder_drive_id' => 'required|string|max:255',

            'folder_keterangan' => 'nullable|string',

            'folder_status' => 'nullable|boolean',
        ]);

        $exists = SamperinFolder::where('folder_kode', $request->folder_kode)->where('folder_jenis_kerja_id', $request->folder_jenis_kerja_id)->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'folder_kode' => 'Kode folder tersebut sudah digunakan untuk jenis kerja ini.',
                ]);
        }

        $validated['folder_uid'] = (string) Str::uuid();

        $validated['folder_status'] = $request->boolean('folder_status');

        SamperinFolder::create($validated);

        return redirect()->route('pengaturan.folder')->with('success', 'Folder berhasil ditambahkan.');
    }

    public function folderUpdate(Request $request, $uid)
    {
        $folder = SamperinFolder::where('folder_uid', $uid)->firstOrFail();

        $validated = $request->validate([
            'folder_kode' => 'required|string|max:100',

            'folder_nama' => 'required|string|max:150',

            'folder_jenis' => 'required|string|max:50',

            'folder_jenis_kerja_id' => ['required', 'integer'],

            'folder_prefix' => 'nullable|string|max:50',

            'folder_drive_id' => 'required|string|max:255',

            'folder_keterangan' => 'nullable|string',

            'folder_status' => 'nullable|boolean',
        ]);

        $exists = SamperinFolder::where('folder_kode', $request->folder_kode)->where('folder_jenis_kerja_id', $request->folder_jenis_kerja_id)->where('folder_id', '!=', $folder->folder_id)->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'folder_kode' => 'Kode folder tersebut sudah digunakan untuk jenis kerja ini.',
                ]);
        }

        $validated['folder_status'] = $request->boolean('folder_status');

        $folder->update($validated);

        return redirect()->route('pengaturan.folder')->with('success', 'Folder berhasil diperbarui.');
    }

    public function folderDelete($uid)
    {
        $folder = SamperinFolder::where('folder_uid', $uid)->firstOrFail();

        $folder->delete();

        return redirect()->route('pengaturan.folder')->with('success', 'Folder berhasil dihapus.');
    }
}