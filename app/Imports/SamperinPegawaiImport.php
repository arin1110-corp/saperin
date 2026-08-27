<?php

namespace App\Imports;

use App\Models\SamperinUser;
use App\Models\SamperinJabatan;
use App\Models\SamperinBidang;
use App\Models\SamperinGolongan;
use App\Models\SamperinEselon;
use App\Models\SamperinPendidikan;
use App\Models\SamperinStatusPegawai;
use App\Models\SamperinJenisKerja;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class SamperinPegawaiImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /*
    |--------------------------------------------------------------------------
    | HASIL IMPORT
    |--------------------------------------------------------------------------
    */

    protected int $total = 0;

    protected int $berhasil = 0;

    protected int $ditambahkan = 0;

    protected int $diperbarui = 0;

    protected int $dilewati = 0;

    protected array $errors = [];

    /*
    |--------------------------------------------------------------------------
    | COLLECTION
    |--------------------------------------------------------------------------
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            /*
            |--------------------------------------------------------------------------
            | NOMOR BARIS EXCEL
            |--------------------------------------------------------------------------
            */

            $excelRow = $index + 2;

            $this->total++;

            try {
                DB::transaction(function () use ($row, $excelRow) {
                    /*
                    |--------------------------------------------------------------------------
                    | NORMALISASI ROW
                    |--------------------------------------------------------------------------
                    */

                    $data = $this->normalizeRow($row);

                    /*
                    |--------------------------------------------------------------------------
                    | VALIDASI NIP
                    |--------------------------------------------------------------------------
                    */

                    if (empty($data['user_nip']) || $data['user_nip'] === '-') {
                        throw new \Exception('NIP kosong/tidak valid.');
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CARI USER BERDASARKAN NIP
                    |--------------------------------------------------------------------------
                    */

                    $user = SamperinUser::where('user_nip', $data['user_nip'])->first();

                    /*
                    |--------------------------------------------------------------------------
                    | REFERENSI MASTER
                    |--------------------------------------------------------------------------
                    */

                    $jabatanId = $this->findJabatan($data['user_jabatan']);

                    $bidangId = $this->findBidang($data['user_bidang']);

                    $golonganId = $this->findGolongan($data['user_golongan']);

                    $eselonId = $this->findEselon($data['user_eselon']);

                    $pendidikanId = $this->findPendidikan($data['user_pendidikan']);

                    $statusId = $this->findStatusPegawai($data['user_status']);

                    $jenisKerjaId = $this->findJenisKerja($data['user_jeniskerja']);

                    /*
                    |--------------------------------------------------------------------------
                    | DATA YANG AKAN DISIMPAN
                    |--------------------------------------------------------------------------
                    */

                    $saveData = [
                        'user_nip' => $data['user_nip'],

                        'user_nik' => $data['user_nik'],

                        'user_nama' => $data['user_nama'],

                        'user_gelardepan' => $data['user_gelardepan'],

                        'user_gelarbelakang' => $data['user_gelarbelakang'],

                        'user_tempatlahir' => $data['user_tempatlahir'],

                        'user_tgllahir' => $this->parseDate($data['user_tgllahir']),

                        'user_jk' => $data['user_jk'],

                        'user_jabatan_id' => $jabatanId,

                        'user_bidang_id' => $bidangId,

                        'user_golongan_id' => $golonganId,

                        'user_eselon_id' => $eselonId,

                        'user_pendidikan_id' => $pendidikanId,

                        'user_status_id' => $statusId,

                        'user_jenis_kerja_id' => $jenisKerjaId,

                        'user_tmt' => $this->parseDate($data['user_tmt']),

                        'user_spmt' => $this->parseDate($data['user_spmt']),

                        'user_npwp' => $data['user_npwp'],

                        'user_bpjs' => $data['user_bpjs'],

                        'user_norek_bpd' => $data['user_norek'],

                        'user_email' => $data['user_email'],

                        'user_notelp' => $data['user_notelp'],

                        'user_alamat' => $data['user_alamat'],

                        'user_lokasikerja' => $data['user_lokasikerja'],

                        'user_keterangan' => $data['user_ket'],

                        'user_status' => $this->parseUserStatus($data['user_status']),
                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | INSERT
                    |--------------------------------------------------------------------------
                    */

                    if (!$user) {
                        $saveData['user_uid'] = (string) Str::uuid();

                        $saveData['user_created_at'] = now();

                        $saveData['user_updated_at'] = now();

                        SamperinUser::create($saveData);

                        $this->ditambahkan++;
                    }
                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */ else {
                        $saveData['user_updated_at'] = now();

                        $user->update($saveData);

                        $this->diperbarui++;
                    }

                    $this->berhasil++;
                });
            } catch (\Throwable $e) {
                $this->dilewati++;

                $this->errors[] = [
                    'baris' => $excelRow,

                    'nip' => $this->clean($row['user_nip'] ?? ($row['nip'] ?? null)),

                    'nama' => $this->clean($row['user_nama'] ?? ($row['nama'] ?? null)),

                    'error' => $e->getMessage(),
                ];
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE ROW
    |--------------------------------------------------------------------------
    */

    protected function normalizeRow($row): array
    {
        return [
            'user_nip' => $this->clean($row['user_nip'] ?? ($row['nip'] ?? null)),

            'user_nik' => $this->clean($row['user_nik'] ?? ($row['nik'] ?? null)),

            'user_nama' => $this->clean($row['user_nama'] ?? ($row['nama'] ?? null)),

            'user_gelardepan' => $this->clean($row['user_gelardepan'] ?? ($row['gelardepan'] ?? ($row['gelar_depan'] ?? null))),

            'user_gelarbelakang' => $this->clean($row['user_gelarbelakang'] ?? ($row['gelarbelakang'] ?? ($row['gelar_belakang'] ?? null))),

            'user_tempatlahir' => $this->clean($row['user_tempatlahir'] ?? ($row['tempatlahir'] ?? ($row['tempat_lahir'] ?? null))),

            'user_tgllahir' => $row['user_tgllahir'] ?? ($row['tgllahir'] ?? ($row['tanggal_lahir'] ?? null)),

            'user_jk' => $this->clean($row['user_jk'] ?? ($row['jk'] ?? ($row['jenis_kelamin'] ?? null))),

            'user_jabatan' => $this->clean($row['user_jabatan'] ?? ($row['jabatan'] ?? null)),

            'user_bidang' => $this->clean($row['user_bidang'] ?? ($row['bidang'] ?? null)),

            'user_golongan' => $this->clean($row['user_golongan'] ?? ($row['golongan'] ?? null)),

            'user_eselon' => $this->clean($row['user_eselon'] ?? ($row['eselon'] ?? null)),

            'user_pendidikan' => $this->clean($row['user_pendidikan'] ?? ($row['pendidikan'] ?? null)),

            'user_status' => $this->clean($row['user_status'] ?? ($row['status'] ?? null)),

            'user_jeniskerja' => $this->clean($row['user_jeniskerja'] ?? ($row['jenis_kerja'] ?? ($row['jeniskerja'] ?? null))),

            'user_tmt' => $row['user_tmt'] ?? ($row['tmt'] ?? null),

            'user_spmt' => $row['user_spmt'] ?? ($row['spmt'] ?? null),

            'user_npwp' => $this->clean($row['user_npwp'] ?? ($row['npwp'] ?? null)),

            'user_bpjs' => $this->clean($row['user_bpjs'] ?? ($row['bpjs'] ?? null)),

            'user_norek' => $this->clean($row['user_norek'] ?? ($row['norek'] ?? ($row['user_norek_bpd'] ?? null))),

            'user_email' => $this->clean($row['user_email'] ?? ($row['email'] ?? null)),

            'user_notelp' => $this->clean($row['user_notelp'] ?? ($row['notelp'] ?? ($row['no_telp'] ?? ($row['telepon'] ?? null)))),

            'user_alamat' => $this->clean($row['user_alamat'] ?? ($row['alamat'] ?? null)),

            'user_lokasikerja' => $this->clean($row['user_lokasikerja'] ?? ($row['lokasikerja'] ?? ($row['lokasi_kerja'] ?? null))),

            'user_ket' => $this->clean($row['user_ket'] ?? ($row['keterangan'] ?? ($row['ket'] ?? null))),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN
    |--------------------------------------------------------------------------
    */

    protected function clean($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '' || in_array(strtolower($value), ['null', 'nil', 'none', 'n/a', 'na', '-', '--', 'kosong'], true)) {
            return null;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE DATE
    |--------------------------------------------------------------------------
    */

    protected function parseDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            if (is_numeric($value) && $value > 1000) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }

            $value = trim((string) $value);

            $formats = ['Y-m-d', 'd-m-Y', 'd/m/Y', 'm/d/Y', 'd.m.Y'];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $value);

                if ($date && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            }

            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FIND JABATAN
    |--------------------------------------------------------------------------
    */

    protected function findJabatan(?string $value): ?int
    {
        if (!$value) {
            return $this->defaultJabatan();
        }

        return SamperinJabatan::where('jabatan_kode', $value)->orWhere('jabatan_nama', $value)->value('jabatan_id') ?? $this->defaultJabatan();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BIDANG
    |--------------------------------------------------------------------------
    */

    protected function findBidang(?string $value): ?int
    {
        if (!$value) {
            return $this->defaultBidang();
        }

        return SamperinBidang::where('bidang_kode', $value)->orWhere('bidang_nama', $value)->value('bidang_id') ?? $this->defaultBidang();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND GOLONGAN
    |--------------------------------------------------------------------------
    */

    protected function findGolongan(?string $value): ?int
    {
        if (!$value) {
            return $this->defaultGolongan();
        }

        return SamperinGolongan::where('golongan_kode', $value)->orWhere('golongan_nama', $value)->value('golongan_id') ?? $this->defaultGolongan();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND ESELON
    |--------------------------------------------------------------------------
    */

    protected function findEselon(?string $value): ?int
    {
        if (!$value) {
            return $this->defaultEselon();
        }

        return SamperinEselon::where('eselon_kode', $value)->orWhere('eselon_nama', $value)->value('eselon_id') ?? $this->defaultEselon();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND PENDIDIKAN
    |--------------------------------------------------------------------------
    */

    protected function findPendidikan(?string $value): ?int
    {
        if (!$value) {
            return $this->defaultPendidikan();
        }

        return SamperinPendidikan::where('pendidikan_kode', $value)->orWhere('pendidikan_nama', $value)->value('pendidikan_id') ?? $this->defaultPendidikan();
    }

    /*
    |--------------------------------------------------------------------------
    | FIND STATUS PEGAWAI
    |--------------------------------------------------------------------------
    */

    protected function findStatusPegawai(?string $value): ?int
    {
        if (!$value) {
            return SamperinStatusPegawai::where('status_pegawai_kode', 'PNS')->value('status_pegawai_id');
        }

        return SamperinStatusPegawai::where('status_pegawai_kode', $value)->orWhere('status_pegawai_nama', $value)->value('status_pegawai_id');
    }

    /*
    |--------------------------------------------------------------------------
    | FIND JENIS KERJA
    |--------------------------------------------------------------------------
    */

    protected function findJenisKerja(?string $value): ?int
    {
        if (!$value) {
            return $this->defaultJenisKerja();
        }

        return SamperinJenisKerja::where('jenis_kerja_kode', $value)->orWhere('jenis_kerja_nama', $value)->value('jenis_kerja_id') ?? $this->defaultJenisKerja();
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT MASTER
    |--------------------------------------------------------------------------
    */

    protected function defaultJabatan(): ?int
    {
        return SamperinJabatan::where('jabatan_kode', 'DEFAULT')->value('jabatan_id');
    }

    protected function defaultBidang(): ?int
    {
        return SamperinBidang::where('bidang_kode', 'DEFAULT')->value('bidang_id');
    }

    protected function defaultGolongan(): ?int
    {
        return SamperinGolongan::where('golongan_kode', 'DEFAULT')->value('golongan_id');
    }

    protected function defaultEselon(): ?int
    {
        return SamperinEselon::where('eselon_kode', 'DEFAULT')->value('eselon_id');
    }

    protected function defaultPendidikan(): ?int
    {
        return SamperinPendidikan::where('pendidikan_kode', 'DEFAULT')->value('pendidikan_id');
    }

    protected function defaultJenisKerja(): ?int
    {
        return SamperinJenisKerja::where('jenis_kerja_kode', 'DEFAULT')->value('jenis_kerja_id');
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS USER
    |--------------------------------------------------------------------------
    */

    protected function parseUserStatus(?string $value): int
    {
        if (!$value) {
            return 1;
        }

        $value = strtolower(trim($value));

        return match ($value) {
            '0', 'nonaktif', 'tidak aktif', 'inactive', 'pensiun', 'meninggal', 'mutasi keluar' => 0,

            default => 1,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | CHUNK
    |--------------------------------------------------------------------------
    */

    public function chunkSize(): int
    {
        return 500;
    }

    /*
    |--------------------------------------------------------------------------
    | GETTERS
    |--------------------------------------------------------------------------
    */

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getBerhasil(): int
    {
        return $this->berhasil;
    }

    public function getDitambahkan(): int
    {
        return $this->ditambahkan;
    }

    public function getDiperbarui(): int
    {
        return $this->diperbarui;
    }

    public function getDilewati(): int
    {
        return $this->dilewati;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}