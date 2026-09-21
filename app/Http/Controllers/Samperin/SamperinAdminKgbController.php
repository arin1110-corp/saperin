<?php

namespace App\Http\Controllers\Samperin;

use App\Http\Controllers\Controller;
use App\Models\SamperinGolongan;
use App\Models\SamperinKgb;
use App\Models\SamperinKgbBatch;
use App\Models\SamperinPeraturanGaji;
use App\Models\SamperinUser;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SamperinAdminKgbController extends Controller
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

        $kgb = SamperinKgbBatch::query()
            ->with(['peraturanGaji', 'pejabat'])
            ->withCount('kgb')

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                $q->where('kgb_batch_nama', 'like', "%{$search}%")
                    ->orWhere('kgb_batch_nomor_format', 'like', "%{$search}%")
                    ->orWhere('kgb_batch_oleh_pejabat', 'like', "%{$search}%");
                });
            })

            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('kgb_batch_status', $status);
            })

            ->latest('kgb_batch_id')
            ->paginate(10)
            ->withQueryString();

        $totalBatch = SamperinKgbBatch::count();

        $batchAktif = SamperinKgbBatch::where('kgb_batch_status', 1)->count();

        $totalKgb = SamperinKgb::count();

        return view('dashboard.kepegawaian.kgb.index', compact('kgb', 'totalBatch', 'batchAktif', 'totalKgb'));
    }

    /**
     * ============================================================
     * CREATE
     * ============================================================
     */
    public function create(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Peraturan Gaji
        |--------------------------------------------------------------------------
        */
        $peraturanGaji = SamperinPeraturanGaji::query()->where('peraturan_gaji_status', 1)->orderByDesc('peraturan_gaji_tahun')->orderBy('peraturan_gaji_nama')->get();

        /*
        |--------------------------------------------------------------------------
        | Pejabat
        |--------------------------------------------------------------------------
        */
        $pejabat = SamperinUser::query()->where('user_status', 1)->orderBy('user_nama')->get();

        /*
        |--------------------------------------------------------------------------
        | Golongan
        |--------------------------------------------------------------------------
        */
        $golongan = SamperinGolongan::query()->where('golongan_status', 1)->orderBy('golongan_nama')->get();

        /*
        |--------------------------------------------------------------------------
        | Pegawai
        |--------------------------------------------------------------------------
        */
        $pegawaiQuery = SamperinUser::query()
            ->with(['jabatan', 'bidang', 'golongan'])
            ->where('user_status', 1);

        /*
        |--------------------------------------------------------------------------
        | Filter Golongan
        |--------------------------------------------------------------------------
        */
        if ($request->filled('golongan_id')) {
            $pegawaiQuery->where('user_golongan_id', $request->golongan_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Search Nama / NIP
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $pegawaiQuery->where(function ($query) use ($search) {
                $query->where('user_nama', 'like', "%{$search}%")->orWhere('user_nip', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Pegawai
        |--------------------------------------------------------------------------
        */
        $pegawai = $pegawaiQuery->orderBy('user_nama')->get();

        $jumlahPegawai = $pegawai->count();

        return view('dashboard.kepegawaian.kgb.create', compact('peraturanGaji', 'pejabat', 'golongan', 'pegawai', 'jumlahPegawai'));
    }

    /**
     * ============================================================
     * STORE
     * ============================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'kgb_batch_nama' => ['required', 'string', 'max:255'],

                'kgb_batch_peraturan_gaji_id' => ['required', 'integer', 'exists:samperin_peraturan_gaji,peraturan_gaji_id'],

                'kgb_batch_pejabat_id' => ['required', 'integer', 'exists:samperin_user,user_id'],

                'kgb_batch_oleh_pejabat' => ['required', 'string', 'max:255'],

                'kgb_batch_tanggal' => ['required', 'date'],

                'kgb_batch_mulai_berlaku' => ['required', 'date'],

                'kgb_batch_nomor_format' => ['required', 'string', 'max:255'],

                'kgb_batch_nomor_awal' => ['required', 'integer', 'min:1'],

                'pegawai' => ['required', 'array', 'min:1'],

                'pegawai.*' => ['required', 'integer', 'exists:samperin_user,user_id'],
            ],
            [
                'pegawai.required' => 'Silakan pilih minimal satu pegawai.',

                'pegawai.min' => 'Silakan pilih minimal satu pegawai.',

                'kgb_batch_oleh_pejabat.required' => 'Oleh pejabat wajib diisi.',
            ],
        );

        $tanggalSurat = Carbon::parse($validated['kgb_batch_tanggal']);

        $mulaiBerlaku = Carbon::parse($validated['kgb_batch_mulai_berlaku']);

        /*
        |--------------------------------------------------------------------------
        | Pegawai
        |--------------------------------------------------------------------------
        */
        $pegawaiIds = collect($validated['pegawai'])->unique()->values();

        $pegawaiTerpilih = SamperinUser::query()->whereIn('user_id', $pegawaiIds)->get();

        /*
        |--------------------------------------------------------------------------
        | Validasi TMT
        |--------------------------------------------------------------------------
        */
        foreach ($pegawaiTerpilih as $pegawai) {
            if (!$pegawai->user_tmt) {
                throw ValidationException::withMessages([
                    'pegawai' => "Pegawai {$pegawai->user_nama} belum memiliki TMT.",
                ]);
            }

            $tmt = Carbon::parse($pegawai->user_tmt);

            if ($tmt->greaterThan($mulaiBerlaku)) {
                throw ValidationException::withMessages([
                    'pegawai' => "TMT pegawai {$pegawai->user_nama} lebih besar dari tanggal mulai berlaku KGB.",
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Peraturan Gaji
        |--------------------------------------------------------------------------
        */
        $peraturan = SamperinPeraturanGaji::query()->with('golongan')->findOrFail($validated['kgb_batch_peraturan_gaji_id']);

        foreach ($pegawaiTerpilih as $pegawai) {
            if (!$pegawai->user_golongan_id) {
                throw ValidationException::withMessages([
                    'pegawai' => "Pegawai {$pegawai->user_nama} belum memiliki golongan.",
                ]);
            }

            $tarif = $peraturan->golongan->firstWhere('golongan_id', $pegawai->user_golongan_id);

            if (!$tarif) {
                throw ValidationException::withMessages([
                    'pegawai' => "Tarif gaji untuk golongan pegawai {$pegawai->user_nama} belum diatur pada peraturan gaji yang dipilih.",
                ]);
            }
        }

        try {
            DB::transaction(function () use ($validated, $pegawaiIds) {
                /*
                |--------------------------------------------------------------------------
                | Cek pegawai yang masih memiliki KGB aktif
                |--------------------------------------------------------------------------
                */
                $pegawaiSudahAda = SamperinKgb::query()
                    ->whereIn('kgb_user_id', $pegawaiIds)
                    ->whereHas('batch', function ($query) {
                        $query->where('kgb_batch_status', 1);
                    })
                    ->pluck('kgb_user_id');

                if ($pegawaiSudahAda->isNotEmpty()) {
                    $nama = SamperinUser::query()->whereIn('user_id', $pegawaiSudahAda)->pluck('user_nama')->implode(', ');

                    throw ValidationException::withMessages([
                        'pegawai' => "Pegawai berikut masih memiliki KGB aktif: {$nama}",
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Buat Batch
                |--------------------------------------------------------------------------
                */
                $batch = SamperinKgbBatch::create([
                    'kgb_batch_uid' => (string) Str::uuid(),

                    'kgb_batch_nama' => $validated['kgb_batch_nama'],

                    'kgb_batch_peraturan_gaji_id' => $validated['kgb_batch_peraturan_gaji_id'],

                    'kgb_batch_pejabat_id' => $validated['kgb_batch_pejabat_id'],

                    'kgb_batch_oleh_pejabat' => $validated['kgb_batch_oleh_pejabat'],

                    'kgb_batch_tanggal' => $validated['kgb_batch_tanggal'],

                    'kgb_batch_mulai_berlaku' => $validated['kgb_batch_mulai_berlaku'],

                    'kgb_batch_nomor_format' => $validated['kgb_batch_nomor_format'],

                    'kgb_batch_nomor_awal' => $validated['kgb_batch_nomor_awal'],

                    'kgb_batch_nomor_akhir' => null,

                    'kgb_batch_status' => 1,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Generate Detail KGB
                |--------------------------------------------------------------------------
                */
                $this->generateKgbRows($batch, $pegawaiIds);
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'pegawai' => 'Batch KGB gagal dibuat. ' . $e->getMessage(),
                ]);
        }

        return redirect()->route('samperin.admin.kgb.index')->with('success', 'Batch KGB berhasil dibuat.');
    }

    /**
     * ============================================================
     * GENERATE KGB ROWS
     * ============================================================
     */
    private function generateKgbRows(SamperinKgbBatch $batch, $pegawaiIds)
    {
        /*
        |--------------------------------------------------------------------------
        | Peraturan Gaji
        |--------------------------------------------------------------------------
        */
        $peraturan = SamperinPeraturanGaji::query()->with('golongan')->findOrFail($batch->kgb_batch_peraturan_gaji_id);

        /*
        |--------------------------------------------------------------------------
        | Nomor Awal
        |--------------------------------------------------------------------------
        */
        $nomor = (int) $batch->kgb_batch_nomor_awal;

        /*
        |--------------------------------------------------------------------------
        | Tahun Surat
        |--------------------------------------------------------------------------
        */
        $tahun = Carbon::parse($batch->kgb_batch_tanggal)->format('Y');

        /*
        |--------------------------------------------------------------------------
        | Tanggal Mulai Berlaku
        |--------------------------------------------------------------------------
        */
        $tanggalMulaiBerlaku = Carbon::parse($batch->kgb_batch_mulai_berlaku);

        /*
        |--------------------------------------------------------------------------
        | Generate Satu Per Satu
        |--------------------------------------------------------------------------
        */
        foreach ($pegawaiIds as $pegawaiId) {
            $pegawai = SamperinUser::query()->with('golongan')->findOrFail($pegawaiId);

            /*
            |--------------------------------------------------------------------------
            | Pastikan Golongan
            |--------------------------------------------------------------------------
            */
            if (!$pegawai->user_golongan_id) {
                throw new \Exception("Pegawai {$pegawai->user_nama} belum memiliki golongan.");
            }

            /*
            |--------------------------------------------------------------------------
            | Pastikan TMT
            |--------------------------------------------------------------------------
            */
            if (!$pegawai->user_tmt) {
                throw new \Exception("TMT pegawai {$pegawai->user_nama} belum tersedia.");
            }

            $tmt = Carbon::parse($pegawai->user_tmt);

            if ($tmt->greaterThan($tanggalMulaiBerlaku)) {
                throw new \Exception("TMT pegawai {$pegawai->user_nama} lebih besar dari tanggal mulai berlaku KGB.");
            }

            /*
            |--------------------------------------------------------------------------
            | Pastikan Tarif Gaji Tersedia
            |--------------------------------------------------------------------------
            */
            $tarif = $peraturan->golongan->firstWhere('golongan_id', $pegawai->user_golongan_id);

            if (!$tarif) {
                throw new \Exception("Tarif gaji untuk golongan pegawai {$pegawai->user_nama} belum diatur pada peraturan gaji yang dipilih.");
            }

            /*
            |--------------------------------------------------------------------------
            | Masa Kerja
            |--------------------------------------------------------------------------
            */
            $masaKerja = $tmt->diff($tanggalMulaiBerlaku);

            /*
            |--------------------------------------------------------------------------
            | Nomor Surat
            |--------------------------------------------------------------------------
            */
            $nomorSurat = $this->generateNomorSurat($batch, $nomor);

            /*
            |--------------------------------------------------------------------------
            | Simpan Detail KGB
            |--------------------------------------------------------------------------
            */
            SamperinKgb::create([
                'kgb_uid' => (string) Str::uuid(),

                'kgb_batch_id' => $batch->kgb_batch_id,

                'kgb_user_id' => $pegawai->user_id,

                'kgb_golongan_id' => $pegawai->user_golongan_id,

                'kgb_nomor_surat' => $nomorSurat,

                'kgb_tanggal_surat' => $batch->kgb_batch_tanggal,

                'kgb_pejabat_id' => $batch->kgb_batch_pejabat_id,

                'kgb_masa_kerja_tahun' => $masaKerja->y,

                'kgb_masa_kerja_bulan' => $masaKerja->m,

                'kgb_mulai_berlaku' => $batch->kgb_batch_mulai_berlaku,

                'kgb_nomor_sk' => null,

                'kgb_tanggal_sk' => null,

                'kgb_status' => 1,
            ]);

            $nomor++;
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan Nomor Akhir
        |--------------------------------------------------------------------------
        */
        $batch->update([
            'kgb_batch_nomor_akhir' => $nomor - 1,
        ]);
    }

    /**
     * ============================================================
     * GENERATE NOMOR SURAT
     * ============================================================
     */
    private function generateNomorSurat(SamperinKgbBatch $batch, int $nomor): string
    {
        $tahun = Carbon::parse($batch->kgb_batch_tanggal)->format('Y');

        return str_replace(['{nomor}', '{tahun}'], [str_pad($nomor, 3, '0', STR_PAD_LEFT), $tahun], $batch->kgb_batch_nomor_format);
    }

    /**
     * ============================================================
     * SHOW
     * ============================================================
     */
    public function show($id)
    {
        $batch = SamperinKgbBatch::query()
            ->with(['peraturanGaji', 'pejabat', 'kgb.user', 'kgb.golongan', 'kgb.pejabat'])
            ->findOrFail($id);

        return view('dashboard.kepegawaian.kgb.show', compact('batch'));
    }

    /**
     * ============================================================
     * EDIT
     * ============================================================
     */
    public function edit($id)
    {
        $batch = SamperinKgbBatch::query()
            ->with(['kgb.user', 'kgb.golongan'])
            ->findOrFail($id);

        $peraturanGaji = SamperinPeraturanGaji::query()->where('peraturan_gaji_status', 1)->orderByDesc('peraturan_gaji_tahun')->orderBy('peraturan_gaji_nama')->get();

        $pejabat = SamperinUser::query()->where('user_status', 1)->orderBy('user_nama')->get();

        $golongan = SamperinGolongan::query()->where('golongan_status', 1)->orderBy('golongan_nama')->get();

        $pegawaiDalamBatch = $batch->kgb->pluck('kgb_user_id')->values();

        $pegawaiQuery = SamperinUser::query()
            ->with(['jabatan', 'bidang', 'golongan'])
            ->where('user_status', 1);

        if (request()->filled('golongan_id')) {
            $pegawaiQuery->where('user_golongan_id', request('golongan_id'));
        }

        if (request()->filled('search')) {
            $search = trim((string) request('search'));

            $pegawaiQuery->where(function ($query) use ($search) {
                $query->where('user_nama', 'like', "%{$search}%")->orWhere('user_nip', 'like', "%{$search}%");
            });
        }

        $pegawai = $pegawaiQuery->orderBy('user_nama')->get();

        $jumlahPegawai = $pegawai->count();

        return view('dashboard.kepegawaian.kgb.edit', compact('batch', 'peraturanGaji', 'pejabat', 'golongan', 'pegawai', 'jumlahPegawai', 'pegawaiDalamBatch'));
    }

    /**
     * ============================================================
     * UPDATE
     * ============================================================
     *
     * Saat batch diubah, seluruh detail KGB yang sudah tersimpan
     * ikut disinkronkan dengan data batch.
     *
     * Yang disinkronkan:
     *
     * - Nomor surat
     * - Tanggal surat
     * - Pejabat
     * - Masa kerja
     * - Mulai berlaku
     * - Golongan pegawai
     *
     * Data yang tetap milik detail:
     *
     * - Nomor SK
     * - Tanggal SK
     */
    public function update(Request $request, $id)
    {
        $batch = SamperinKgbBatch::query()->with('kgb')->findOrFail($id);

        $validated = $request->validate(
            [
                'kgb_batch_nama' => ['required', 'string', 'max:255'],

                'kgb_batch_peraturan_gaji_id' => ['required', 'integer', 'exists:samperin_peraturan_gaji,peraturan_gaji_id'],

                'kgb_batch_pejabat_id' => ['required', 'integer', 'exists:samperin_user,user_id'],

                'kgb_batch_oleh_pejabat' => ['required', 'string', 'max:255'],

                'kgb_batch_tanggal' => ['required', 'date'],

                'kgb_batch_mulai_berlaku' => ['required', 'date'],

                'kgb_batch_nomor_format' => ['required', 'string', 'max:255'],

                'kgb_batch_nomor_awal' => ['required', 'integer', 'min:1'],

                'pegawai' => ['nullable', 'array'],

                'pegawai.*' => ['integer', 'exists:samperin_user,user_id'],
            ],
            [
                'kgb_batch_oleh_pejabat.required' => 'Oleh pejabat wajib diisi.',
            ],
        );

        try {
            DB::transaction(function () use ($validated, $batch) {
                /*
                |--------------------------------------------------------------------------
                | DATA BATCH BARU
                |--------------------------------------------------------------------------
                */
                $tanggalSurat = Carbon::parse($validated['kgb_batch_tanggal']);

                $tanggalMulaiBerlaku = Carbon::parse($validated['kgb_batch_mulai_berlaku']);

                /*
                |--------------------------------------------------------------------------
                | Peraturan Gaji Baru
                |--------------------------------------------------------------------------
                */
                $peraturan = SamperinPeraturanGaji::query()->with('golongan')->findOrFail($validated['kgb_batch_peraturan_gaji_id']);

                /*
                |--------------------------------------------------------------------------
                | Update Batch
                |--------------------------------------------------------------------------
                */
                $batch->update([
                    'kgb_batch_nama' => $validated['kgb_batch_nama'],

                    'kgb_batch_peraturan_gaji_id' => $validated['kgb_batch_peraturan_gaji_id'],

                    'kgb_batch_pejabat_id' => $validated['kgb_batch_pejabat_id'],

                    'kgb_batch_oleh_pejabat' => $validated['kgb_batch_oleh_pejabat'],

                    'kgb_batch_tanggal' => $validated['kgb_batch_tanggal'],

                    'kgb_batch_mulai_berlaku' => $validated['kgb_batch_mulai_berlaku'],

                    'kgb_batch_nomor_format' => $validated['kgb_batch_nomor_format'],

                    'kgb_batch_nomor_awal' => $validated['kgb_batch_nomor_awal'],
                ]);

                /*
                |--------------------------------------------------------------------------
                | Pegawai Lama
                |--------------------------------------------------------------------------
                */
                $pegawaiLama = SamperinKgb::query()->where('kgb_batch_id', $batch->kgb_batch_id)->pluck('kgb_user_id');

                /*
                |--------------------------------------------------------------------------
                | Pegawai Baru
                |--------------------------------------------------------------------------
                */
                $pegawaiBaru = collect($validated['pegawai'] ?? [])
                    ->unique()
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | Pegawai Yang Ditambahkan
                |--------------------------------------------------------------------------
                */
                $pegawaiYangDitambahkan = $pegawaiBaru->diff($pegawaiLama)->values();

                /*
                |--------------------------------------------------------------------------
                | Jika tidak ada input pegawai dari form,
                | gunakan pegawai yang sudah ada.
                |--------------------------------------------------------------------------
                */
                $semuaPegawaiIds = $pegawaiLama->merge($pegawaiYangDitambahkan)->unique()->values();

                /*
                |--------------------------------------------------------------------------
                | Validasi seluruh pegawai dalam batch
                |--------------------------------------------------------------------------
                */
                $pegawaiDalamBatch = SamperinUser::query()->whereIn('user_id', $semuaPegawaiIds)->get();

                foreach ($pegawaiDalamBatch as $pegawai) {
                    /*
                    |--------------------------------------------------------------------------
                    | Golongan
                    |--------------------------------------------------------------------------
                    */
                    if (!$pegawai->user_golongan_id) {
                        throw new \Exception("Pegawai {$pegawai->user_nama} belum memiliki golongan.");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | TMT
                    |--------------------------------------------------------------------------
                    */
                    if (!$pegawai->user_tmt) {
                        throw new \Exception("TMT pegawai {$pegawai->user_nama} belum tersedia.");
                    }

                    $tmt = Carbon::parse($pegawai->user_tmt);

                    if ($tmt->greaterThan($tanggalMulaiBerlaku)) {
                        throw new \Exception("TMT pegawai {$pegawai->user_nama} lebih besar dari tanggal mulai berlaku KGB.");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Tarif Gaji
                    |--------------------------------------------------------------------------
                    */
                    $tarif = $peraturan->golongan->firstWhere('golongan_id', $pegawai->user_golongan_id);

                    if (!$tarif) {
                        throw new \Exception("Tarif gaji untuk golongan pegawai {$pegawai->user_nama} belum diatur pada peraturan gaji yang dipilih.");
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Cek Pegawai Baru Yang Sudah Memiliki KGB Aktif
                |--------------------------------------------------------------------------
                */
                if ($pegawaiYangDitambahkan->isNotEmpty()) {
                    $pegawaiSudahAda = SamperinKgb::query()
                        ->whereIn('kgb_user_id', $pegawaiYangDitambahkan)
                        ->where('kgb_batch_id', '!=', $batch->kgb_batch_id)
                        ->whereHas('batch', function ($query) {
                            $query->where('kgb_batch_status', 1);
                        })
                        ->pluck('kgb_user_id');

                    if ($pegawaiSudahAda->isNotEmpty()) {
                        $nama = SamperinUser::query()->whereIn('user_id', $pegawaiSudahAda)->pluck('user_nama')->implode(', ');

                        throw new \Exception("Pegawai berikut masih memiliki KGB aktif: {$nama}");
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | NOMOR AWAL
                |--------------------------------------------------------------------------
                */
                $nomor = (int) $batch->kgb_batch_nomor_awal;

                /*
                |--------------------------------------------------------------------------
                | UPDATE KGB LAMA
                |--------------------------------------------------------------------------
                |
                | Semua data batch yang disalin ke KGB
                | harus disinkronkan kembali.
                |
                */
                $kgbList = SamperinKgb::query()->with('user')->where('kgb_batch_id', $batch->kgb_batch_id)->orderBy('kgb_id')->get();

                foreach ($kgbList as $kgb) {
                    if (!$kgb->user) {
                        continue;
                    }

                    $pegawai = $kgb->user;

                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan TMT
                    |--------------------------------------------------------------------------
                    */
                    if (!$pegawai->user_tmt) {
                        throw new \Exception("TMT pegawai {$pegawai->user_nama} belum tersedia.");
                    }

                    $tmt = Carbon::parse($pegawai->user_tmt);

                    if ($tmt->greaterThan($tanggalMulaiBerlaku)) {
                        throw new \Exception("TMT pegawai {$pegawai->user_nama} lebih besar dari tanggal mulai berlaku KGB.");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan Golongan
                    |--------------------------------------------------------------------------
                    */
                    if (!$pegawai->user_golongan_id) {
                        throw new \Exception("Pegawai {$pegawai->user_nama} belum memiliki golongan.");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan Tarif
                    |--------------------------------------------------------------------------
                    */
                    $tarif = $peraturan->golongan->firstWhere('golongan_id', $pegawai->user_golongan_id);

                    if (!$tarif) {
                        throw new \Exception("Tarif gaji untuk golongan pegawai {$pegawai->user_nama} belum diatur pada peraturan gaji yang dipilih.");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Masa Kerja
                    |--------------------------------------------------------------------------
                    */
                    $masaKerja = $tmt->diff($tanggalMulaiBerlaku);

                    /*
                    |--------------------------------------------------------------------------
                    | Nomor Surat
                    |--------------------------------------------------------------------------
                    */
                    $nomorSurat = $this->generateNomorSurat($batch, $nomor);

                    /*
                    |--------------------------------------------------------------------------
                    | Update Detail KGB
                    |--------------------------------------------------------------------------
                    */
                    $kgb->update([
                        'kgb_golongan_id' => $pegawai->user_golongan_id,

                        'kgb_nomor_surat' => $nomorSurat,

                        'kgb_tanggal_surat' => $validated['kgb_batch_tanggal'],

                        'kgb_pejabat_id' => $validated['kgb_batch_pejabat_id'],

                        'kgb_masa_kerja_tahun' => $masaKerja->y,

                        'kgb_masa_kerja_bulan' => $masaKerja->m,

                        'kgb_mulai_berlaku' => $validated['kgb_batch_mulai_berlaku'],
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Nomor Berikutnya
                    |--------------------------------------------------------------------------
                    */
                    $nomor++;
                }

                /*
                |--------------------------------------------------------------------------
                | TAMBAHKAN PEGAWAI BARU
                |--------------------------------------------------------------------------
                */
                foreach ($pegawaiYangDitambahkan as $pegawaiId) {
                    $pegawai = SamperinUser::query()->with('golongan')->findOrFail($pegawaiId);

                    /*
                    |--------------------------------------------------------------------------
                    | Golongan
                    |--------------------------------------------------------------------------
                    */
                    if (!$pegawai->user_golongan_id) {
                        throw new \Exception("Pegawai {$pegawai->user_nama} belum memiliki golongan.");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | TMT
                    |--------------------------------------------------------------------------
                    */
                    if (!$pegawai->user_tmt) {
                        throw new \Exception("TMT pegawai {$pegawai->user_nama} belum tersedia.");
                    }

                    $tmt = Carbon::parse($pegawai->user_tmt);

                    if ($tmt->greaterThan($tanggalMulaiBerlaku)) {
                        throw new \Exception("TMT pegawai {$pegawai->user_nama} lebih besar dari tanggal mulai berlaku KGB.");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Tarif Gaji
                    |--------------------------------------------------------------------------
                    */
                    $tarif = $peraturan->golongan->firstWhere('golongan_id', $pegawai->user_golongan_id);

                    if (!$tarif) {
                        throw new \Exception("Tarif gaji untuk golongan pegawai {$pegawai->user_nama} belum diatur pada peraturan gaji yang dipilih.");
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Masa Kerja
                    |--------------------------------------------------------------------------
                    */
                    $masaKerja = $tmt->diff($tanggalMulaiBerlaku);

                    /*
                    |--------------------------------------------------------------------------
                    | Nomor Surat
                    |--------------------------------------------------------------------------
                    */
                    $nomorSurat = $this->generateNomorSurat($batch, $nomor);

                    /*
                    |--------------------------------------------------------------------------
                    | Simpan KGB Baru
                    |--------------------------------------------------------------------------
                    */
                    SamperinKgb::create([
                        'kgb_uid' => (string) Str::uuid(),

                        'kgb_batch_id' => $batch->kgb_batch_id,

                        'kgb_user_id' => $pegawai->user_id,

                        'kgb_golongan_id' => $pegawai->user_golongan_id,

                        'kgb_nomor_surat' => $nomorSurat,

                        'kgb_tanggal_surat' => $validated['kgb_batch_tanggal'],

                        'kgb_pejabat_id' => $validated['kgb_batch_pejabat_id'],

                        'kgb_masa_kerja_tahun' => $masaKerja->y,

                        'kgb_masa_kerja_bulan' => $masaKerja->m,

                        'kgb_mulai_berlaku' => $validated['kgb_batch_mulai_berlaku'],

                        'kgb_nomor_sk' => null,

                        'kgb_tanggal_sk' => null,

                        'kgb_status' => 1,
                    ]);

                    $nomor++;
                }

                /*
                |--------------------------------------------------------------------------
                | UPDATE NOMOR AKHIR
                |--------------------------------------------------------------------------
                */
                $jumlahKgb = SamperinKgb::query()->where('kgb_batch_id', $batch->kgb_batch_id)->count();

                $batch->update([
                    'kgb_batch_nomor_akhir' => $jumlahKgb > 0 ? (int) $batch->kgb_batch_nomor_awal + $jumlahKgb - 1 : null,
                ]);
            });

            return redirect()->route('samperin.admin.kgb.show', $batch->kgb_batch_id)->with('success', 'Batch KGB berhasil diperbarui.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'pegawai' => 'Batch KGB gagal diperbarui. ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * ============================================================
     * UPDATE NOMOR SK
     * ============================================================
     */
    public function updateNomorSk(Request $request, $id)
    {
        $kgb = SamperinKgb::findOrFail($id);

        $validated = $request->validate([
            'kgb_nomor_sk' => ['required', 'string', 'max:255'],
        ]);

        $kgb->update([
            'kgb_nomor_sk' => $validated['kgb_nomor_sk'],
        ]);

        return back()->with('success', 'Nomor SK berhasil diperbarui.');
    }

    /**
     * ============================================================
     * TOGGLE STATUS
     * ============================================================
     */
    public function toggleStatus($id)
    {
        $batch = SamperinKgbBatch::findOrFail($id);

        $batch->update([
            'kgb_batch_status' => !$batch->kgb_batch_status,
        ]);

        return back()->with('success', 'Status batch KGB berhasil diperbarui.');
    }

    /**
     * ============================================================
     * REMOVE PEGAWAI DARI BATCH
     * ============================================================
     */
    public function removePegawai($id, $kgbId)
    {
        $batch = SamperinKgbBatch::findOrFail($id);

        $kgb = SamperinKgb::query()->with('user')->where('kgb_id', $kgbId)->where('kgb_batch_id', $batch->kgb_batch_id)->firstOrFail();

        $namaPegawai = $kgb->user->user_nama ?? 'Pegawai';

        DB::transaction(function () use ($batch, $kgb) {
            /*
                |--------------------------------------------------------------------------
                | Hapus Detail KGB
                |--------------------------------------------------------------------------
                */
            $kgb->delete();

            /*
                |--------------------------------------------------------------------------
                | Ambil Sisa KGB
                |--------------------------------------------------------------------------
                */
            $kgbList = SamperinKgb::query()->where('kgb_batch_id', $batch->kgb_batch_id)->orderBy('kgb_id')->get();

            /*
                |--------------------------------------------------------------------------
                | Nomor Awal
                |--------------------------------------------------------------------------
                */
            $nomor = (int) $batch->kgb_batch_nomor_awal;

            /*
                |--------------------------------------------------------------------------
                | Generate Ulang Nomor Surat
                |--------------------------------------------------------------------------
                */
            foreach ($kgbList as $item) {
                $nomorSurat = $this->generateNomorSurat($batch, $nomor);

                $item->update([
                    'kgb_nomor_surat' => $nomorSurat,
                ]);

                $nomor++;
            }

            /*
                |--------------------------------------------------------------------------
                | Update Nomor Akhir
                |--------------------------------------------------------------------------
                */
            $batch->update([
                'kgb_batch_nomor_akhir' => $kgbList->isNotEmpty() ? $nomor - 1 : null,
            ]);
        });

        return back()->with('success', "{$namaPegawai} berhasil dihapus dari batch KGB.");
    }

    /**
     * ============================================================
     * PDF SATU KGB
     * ============================================================
     */
    public function pdf($id, $kgbId)
    {
        $batch = SamperinKgbBatch::query()
            ->with(['peraturanGaji', 'pejabat'])
            ->findOrFail($id);

        $kgb = SamperinKgb::query()
            ->with(['user.jabatan', 'user.bidang', 'user.golongan', 'golongan', 'pejabat', 'batch.peraturanGaji.golongan'])
            ->where('kgb_id', $kgbId)
            ->where('kgb_batch_id', $batch->kgb_batch_id)
            ->firstOrFail();

        return Pdf::loadView('dashboard.kepegawaian.kgb.pdf', compact('batch', 'kgb'))
            ->setPaper('A4', 'portrait')
            ->stream('KGB-' . ($kgb->user->user_nama ?? $kgb->kgb_id) . '.pdf');
    }

    /**
     * ============================================================
     * PDF SEMUA KGB
     * ============================================================
     */
    public function pdfAll($id)
    {
        $batch = SamperinKgbBatch::query()
            ->with(['peraturanGaji.golongan', 'pejabat', 'kgb.user.jabatan', 'kgb.user.bidang', 'kgb.user.golongan', 'kgb.golongan', 'kgb.pejabat'])
            ->findOrFail($id);

        if ($batch->kgb->isEmpty()) {
            return back()->withErrors([
                'pegawai' => 'Batch KGB belum memiliki pegawai.',
            ]);
        }

        $pdf = Pdf::loadView('dashboard.kepegawaian.kgb.pdf-all', compact('batch'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('KGB-' . $batch->kgb_batch_nama . '.pdf');
    }
}