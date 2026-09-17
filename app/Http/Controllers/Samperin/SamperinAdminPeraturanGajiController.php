<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinGolongan;
use App\Models\SamperinPeraturanGaji;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SamperinAdminPeraturanGajiController extends Controller
{
    /**
     * ============================================================
     * INDEX
     * ============================================================
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->search);
        $status = $request->status;

        $peraturan = SamperinPeraturanGaji::query()
            ->withCount('golongan')

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('peraturan_gaji_nama', 'like', "%{$search}%")->orWhere('peraturan_gaji_nomor', 'like', "%{$search}%");
                });
            })

            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('peraturan_gaji_status', $status);
            })

            ->latest('peraturan_gaji_id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $totalPeraturan = SamperinPeraturanGaji::count();

        $peraturanAktif = SamperinPeraturanGaji::where('peraturan_gaji_status', 1)->count();

        $peraturanNonaktif = SamperinPeraturanGaji::where('peraturan_gaji_status', 0)->count();

        return view('dashboard.kepegawaian.peraturan-gaji.index', compact('peraturan', 'totalPeraturan', 'peraturanAktif', 'peraturanNonaktif'));
    }

    /**
     * ============================================================
     * CREATE
     * ============================================================
     */
    public function create()
    {
        return view('dashboard.kepegawaian.peraturan-gaji.create');
    }

    /**
     * ============================================================
     * STORE
     * ============================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'peraturan_gaji_nama' => ['required', 'string', 'max:255'],

            'peraturan_gaji_nomor' => ['required', 'string', 'max:255'],

            'peraturan_gaji_tahun' => ['required', 'integer', 'min:2000', 'max:2100'],

            'peraturan_gaji_tanggal' => ['nullable', 'date'],

            'peraturan_gaji_status' => ['required', 'boolean'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Simpan Peraturan
        |--------------------------------------------------------------------------
        */

        SamperinPeraturanGaji::create([
            'peraturan_gaji_uid' => (string) Str::uuid(),

            'peraturan_gaji_nama' => $validated['peraturan_gaji_nama'],

            'peraturan_gaji_nomor' => $validated['peraturan_gaji_nomor'],

            'peraturan_gaji_tahun' => $validated['peraturan_gaji_tahun'],

            'peraturan_gaji_tanggal' => $validated['peraturan_gaji_tanggal'] ?? null,

            'peraturan_gaji_status' => $validated['peraturan_gaji_status'],
        ]);

        return redirect()->route('samperin.admin.peraturan-gaji.index')->with('success', 'Peraturan gaji berhasil ditambahkan.');
    }

    /**
     * ============================================================
     * SHOW
     * ============================================================
     *
     * Menampilkan pengaturan tarif gaji
     * berdasarkan golongan.
     */
    public function show($id)
    {
        $peraturan = SamperinPeraturanGaji::query()
            ->with([
                'golongan' => function ($query) {
                    $query->with('golongan');
                },
            ])
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Semua golongan aktif
        |--------------------------------------------------------------------------
        */

        $golongan = SamperinGolongan::query()->where('golongan_status', 1)->orderBy('golongan_nama')->get();

        return view('dashboard.kepegawaian.peraturan-gaji.show', compact('peraturan', 'golongan'));
    }

    /**
     * ============================================================
     * SAVE GOLONGAN
     * ============================================================
     *
     * Digunakan untuk:
     *
     * - Menambah tarif baru
     * - Menyimpan tarif yang sudah ada
     *
     * Input nominal boleh:
     *
     * 1000000
     * 1.000.000
     *
     * Database tetap:
     *
     * 1000000
     */
    public function saveGolongan(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan peraturan ada
        |--------------------------------------------------------------------------
        */

        $peraturan = SamperinPeraturanGaji::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Validasi dasar
        |--------------------------------------------------------------------------
        |
        | Jangan gunakan "numeric" di sini karena
        | input bisa berbentuk 1.000.000
        |
        */

        $validated = $request->validate(
            [
                'golongan_id' => ['required', 'integer', 'exists:samperin_golongan,golongan_id'],

                'peraturan_gaji_gaji_lama' => ['required', 'string', 'max:50'],

                'peraturan_gaji_gaji_baru' => ['required', 'string', 'max:50'],

                'peraturan_gaji_golongan_status' => ['required', 'boolean'],
            ],
            [
                'golongan_id.required' => 'Golongan wajib dipilih.',

                'golongan_id.exists' => 'Golongan tidak ditemukan.',

                'peraturan_gaji_gaji_lama.required' => 'Gaji lama wajib diisi.',

                'peraturan_gaji_gaji_baru.required' => 'Gaji baru wajib diisi.',

                'peraturan_gaji_golongan_status.required' => 'Status tarif wajib dipilih.',
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | Bersihkan format nominal
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | 1.000.000 -> 1000000
        | 2.500.000 -> 2500000
        | 1000000   -> 1000000
        |
        */

        $gajiLama = preg_replace('/[^0-9]/', '', (string) $validated['peraturan_gaji_gaji_lama']);

        $gajiBaru = preg_replace('/[^0-9]/', '', (string) $validated['peraturan_gaji_gaji_baru']);

        /*
        |--------------------------------------------------------------------------
        | Pastikan tidak kosong
        |--------------------------------------------------------------------------
        */

        if ($gajiLama === null || $gajiLama === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'peraturan_gaji_gaji_lama' => 'Gaji lama harus berupa angka.',
                ]);
        }

        if ($gajiBaru === null || $gajiBaru === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'peraturan_gaji_gaji_baru' => 'Gaji baru harus berupa angka.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi nilai angka
        |--------------------------------------------------------------------------
        */

        if (!ctype_digit($gajiLama)) {
            return back()
                ->withInput()
                ->withErrors([
                    'peraturan_gaji_gaji_lama' => 'Gaji lama harus berupa angka.',
                ]);
        }

        if (!ctype_digit($gajiBaru)) {
            return back()
                ->withInput()
                ->withErrors([
                    'peraturan_gaji_gaji_baru' => 'Gaji baru harus berupa angka.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan / Update
        |--------------------------------------------------------------------------
        */

        $peraturan->golongan()->updateOrCreate(
            /*
                |--------------------------------------------------------------------------
                | Kunci pencarian
                |--------------------------------------------------------------------------
                */

            [
                'golongan_id' => $validated['golongan_id'],
            ],

            /*
                |--------------------------------------------------------------------------
                | Data
                |--------------------------------------------------------------------------
                */

            [
                'peraturan_gaji_golongan_uid' => (string) Str::uuid(),

                'peraturan_gaji_gaji_lama' => (int) $gajiLama,

                'peraturan_gaji_gaji_baru' => (int) $gajiBaru,

                'peraturan_gaji_golongan_status' => $validated['peraturan_gaji_golongan_status'],
            ],
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return back()->with('success', 'Tarif gaji golongan berhasil disimpan.');
    }

    /**
     * ============================================================
     * UPDATE GOLONGAN
     * ============================================================
     *
     * Disediakan jika route update-golongan
     * masih digunakan.
     */
    public function updateGolongan(Request $request, $id, $golonganId)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan peraturan ada
        |--------------------------------------------------------------------------
        */

        $peraturan = SamperinPeraturanGaji::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'peraturan_gaji_gaji_lama' => ['required', 'string', 'max:50'],

            'peraturan_gaji_gaji_baru' => ['required', 'string', 'max:50'],

            'peraturan_gaji_golongan_status' => ['required', 'boolean'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Bersihkan nominal
        |--------------------------------------------------------------------------
        */

        $gajiLama = preg_replace('/[^0-9]/', '', (string) $validated['peraturan_gaji_gaji_lama']);

        $gajiBaru = preg_replace('/[^0-9]/', '', (string) $validated['peraturan_gaji_gaji_baru']);

        /*
        |--------------------------------------------------------------------------
        | Validasi angka
        |--------------------------------------------------------------------------
        */

        if ($gajiLama === null || $gajiLama === '' || !ctype_digit($gajiLama)) {
            return back()
                ->withInput()
                ->withErrors([
                    'peraturan_gaji_gaji_lama' => 'Gaji lama harus berupa angka.',
                ]);
        }

        if ($gajiBaru === null || $gajiBaru === '' || !ctype_digit($gajiBaru)) {
            return back()
                ->withInput()
                ->withErrors([
                    'peraturan_gaji_gaji_baru' => 'Gaji baru harus berupa angka.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cari tarif
        |--------------------------------------------------------------------------
        */

        $tarif = $peraturan->golongan()->where('golongan_id', $golonganId)->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $tarif->update([
            'peraturan_gaji_gaji_lama' => (int) $gajiLama,

            'peraturan_gaji_gaji_baru' => (int) $gajiBaru,

            'peraturan_gaji_golongan_status' => $validated['peraturan_gaji_golongan_status'],
        ]);

        return back()->with('success', 'Tarif gaji berhasil diperbarui.');
    }

    /**
     * ============================================================
     * EDIT PERATURAN
     * ============================================================
     */
    public function edit($id)
    {
        $peraturan = SamperinPeraturanGaji::findOrFail($id);

        return view('dashboard.kepegawaian.peraturan-gaji.edit', compact('peraturan'));
    }

    /**
     * ============================================================
     * UPDATE PERATURAN
     * ============================================================
     */
    public function update(Request $request, $id)
    {
        $peraturan = SamperinPeraturanGaji::findOrFail($id);

        $validated = $request->validate([
            'peraturan_gaji_nama' => ['required', 'string', 'max:255'],

            'peraturan_gaji_nomor' => ['required', 'string', 'max:255'],

            'peraturan_gaji_tahun' => ['required', 'integer', 'min:2000', 'max:2100'],

            'peraturan_gaji_tanggal' => ['nullable', 'date'],

            'peraturan_gaji_status' => ['required', 'boolean'],
        ]);

        $peraturan->update([
            'peraturan_gaji_nama' => $validated['peraturan_gaji_nama'],

            'peraturan_gaji_nomor' => $validated['peraturan_gaji_nomor'],

            'peraturan_gaji_tahun' => $validated['peraturan_gaji_tahun'],

            'peraturan_gaji_tanggal' => $validated['peraturan_gaji_tanggal'] ?? null,

            'peraturan_gaji_status' => $validated['peraturan_gaji_status'],
        ]);

        return redirect()->route('samperin.admin.peraturan-gaji.index')->with('success', 'Peraturan gaji berhasil diperbarui.');
    }

    /**
     * ============================================================
     * TOGGLE STATUS
     * ============================================================
     */
    public function toggleStatus($id)
    {
        $peraturan = SamperinPeraturanGaji::findOrFail($id);

        $peraturan->update([
            'peraturan_gaji_status' => !$peraturan->peraturan_gaji_status,
        ]);

        return back()->with('success', 'Status peraturan gaji berhasil diperbarui.');
    }
}