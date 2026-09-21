<?php

namespace App\Services\Samperin;

use App\Imports\SimpegImport;
use App\Models\SamperinUser;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class SimpegSyncService
{
    /**
     * Sinkronisasi Excel SIMPEG ke samperin_user.
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
    */

        $import = new SimpegImport();

        Excel::import($import, $filePath, null, \Maatwebsite\Excel\Excel::XLSX);

        $rows = $import->rows ?? collect();

        if ($rows->isEmpty()) {
            throw new \Exception('File Excel SIMPEG kosong atau tidak memiliki data.');
        }

        /*
    |--------------------------------------------------------------------------
    | BARIS PERTAMA BUKAN DATA
    |--------------------------------------------------------------------------
    |
    | Berdasarkan hasil pengecekan:
    | index 0 = subheader
    | index 1 = pegawai pertama
    |
    */

        $rows = $rows->skip(1)->values();

        if ($rows->isEmpty()) {
            throw new \Exception('File Excel SIMPEG tidak memiliki data pegawai.');
        }

        /*
    |--------------------------------------------------------------------------
    | SIMPAN IDENTITAS YANG ADA DI EXCEL
    |--------------------------------------------------------------------------
    |
    | Digunakan nanti untuk menentukan pegawai mana yang harus
    | dinonaktifkan.
    |
    */

        $nipExcel = [];
        $nikExcel = [];

        /*
    |--------------------------------------------------------------------------
    | PROSES DATA PEGAWAI
    |--------------------------------------------------------------------------
    */

        foreach ($rows as $index => $row) {
            /*
        |--------------------------------------------------------------------------
        | IDENTITAS
        |--------------------------------------------------------------------------
        */

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
        | NIP DAN NIK WAJIB SALAH SATU
        |--------------------------------------------------------------------------
        */

            if (!$nip && !$nik) {
                $result['failed']++;

                $result['errors'][] = [
                    'baris' => $index + 2,
                    'nama' => $nama,
                    'pesan' => 'NIP dan NIK kosong.',
                ];

                continue;
            }

            $result['total']++;

            /*
        |--------------------------------------------------------------------------
        | CATAT IDENTITAS DARI EXCEL
        |--------------------------------------------------------------------------
        */

            if ($nip) {
                $nipExcel[$nip] = true;
            }

            if ($nik) {
                $nikExcel[$nik] = true;
            }

            /*
        |--------------------------------------------------------------------------
        | CEK DUPLIKAT DALAM EXCEL
        |--------------------------------------------------------------------------
        */

            try {
                /*
            |--------------------------------------------------------------------------
            | CARI PEGAWAI DI SAMPERIN
            |--------------------------------------------------------------------------
            |
            | Prioritas:
            | 1. NIP
            | 2. NIK jika NIP tidak tersedia / tidak ditemukan
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
            | VALIDASI NIP VS NIK
            |--------------------------------------------------------------------------
            |
            | Contoh:
            |
            | Database:
            | NIP = 123
            | NIK = 111
            |
            | Excel:
            | NIP = 123
            | NIK = 222
            |
            | Jangan update karena identitas bertentangan.
            |
            */

                if ($pegawai && $nip && $nik && $pegawai->user_nip === $nip && $pegawai->user_nik && $pegawai->user_nik !== $nik) {
                    $result['failed']++;

                    $result['errors'][] = [
                        'baris' => $index + 2,
                        'nama' => $nama,
                        'nip' => $nip,
                        'nik_excel' => $nik,
                        'nik_database' => $pegawai->user_nik,
                        'pesan' => 'NIP cocok tetapi NIK berbeda. Data dilewati.',
                    ];

                    Log::warning('SAMPERIN SIMPEG: NIP cocok tetapi NIK berbeda.', [
                        'nip' => $nip,
                        'nik_excel' => $nik,
                        'nik_database' => $pegawai->user_nik,
                        'nama' => $nama,
                    ]);

                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | MAPPING DATA
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
                | Pegawai ditemukan di SIMPEG berarti aktif.
                */
                    $pegawai->user_status = 1;

                    $pegawai->save();

                    $result['updated']++;
                } /*
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
    |
    | Asumsi:
    | Excel SIMPEG adalah full dataset pegawai Dinas Kebudayaan.
    |
    */

        $result['deactivated'] = $this->deactivateMissing($nipExcel, $nikExcel);

        return $result;
    }

    /**
     * Mapping Excel → samperin_user.
     */
    private function mapPegawai(Collection $row, ?string $nip, ?string $nik): array
    {
        $jabatan = $this->findJabatan($this->getValue($row, 'jabatan'));

        $golongan = $this->findGolongan($this->getValue($row, 'golongan'));

        $eselon = $this->findEselon($this->getValue($row, 'eselon'), $this->getValue($row, 'sub_eselon'));

        $pendidikan = $this->findPendidikan($this->getValue($row, 'pendidikan_terakhir'), $this->getValue($row, 'jurusan_pendidikan'));

        $jenisKerja = $this->findJenisKerja($this->getValue($row, 'status_pegawai'));

        $tmt = null;

        if ($jenisKerja) {
            $tmtValue = match (strtoupper(trim($jenisKerja->jenis_kerja_kode))) {
                'PNS' => $this->getValue($row, 'tmt_pns'),

                'PPPK', 'PPPK-PW' => $this->getValue($row, 'tmt_pppk'),

                'PJLP' => $this->getValue($row, 'tmt_non_pns'),

                default => null,
            };

            $tmt = $this->normalizeDate($tmtValue);
        }

        return [
            'user_nip' => $nip,

            'user_nik' => $nik,

            'user_nama' => $this->cleanString($this->getValue($row, 'nama')),

            'user_tmt' => $tmt,

            'user_tmt_berkala' => $tmt,

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
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MASTER LOOKUP
    |--------------------------------------------------------------------------
    */

    private function findJabatan($value)
    {
        $value = $this->cleanString($value);

        if (!$value) {
            return null;
        }

        return DB::table('samperin_jabatan')
            ->whereRaw('LOWER(TRIM(jabatan_nama)) = ?', [strtolower($value)])
            ->first();
    }

    private function findBidang($value)
    {
        $value = $this->cleanString($value);

        if (!$value) {
            return null;
        }

        return DB::table('samperin_bidang')
            ->whereRaw('LOWER(TRIM(bidang_nama)) = ?', [strtolower($value)])
            ->first();
    }

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

    private function findEselon(?string $eselon, ?string $subEselon = null)
    {
        $eselon = $this->cleanString($eselon);
        $subEselon = $this->cleanString($subEselon);

        /*
    |--------------------------------------------------------------------------
    | ESELON & SUB ESELON KOSONG
    | → NON ESELON
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
    | PRIORITAS SUB ESELON
    |--------------------------------------------------------------------------
    |
    | IV.a  → Eselon IVA
    | IV.b  → Eselon IVB
    | III.a → Eselon IIIA
    | III.b → Eselon IIIB
    | II.a  → Eselon IIA
    | II.b  → Eselon IIB
    | I.a   → Eselon IA
    | I.b   → Eselon IB
    |
    */

        if ($subEselon) {
            $sub = strtoupper(trim($subEselon));

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
    | FALLBACK BERDASARKAN ESELON
    |--------------------------------------------------------------------------
    */

        if ($eselon) {
            $eselon = strtoupper(trim($eselon));

            /*
        | Kalau Excel hanya memberi:
        | IV → cari Eselon IVA sebagai fallback
        | III → cari Eselon IIIA
        */

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
    | Mapping jenjang SIMPEG ke jenjang SAMPERIN
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
    | Jika jurusan tidak ditemukan
    |--------------------------------------------------------------------------
    |
    | Tetap cari berdasarkan jenjang.
    | Ini mencegah data menjadi gagal hanya karena
    | penulisan jurusan berbeda.
    |
    */

        return DB::table('samperin_pendidikan')
            ->where('pendidikan_status', 1)
            ->whereRaw('UPPER(TRIM(pendidikan_jenjang)) = ?', [$jenjangMaster])
            ->first();
    }

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

            'KONTRAK', 'KONTRAK (PJLP)', 'KONTRAK PJLP', 'PJLP' => 'PJLP',

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

    /*
    |--------------------------------------------------------------------------
    | IDENTIFIER
    |--------------------------------------------------------------------------
    */

    private function normalizeIdentifier($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        /*
        | Excel apostrophe
        */

        $value = ltrim($value, "'");

        /*
        | Hilangkan whitespace
        */

        $value = preg_replace('/\s+/', '', $value);

        if ($value === '') {
            return null;
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | PHONE
    |--------------------------------------------------------------------------
    */

    private function normalizePhone($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        $value = ltrim($value, "'");

        $value = preg_replace('/\s+/', '', $value);

        return $value === '' ? null : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | STRING
    |--------------------------------------------------------------------------
    */

    private function cleanString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /*
    |--------------------------------------------------------------------------
    | GENDER
    |--------------------------------------------------------------------------
    */

    private function normalizeGender($value): ?string
    {
        if (!$value) {
            return null;
        }

        $value = strtoupper(trim((string) $value));

        return match ($value) {
            'L', 'LAKI', 'LAKI-LAKI', 'LAKI LAKI', 'PRIA' => 'L',

            'P', 'PEREMPUAN', 'WANITA' => 'P',

            default => null,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | DATE
    |--------------------------------------------------------------------------
    */

    private function normalizeDate($value): ?string
    {
        if (!$value) {
            return null;
        }

        try {
            /*
            | Excel serial date
            */

            if (is_numeric($value) && (int) $value > 1000) {
                return Carbon::create(1899, 12, 30)->addDays((int) $value)->format('Y-m-d');
            }

            return Carbon::parse($value)->format('Y-m-d');
        } catch (Throwable $e) {
            return null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET VALUE
    |--------------------------------------------------------------------------
    */

    private function getValue($row, string $key)
    {
        if ($row instanceof Collection) {
            return $row->get($key);
        }

        if (is_array($row)) {
            return $row[$key] ?? null;
        }

        return $row->{$key} ?? null;
    }

    /*
    |--------------------------------------------------------------------------
    | DEACTIVATE MISSING
    |--------------------------------------------------------------------------
    */

    private function deactivateMissing(array $nipExcel, array $nikExcel): int
    {
        $count = 0;

        SamperinUser::where('user_status', 1)->chunkById(500, function ($users) use (&$count, $nipExcel, $nikExcel) {
            foreach ($users as $user) {
                $nip = $this->normalizeIdentifier($user->user_nip);

                $nik = $this->normalizeIdentifier($user->user_nik);

                $exists = false;

                /*
                        |--------------------------------------------------------------------------
                        | USER PUNYA NIP
                        |--------------------------------------------------------------------------
                        */

                if ($nip) {
                    $exists = isset($nipExcel[$nip]);
                } /*
                        |--------------------------------------------------------------------------
                        | USER TANPA NIP → NIK
                        |--------------------------------------------------------------------------
                        */ elseif ($nik) {
                    $exists = isset($nikExcel[$nik]);
                }

                /*
                        |--------------------------------------------------------------------------
                        | TIDAK ADA DI EXCEL
                        |--------------------------------------------------------------------------
                        */

                if (!$exists) {
                    $user->user_status = 0;

                    $user->save();

                    $count++;
                }
            }
        });

        return $count;
    }
}