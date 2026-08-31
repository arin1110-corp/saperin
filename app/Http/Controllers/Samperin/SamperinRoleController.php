<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class SamperinRoleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = SamperinRole::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('role_nama', 'like', '%' . $search . '%')->orWhere('role_slug', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('role_status', (int) $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $roles = $query->orderBy('role_nama')->get();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalRole = SamperinRole::count();

        $roleAktif = SamperinRole::where('role_status', 1)->count();

        $roleNonaktif = SamperinRole::where('role_status', 0)->count();

        return view('dashboard.roles.index', compact('roles', 'totalRole', 'roleAktif', 'roleNonaktif'));
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
                'role_nama' => ['required', 'string', 'max:100', 'unique:samperin_role,role_nama'],

                'role_slug' => ['nullable', 'string', 'max:100', 'unique:samperin_role,role_slug'],

                'role_status' => ['required', 'in:0,1'],
            ],
            [
                'role_nama.required' => 'Nama role wajib diisi.',

                'role_nama.unique' => 'Nama role sudah digunakan.',

                'role_slug.unique' => 'Slug role sudah digunakan.',

                'role_status.required' => 'Status role wajib dipilih.',
            ],
        );

        try {
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | ROLE ID
            |--------------------------------------------------------------------------
            */

            $lastRoleId = SamperinRole::lockForUpdate()->max('role_id');

            $roleId = ((int) $lastRoleId) + 1;

            /*
            |--------------------------------------------------------------------------
            | SLUG
            |--------------------------------------------------------------------------
            */

            $roleSlug = !empty($validated['role_slug']) ? Str::slug($validated['role_slug']) : Str::slug($validated['role_nama']);

            /*
            |--------------------------------------------------------------------------
            | CEK SLUG
            |--------------------------------------------------------------------------
            */

            if (SamperinRole::where('role_slug', $roleSlug)->exists()) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->withErrors([
                        'role_slug' => 'Slug "' . $roleSlug . '" sudah digunakan.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE
            |--------------------------------------------------------------------------
            */

            SamperinRole::create([
                'role_id' => $roleId,

                'role_uid' => (string) Str::uuid(),

                'role_nama' => trim($validated['role_nama']),

                'role_slug' => $roleSlug,

                'role_status' => (int) $validated['role_status'],
            ]);

            DB::commit();

            return redirect()->route('samperin.admin.roles.index')->with('success', 'Role berhasil ditambahkan.');
        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'Role gagal ditambahkan. Silakan coba lagi.',
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, string $role_uid)
    {
        $role = SamperinRole::where('role_uid', $role_uid)->firstOrFail();

        $validated = $request->validate(
            [
                'role_nama' => ['required', 'string', 'max:100', 'unique:samperin_role,role_nama,' . $role->role_id . ',role_id'],

                'role_slug' => ['nullable', 'string', 'max:100', 'unique:samperin_role,role_slug,' . $role->role_id . ',role_id'],

                'role_status' => ['required', 'in:0,1'],
            ],
            [
                'role_nama.required' => 'Nama role wajib diisi.',

                'role_nama.unique' => 'Nama role sudah digunakan.',

                'role_slug.unique' => 'Slug role sudah digunakan.',
            ],
        );

        try {
            $roleSlug = !empty($validated['role_slug']) ? Str::slug($validated['role_slug']) : Str::slug($validated['role_nama']);

            /*
            |--------------------------------------------------------------------------
            | CEK SLUG
            |--------------------------------------------------------------------------
            */

            $slugDipakai = SamperinRole::where('role_slug', $roleSlug)->where('role_id', '!=', $role->role_id)->exists();

            if ($slugDipakai) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'role_slug' => 'Slug "' . $roleSlug . '" sudah digunakan.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $role->update([
                'role_nama' => trim($validated['role_nama']),

                'role_slug' => $roleSlug,

                'role_status' => (int) $validated['role_status'],
            ]);

            return redirect()->route('samperin.admin.roles.index')->with('success', 'Role berhasil diperbarui.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'Role gagal diperbarui. Silakan coba lagi.',
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE STATUS
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(string $role_uid)
    {
        $role = SamperinRole::where('role_uid', $role_uid)->firstOrFail();

        try {
            $newStatus = (int) $role->role_status === 1 ? 0 : 1;

            $role->update([
                'role_status' => $newStatus,
            ]);

            return redirect()
                ->route('samperin.admin.roles.index')
                ->with('success', $newStatus === 1 ? 'Role berhasil diaktifkan.' : 'Role berhasil dinonaktifkan.');
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'role' => 'Status role gagal diubah.',
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(string $role_uid)
    {
        $role = SamperinRole::where('role_uid', $role_uid)->firstOrFail();

        try {
            /*
            |--------------------------------------------------------------------------
            | CEK USER
            |--------------------------------------------------------------------------
            |
            | Role yang masih digunakan user tidak boleh dihapus.
            |
            */

            if ($role->users()->exists()) {
                return back()->withErrors([
                    'role' => 'Role "' . $role->role_nama . '" tidak dapat dihapus karena masih digunakan oleh pengguna.',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            $role->delete();

            return redirect()->route('samperin.admin.roles.index')->with('success', 'Role berhasil dihapus.');
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'role' => 'Role gagal dihapus. Pastikan role tidak sedang digunakan.',
            ]);
        }
    }
}