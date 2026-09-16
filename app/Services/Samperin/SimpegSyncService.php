<?php

namespace App\Services\Samperin;

use App\Imports\SimpegImport;
use App\Models\SamperinUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SimpegSyncService
{
    /**
     * Sinkronisasi data SIMPEG dari file Excel XLSX.
     */
    public function sync(string $filePath): array
    {
        $result = [
            'total' => 0,
            'updated' => 0,
            'inserted' => 0,
            'deactivated' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        /*
        |--------------------------------------------------------------------------
        | IMPORT EXCEL
        |--------------------------------------------------------------------------
        |
        | Di VPS getRealPath() menghasilkan temporary file tanpa ekstensi.
        | Karena itu Reader XLSX dipaksa secara eksplisit.
        |
        */

        $import = new SimpegImport();

        Excel::import($import, $filePath, null, \Maatwebsite\Excel\Excel::XLSX);

        $rows = $import->rows ?? collect();

        if ($rows->isEmpty()) {
            throw new \Exception('File Excel SIMPEG kosong atau tidak memiliki data.');
        }

        /*
        |--------------------------------------------------------------------------
        | HAPUS BARIS PERTAMA
        |--------------------------------------------------------------------------
        |
        | Berdasarkan hasil pengecekan Excel:
        | index 0 = baris non-data
        | index 1 = pegawai pertama
        |
        */

        $rows = $rows->skip(1)->values();

        if ($rows->isEmpty()) {
            throw new \Exception('File Excel SIMPEG tidak memiliki data pegawai.');
        }

        /*
        |--------------------------------------------------------------------------
        | IDENTITAS YANG ADA DI EXCEL
        |--------------------------------------------------------------------------
        |
        | Dipakai untuk menentukan pegawai SAMPERIN yang sudah tidak
        | ada dalam dataset SIMPEG.
        |
        */

        $nipExcel = [];
        $nikExcel = [];

        /*
        |--------------------------------------------------------------------------
        | PROSES SETIAP PEGAWAI
        |--------------------------------------------------------------------------
        */

        foreach ($rows as $index => $row) {
            $nip = $this->normalizeIdentifier($this->getValue($row, 'nip'));

            $nik = $this->normalizeIdentifier($this->getValue($row, 'nik'));

            $nama = $this->cleanString($this->getValue($row, 'nama'));

            /*
            |--------------------------------------------------------------------------
            | LEWATI BARIS KOSONG
            |--------------------------------------------------------------------------
            */

            if (!$nip && !$nik && !$nama) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | NIP / NIK WAJIB ADA
            |--------------------------------------------------------------------------
            */

            if (!$nip && !$nik) {
                $result['failed']++;

                $result['errors'][] = [
                    'baris' => $index + 2,
                    'nama' => $nama,
                    'nip' => null,
                    'nik' => null,
                    'pesan' => 'NIP dan NIK kosong.',
                ];

                continue;
            }

            $result['total']++;

            /*
            |--------------------------------------------------------------------------
            | SIMPAN IDENTITAS EXCEL
            |--------------------------------------------------------------------------
            */

            if ($nip) {
                $nipExcel[$nip] = true;
            }

            if ($nik) {
                $nikExcel[$nik] = true;
            }

            try {
                /*
                |--------------------------------------------------------------------------
                | CARI PEGAWAI
                |--------------------------------------------------------------------------
                |
                | Prioritas:
                | 1. NIP
                | 2. NIK
                |
                */

                $pegawai = null;

                if ($nip) {
                    $pegawai = SamperinUser::where('user_nip', $nip)->first();
                }

                if (!$pegawai && $nik) {
                    $pegawai = SamperinUser::where('user_nik', $nik)->first();
                }

                /*
                |--------------------------------------------------------------------------
                | VALIDASI NIP + NIK
                |--------------------------------------------------------------------------
                |
                | Jika NIP sama tetapi NIK berbeda, jangan overwrite.
                |
                */

                if ($pegawai && $nip && $nik && $pegawai->user_nip === $nip && $pegawai->user_nik && $pegawai->user_nik !== $nik) {
                    $result['failed']++;

                    $result['errors'][] = [
                        'baris' => $index + 2,
                        'nama' => $nama,
                        'nip' => $nip,
                        'nik' => $nik,
                        'pesan' => 'NIP cocok tetapi NIK berbeda. Data dilewati.',
                    ];

                    Log::warning('SAMPERIN SIMPEG: NIP cocok tetapi NIK berbeda.', [
                        'baris' => $index + 2,
                        'nama' => $nama,
                        'nip' => $nip,
                        'nik_excel' => $nik,
                        'nik_database' => $pegawai->user_nik,
                    ]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | MAPPING DATA SIMPEG
                |--------------------------------------------------------------------------
                */

                $data = $this->mapPegawai($row, $nip, $nik);

                /*
                |--------------------------------------------------------------------------
                | UPDATE
                |--------------------------------------------------------------------------
                */

                if ($pegawai) {
                    $pegawai->fill($data);

                    /*
                    | Ditemukan di SIMPEG = aktif
                    */
                    $pegawai->user_status = 1;

                    $pegawai->save();

                    $result['updated']++;
                }
                /*
                |--------------------------------------------------------------------------
                | INSERT
                |--------------------------------------------------------------------------
                */ else {
                    $data['user_uid'] = (string) Str::uuid();

                    $data['user_status'] = 1;

                    SamperinUser::create($data);

                    $result['inserted']++;
                }
            } catch (\Throwable $e) {
                $result['failed']++;

                $result['errors'][] = [
                    'baris' => $index + 2,
                    'nama' => $nama,
                    'nip' => $nip,
                    'nik' => $nik,
                    'pesan' => $e->getMessage(),
                ];

                Log::error('SAMPERIN SIMPEG SYNC ERROR', [
                    'baris' => $index + 2,
                    'nama' => $nama,
                    'nip' => $nip,
                    'nik' => $nik,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NONAKTIFKAN PEGAWAI YANG TIDAK ADA DI EXCEL
        |--------------------------------------------------------------------------
        */

        $result['deactivated'] = $this->deactivateMissing($nipExcel, $nikExcel);

        return $result;
    }

    /**
     * Mapping data Excel SIMPEG ke samperin_user.
     *
     * CATATAN:
     * user_lokasikerja TIDAK DIUBAH.
     */
    private function mapPegawai(Collection $row, ?string $nip, ?string $nik): array
    {
        $jabatan = $this->findJabatan($this->getValue($row, 'jabatan'));

        $golongan = $this->findGolongan($this->getValue($row, 'golongan'));

        $eselon = $this->findEselon($this->getValue($row, 'eselon'), $this->getValue($row, 'sub_eselon'));

        $pendidikan = $this->findPendidikan($this->getValue($row, 'pendidikan_terakhir'), $this->getValue($row, 'jurusan_pendidikan'));

        $jenisKerja = $this->findJenisKerja($this->getValue($row, 'status_pegawai'));

        return [
            'user_nip' => $nip,

            'user_nik' => $nik,

            'user_nama' => $this->cleanString($this->getValue($row, 'nama')),

            'user_gelardepan' => $this->cleanString($this->getValue($row, 'gelar_depan')),

            'user_gelarbelakang' => $this->cleanString($this->getValue($row, 'gelar_belakang')),

            'user_tempatlahir' => $this->cleanString($this->getValue($row, 'tempat_lahir')),

            'user_tgllahir' => $this->normalizeDate($this->getValue($row, 'tanggal_lahir')),

            'user_jk' => $this->normalizeGender($this->getValue($row, 'jenis_kelamin')),

            'user_notelp' => $this->normalizePhone($this->getValue($row, 'telepon')),

            'user_alamat' => $this->cleanString($this->getValue($row, 'alamat')),

            'user_jabatan_id' => $jabatan?->jabatan_id,

            'user_golongan_id' => $golongan?->golongan_id,

            'user_eselon_id' => $eselon?->eselon_id,

            'user_pendidikan_id' => $pendidikan?->pendidikan_id,

            'user_jenis_kerja_id' => $jenisKerja?->jenis_kerja_id,

            'user_kelasjabatan' => $this->cleanString($this->getValue($row, 'kelas_jabatan')),

            /*
            |--------------------------------------------------------------------------
            | JANGAN MASUKKAN user_lokasikerja
            |--------------------------------------------------------------------------
            |
            | Lokasi kerja adalah data SAMPERIN dan tidak boleh ditimpa
            | oleh sinkronisasi SIMPEG.
            |
            */
        ];
    }

    /**
     * Cari Jabatan.
     */
    private function findJabatan(?string $value)
    {
        $value = $this->cleanString($value);

        if (!$value) {
            return null;
        }

        return DB::table('samperin_jabatan')
            ->where('jabatan_status', 1)
            ->whereRaw('UPPER(TRIM(jabatan_nama)) = ?', [strtoupper($value)])
            ->first();
    }

    /**
     * Cari Golongan.
     */
    private function findGolongan(?string $value)
    {
        $value = $this->cleanString($value);

        if (!$value) {
            return null;
        }

        $value = strtoupper(trim($value));

        return DB::table('samperin_golongan')
            ->where('golongan_status', 1)
            ->where(function ($query) use ($value) {
                $query->whereRaw('UPPER(TRIM(golongan_pangkat)) = ?', [$value]);

                $query->orWhereRaw('UPPER(TRIM(golongan_nama)) = ?', [$value]);
            })
            ->first();
    }

    /**
     * Cari Eselon.
     *
     * SIMPEG:
     * IV + IV.a -> Eselon IVA
     * IV + IV.b -> Eselon IVB
     * III + III.a -> Eselon IIIA
     * III + III.b -> Eselon IIIB
     *
     * Jika Eselon dan Sub Eselon kosong:
     * -> Non Eselon
     */
    private function findEselon(?string $eselon, ?string $subEselon = null)
    {
        $eselon = $this->cleanString($eselon);
        $subEselon = $this->cleanString($subEselon);

        /*
        |--------------------------------------------------------------------------
        | KOSONG = NON ESELON
        |--------------------------------------------------------------------------
        */

        if (!$eselon && !$subEselon) {
            return DB::table('samperin_eselon')
                ->where('eselon_status', 1)
                ->whereRaw('UPPER(TRIM(eselon_nama)) = ?', ['NON ESELON'])
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | SUB ESELON
        |--------------------------------------------------------------------------
        */

        if ($subEselon) {
            $sub = strtoupper(trim($subEselon));

            $sub = str_replace([' ', '-', '_'], '', $sub);

            $map = [
                'I.A' => 'ESELON IA',
                'I.B' => 'ESELON IB',

                'II.A' => 'ESELON IIA',
                'II.B' => 'ESELON IIB',

                'III.A' => 'ESELON IIIA',
                'III.B' => 'ESELON IIIB',

                'IV.A' => 'ESELON IVA',
                'IV.B' => 'ESELON IVB',
            ];

            /*
            | Karena sebelumnya tanda "." dihapus,
            | gunakan bentuk tanpa titik.
            */

            $map = [
                'IA' => 'ESELON IA',
                'IB' => 'ESELON IB',

                'IIA' => 'ESELON IIA',
                'IIB' => 'ESELON IIB',

                'IIIA' => 'ESELON IIIA',
                'IIIB' => 'ESELON IIIB',

                'IVA' => 'ESELON IVA',
                'IVB' => 'ESELON IVB',
            ];

            $namaEselon = $map[$sub] ?? null;

            if ($namaEselon) {
                $result = DB::table('samperin_eselon')
                    ->where('eselon_status', 1)
                    ->whereRaw('UPPER(TRIM(eselon_nama)) = ?', [$namaEselon])
                    ->first();

                if ($result) {
                    return $result;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FALLBACK ESELON
        |--------------------------------------------------------------------------
        */

        if ($eselon) {
            $eselon = strtoupper(trim($eselon));

            $mapEselon = [
                'I' => 'ESELON IA',
                'II' => 'ESELON IIA',
                'III' => 'ESELON IIIA',
                'IV' => 'ESELON IVA',
            ];

            $namaEselon = $mapEselon[$eselon] ?? null;

            if ($namaEselon) {
                return DB::table('samperin_eselon')
                    ->where('eselon_status', 1)
                    ->whereRaw('UPPER(TRIM(eselon_nama)) = ?', [$namaEselon])
                    ->first();
            }
        }

        return null;
    }

    /**
     * Cari Pendidikan berdasarkan jenjang + jurusan.
     */
    private function findPendidikan(?string $jenjang, ?string $jurusan = null)
    {
        $jenjang = $this->cleanString($jenjang);
        $jurusan = $this->cleanString($jurusan);

        if (!$jenjang) {
            return null;
        }

        $jenjang = strtoupper(trim($jenjang));

        /*
        |--------------------------------------------------------------------------
        | Mapping jenjang SIMPEG
        |--------------------------------------------------------------------------
        */

        $jenjangMap = [
            'SD' => 'SD',
            'SEKOLAH DASAR' => 'SD',

            'SMP' => 'SMP',
            'SLTP' => 'SMP',
            'SEKOLAH MENENGAH PERTAMA' => 'SMP',

            'SMA' => 'SMA',
            'SMK' => 'SMA',
            'SLTA' => 'SMA',
            'SEKOLAH MENENGAH ATAS' => 'SMA',
            'SEKOLAH MENENGAH KEJURUAN' => 'SMA',

            'D1' => 'D1',
            'DIPLOMA I' => 'D1',

            'D2' => 'D2',
            'DIPLOMA II' => 'D2',

            'D3' => 'D3',
            'DIPLOMA III' => 'D3',

            'D4' => 'D4',
            'DIPLOMA IV' => 'D4',

            'SARJANA' => 'S1',
            'S1' => 'S1',
            'STRATA I' => 'S1',

            'PASCA SARJANA' => 'S2',
            'MAGISTER' => 'S2',
            'S2' => 'S2',
            'STRATA II' => 'S2',

            'DOKTOR' => 'S3',
            'S3' => 'S3',
            'STRATA III' => 'S3',
        ];

        $jenjangMaster = $jenjangMap[$jenjang] ?? $jenjang;

        /*
        |--------------------------------------------------------------------------
        | Cari jenjang + jurusan
        |--------------------------------------------------------------------------
        */

        if ($jurusan) {
            $pendidikan = DB::table('samperin_pendidikan')
                ->where('pendidikan_status', 1)
                ->whereRaw('UPPER(TRIM(pendidikan_jenjang)) = ?', [$jenjangMaster])
                ->whereRaw('UPPER(TRIM(pendidikan_jurusan)) = ?', [strtoupper(trim($jurusan))])
                ->first();

            if ($pendidikan) {
                return $pendidikan;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Fallback jenjang
        |--------------------------------------------------------------------------
        */

        return DB::table('samperin_pendidikan')
            ->where('pendidikan_status', 1)
            ->whereRaw('UPPER(TRIM(pendidikan_jenjang)) = ?', [$jenjangMaster])
            ->first();
    }

    /**
     * Mapping STATUS PEGAWAI SIMPEG ke Jenis Kerja SAMPERIN.
     */
    private function findJenisKerja(?string $value)
    {
        $value = $this->cleanString($value);

        if (!$value) {
            return null;
        }

        $value = strtoupper(trim($value));

        $kode = match ($value) {
            'PNS' => 'PNS',

            'PPPK' => 'PPPK',

            'PPPK PW', 'PPPK-PW', 'PPPK PARUH WAKTU', 'PPPK PARUH-WAKTU' => 'PPPK-PW',

            'KONTRAK (PJLP)', 'KONTRAK PJLP', 'PJLP' => 'PJLP',

            default => null,
        };

        if (!$kode) {
            return null;
        }

        return DB::table('samperin_jenis_kerja')
            ->where('jenis_kerja_status', 1)
            ->whereRaw('UPPER(TRIM(jenis_kerja_kode)) = ?', [$kode])
            ->first();
    }

    /**
     * Nonaktifkan pegawai SAMPERIN yang tidak ditemukan di Excel.
     */
    private function deactivateMissing(array $nipExcel, array $nikExcel): int
    {
        $count = 0;

        SamperinUser::query()
            ->where('user_status', 1)
            ->chunkById(500, function ($pegawais) use (&$count, $nipExcel, $nikExcel) {
                foreach ($pegawais as $pegawai) {
                    $adaDiExcel = false;

                    /*
                    |--------------------------------------------------------------------------
                    | Jika punya NIP, gunakan NIP
                    |--------------------------------------------------------------------------
                    */

                    if ($pegawai->user_nip) {
                        $nip = $this->normalizeIdentifier($pegawai->user_nip);

                        if ($nip && isset($nipExcel[$nip])) {
                            $adaDiExcel = true;
                        }
                    }
                    /*
                    |--------------------------------------------------------------------------
                    | Jika tidak punya NIP, gunakan NIK
                    |--------------------------------------------------------------------------
                    */ elseif ($pegawai->user_nik) {
                        $nik = $this->normalizeIdentifier($pegawai->user_nik);

                        if ($nik && isset($nikExcel[$nik])) {
                            $adaDiExcel = true;
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Tidak ada di SIMPEG = NONAKTIF
                    |--------------------------------------------------------------------------
                    */

                    if (!$adaDiExcel) {
                        $pegawai->user_status = 0;

                        $pegawai->save();

                        $count++;
                    }
                }
            });

        return $count;
    }

    /**
     * Normalisasi NIP / NIK.
     */
    private function normalizeIdentifier($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        /*
        | Excel sering menyimpan angka sebagai:
        | '196712312000031043
        */

        $value = ltrim($value, "'");

        /*
        | Hilangkan whitespace.
        */

        $value = preg_replace('/\s+/', '', $value);

        return $value !== '' ? $value : null;
    }

    /**
     * Normalisasi nomor telepon.
     */
    private function normalizePhone($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        $value = ltrim($value, "'");

        $value = preg_replace('/\s+/', '', $value);

        return $value !== '' ? $value : null;
    }

    /**
     * Bersihkan string.
     */
    private function cleanString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    /**
     * Normalisasi jenis kelamin.
     */
    private function normalizeGender($value): ?string
    {
        $value = $this->cleanString($value);

        if (!$value) {
            return null;
        }

        $value = strtoupper($value);

        return match ($value) {
            'L', 'LAKI', 'LAKI-LAKI', 'LAKI LAKI', 'PRIA' => 'L',

            'P', 'PEREMPUAN', 'WANITA' => 'P',

            default => null,
        };
    }

    /**
     * Normalisasi tanggal.
     */
    private function normalizeDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Excel serial date
        |--------------------------------------------------------------------------
        */

        if (is_numeric($value) && (float) $value > 1000) {
            try {
                $timestamp = ((float) $value - 25569) * 86400;

                return gmdate('Y-m-d', (int) $timestamp);
            } catch (\Throwable $e) {
                return null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | String date
        |--------------------------------------------------------------------------
        */

        try {
            $value = trim((string) $value);

            $formats = ['d-m-Y', 'd/m/Y', 'Y-m-d', 'Y/m/d'];

            foreach ($formats as $format) {
                $date = \DateTime::createFromFormat($format, $value);

                if ($date && $date->format($format) === $value) {
                    return $date->format('Y-m-d');
                }
            }

            $date = new \DateTime($value);

            return $date->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Ambil value dari Collection / array / object.
     */
    private function getValue($row, string $key)
    {
        if ($row instanceof Collection) {
            return $row->get($key);
        }

        if (is_array($row)) {
            return $row[$key] ?? null;
        }

        if (is_object($row)) {
            return $row->{$key} ?? null;
        }

        return null;
    }
}
