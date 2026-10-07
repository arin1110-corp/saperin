<?php

namespace App\Exports;

use App\Models\SamperinPermintaanBerkas;
use App\Models\SamperinPengumpulanBerkas;
use App\Models\SamperinUser;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SamperinRekapBerkasExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithColumnWidths,
    WithEvents
{
    protected SamperinPermintaanBerkas $permintaan;

    /**
     * ============================================================
     * CONSTRUCTOR
     * ============================================================
     */
    public function __construct(SamperinPermintaanBerkas $permintaan)
    {
        $this->permintaan = $permintaan;
    }

    /**
     * ============================================================
     * COLLECTION
     * ============================================================
     */
    public function collection(): Collection
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil target aktif
        |--------------------------------------------------------------------------
        */

        $targets = $this->permintaan
            ->target()
            ->with([
                'folder',
                'jenisKerja',
            ])
            ->where('target_status', true)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ambil ID jenis kerja target
        |--------------------------------------------------------------------------
        */

        $jenisKerjaIds = $targets
            ->whereNotNull('target_jenis_kerja_id')
            ->pluck('target_jenis_kerja_id')
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Cek apakah target semua jenis kerja
        |--------------------------------------------------------------------------
        */

        $semuaJenisKerja = $targets->contains(function ($target) {
            return strtoupper(
                trim((string) ($target->target_jenis_kerja ?? ''))
            ) === 'SEMUA';
        });

        /*
        |--------------------------------------------------------------------------
        | Ambil pegawai aktif
        |--------------------------------------------------------------------------
        */

        $users = SamperinUser::query()
            ->with([
                'jenisKerja',
                'bidang',
            ])
            ->where('user_status', true)
            ->when(
                !$semuaJenisKerja,
                function ($query) use ($jenisKerjaIds) {
                    $query->whereIn(
                        'user_jenis_kerja_id',
                        $jenisKerjaIds
                    );
                }
            )
            ->orderBy('user_nama')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ambil data pengumpulan
        |--------------------------------------------------------------------------
        */

        $pengumpulan = SamperinPengumpulanBerkas::query()
            ->where(
                'pengumpulan_berkas_permintaan_id',
                $this->permintaan->permintaan_id
            )
            ->get()
            ->keyBy('pengumpulan_berkas_user_uid');

        /*
        |--------------------------------------------------------------------------
        | Bentuk data Excel
        |--------------------------------------------------------------------------
        */

        return $users->map(function ($user) use ($pengumpulan) {

            $berkas = $pengumpulan->get(
                $user->user_uid
            );

            /*
            |--------------------------------------------------------------------------
            | File
            |--------------------------------------------------------------------------
            |
            | Kalau ada berkas:
            | ambil URL Google Drive dari pengumpulan_berkas_file
            |
            | Kalau belum:
            | BELUM MENGUMPULKAN
            |
            */

            $file = $berkas
                ? (
                    $berkas->pengumpulan_berkas_file
                    ?: 'Sudah Mengumpulkan'
                )
                : 'BELUM MENGUMPULKAN';

            return [
                $user->user_nik ?: '-',

                $user->user_nip ?: '-',

                $user->user_nama ?: '-',

                $user->jenisKerja?->jenis_kerja_nama ?: '-',

                $user->bidang?->bidang_nama ?: '-',

                $file,
            ];
        });
    }

    /**
     * ============================================================
     * HEADINGS
     * ============================================================
     */
    public function headings(): array
    {
        return [
            'NIK',
            'NIP',
            'Nama',
            'Jenis Kerja',
            'Bidang',
            'File',
        ];
    }

    /**
     * ============================================================
     * COLUMN WIDTH
     * ============================================================
     */
    public function columnWidths(): array
    {
        return [
            'A' => 22, // NIK
            'B' => 22, // NIP
            'C' => 32, // Nama
            'D' => 22, // Jenis Kerja
            'E' => 30, // Bidang
            'F' => 55, // File / Link Drive
        ];
    }

    /**
     * ============================================================
     * STYLES
     * ============================================================
     */
    public function styles(Worksheet $sheet): array
    {
        $highestRow = $sheet->getHighestRow();

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,

                'color' => [
                    'rgb' => 'FFFFFF',
                ],

                'size' => 11,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,

                'startColor' => [
                    'rgb' => '1F2937',
                ],
            ],

            'alignment' => [
                'horizontal' =>
                    Alignment::HORIZONTAL_CENTER,

                'vertical' =>
                    Alignment::VERTICAL_CENTER,

                'wrapText' => true,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' =>
                        Border::BORDER_THIN,

                    'color' => [
                        'rgb' => 'D1D5DB',
                    ],
                ],
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        if ($highestRow >= 2) {

            /*
            |--------------------------------------------------------------------------
            | Semua cell
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle(
                'A2:F' . $highestRow
            )->applyFromArray([

                'alignment' => [

                    'vertical' =>
                        Alignment::VERTICAL_CENTER,

                    'wrapText' => true,
                ],

                'borders' => [

                    'allBorders' => [

                        'borderStyle' =>
                            Border::BORDER_THIN,

                        'color' => [
                            'rgb' => 'E5E7EB',
                        ],
                    ],
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | NIK
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle(
                'A2:A' . $highestRow
            )->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                )
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                )
                ->setWrapText(true);

            /*
            |--------------------------------------------------------------------------
            | NIP
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle(
                'B2:B' . $highestRow
            )->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                )
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                )
                ->setWrapText(true);

            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle(
                'C2:C' . $highestRow
            )->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_LEFT
                )
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                )
                ->setWrapText(true);

            /*
            |--------------------------------------------------------------------------
            | JENIS KERJA
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle(
                'D2:D' . $highestRow
            )->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_LEFT
                )
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                )
                ->setWrapText(true);

            /*
            |--------------------------------------------------------------------------
            | BIDANG
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle(
                'E2:E' . $highestRow
            )->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_LEFT
                )
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                )
                ->setWrapText(true);

            /*
            |--------------------------------------------------------------------------
            | FILE
            |--------------------------------------------------------------------------
            */

            $sheet->getStyle(
                'F2:F' . $highestRow
            )->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_LEFT
                )
                ->setVertical(
                    Alignment::VERTICAL_CENTER
                )
                ->setWrapText(true);
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER HEIGHT
        |--------------------------------------------------------------------------
        */

        $sheet
            ->getRowDimension(1)
            ->setRowHeight(28);

        return [];
    }

    /**
     * ============================================================
     * EVENTS
     * ============================================================
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event
                    ->sheet
                    ->getDelegate();

                $highestRow = $sheet->getHighestRow();

                /*
                |--------------------------------------------------------------------------
                | AUTO HEIGHT DATA
                |--------------------------------------------------------------------------
                */

                for (
                    $row = 2;
                    $row <= $highestRow;
                    $row++
                ) {

                    $sheet
                        ->getRowDimension($row)
                        ->setRowHeight(-1);
                }

                /*
                |--------------------------------------------------------------------------
                | PROSES KOLOM FILE
                |--------------------------------------------------------------------------
                */

                for (
                    $row = 2;
                    $row <= $highestRow;
                    $row++
                ) {

                    $cell = $sheet->getCell(
                        'F' . $row
                    );

                    $value = $cell->getValue();

                    /*
                    |--------------------------------------------------------------------------
                    | BELUM MENGUMPULKAN
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $value ===
                        'BELUM MENGUMPULKAN'
                    ) {

                        $sheet
                            ->getStyle('F' . $row)
                            ->applyFromArray([

                                'font' => [

                                    'bold' => true,

                                    'color' => [
                                        'rgb' => 'DC2626',
                                    ],
                                ],

                                'fill' => [

                                    'fillType' =>
                                        Fill::FILL_SOLID,

                                    'startColor' => [
                                        'rgb' => 'FEE2E2',
                                    ],
                                ],

                                'alignment' => [

                                    'horizontal' =>
                                        Alignment::HORIZONTAL_CENTER,

                                    'vertical' =>
                                        Alignment::VERTICAL_CENTER,

                                    'wrapText' => true,
                                ],
                            ]);

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | LINK GOOGLE DRIVE
                    |--------------------------------------------------------------------------
                    */

                    if (
                        is_string($value) &&
                        filter_var(
                            $value,
                            FILTER_VALIDATE_URL
                        )
                    ) {

                        /*
                        |--------------------------------------------------------------------------
                        | Jadikan URL hyperlink
                        |--------------------------------------------------------------------------
                        */

                        $cell
                            ->getHyperlink()
                            ->setUrl($value);

                        /*
                        |--------------------------------------------------------------------------
                        | Warna hyperlink
                        |--------------------------------------------------------------------------
                        */

                        $sheet
                            ->getStyle('F' . $row)
                            ->getFont()
                            ->setColor(
                                new Color('0563C1')
                            )
                            ->setUnderline(true);

                        /*
                        |--------------------------------------------------------------------------
                        | Alignment
                        |--------------------------------------------------------------------------
                        */

                        $sheet
                            ->getStyle('F' . $row)
                            ->getAlignment()
                            ->setHorizontal(
                                Alignment::HORIZONTAL_LEFT
                            )
                            ->setVertical(
                                Alignment::VERTICAL_CENTER
                            )
                            ->setWrapText(true);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | FREEZE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A2');

                /*
                |--------------------------------------------------------------------------
                | AUTO FILTER
                |--------------------------------------------------------------------------
                */

                if ($highestRow >= 1) {

                    $sheet->setAutoFilter(
                        'A1:F' . $highestRow
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | PRINT / PAGE SETUP
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getPageSetup()
                    ->setOrientation(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE
                    );

                $sheet
                    ->getPageSetup()
                    ->setPaperSize(
                        \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4
                    );

                $sheet
                    ->getPageSetup()
                    ->setFitToWidth(1);

                $sheet
                    ->getPageSetup()
                    ->setFitToHeight(0);

                $sheet
                    ->getPageMargins()
                    ->setTop(0.4);

                $sheet
                    ->getPageMargins()
                    ->setRight(0.3);

                $sheet
                    ->getPageMargins()
                    ->setBottom(0.4);

                $sheet
                    ->getPageMargins()
                    ->setLeft(0.3);
            },
        ];
    }
}