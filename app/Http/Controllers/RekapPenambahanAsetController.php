<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class RekapPenambahanAsetController extends Controller
{
    // Warna tema ungu
    private const COLOR_PRIMARY = '5B21B6';        // Ungu tua (header utama)
    private const COLOR_SECONDARY = '7C3AED';      // Ungu medium (sub-header)
    private const COLOR_LIGHT = 'EDE9FE';          // Ungu muda (zebra)
    private const COLOR_WHITE = 'FFFFFF';
    private const COLOR_HEADER_TEXT = 'FFFFFF';     // Putih
    private const COLOR_BORDER = 'A78BFA';          // Ungu border

    public function export(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('PENAMBAHAN ASET');

        // ============================================================
        // BAGIAN 1: PENAMBAHAN ASET PER QUARTER PER TAHUN
        // ============================================================

        // Ambil rentang tahun dari request (user pilih di Generate Laporan)
        $yearFrom = (int) $request->input('year_from', date('Y') - 2);
        $yearTo = (int) $request->input('year_to', date('Y'));

        // Pastikan year_from <= year_to
        if ($yearFrom > $yearTo) {
            [$yearFrom, $yearTo] = [$yearTo, $yearFrom];
        }

        $years = range($yearFrom, $yearTo);

        // Hitung data per tahun per quarter
        $yearlyData = [];
        foreach ($years as $year) {
            $yearData = ['year' => $year, 'quarters' => [], 'grand_total_units' => 0, 'grand_total_cost' => 0];
            for ($q = 1; $q <= 4; $q++) {
                $startMonth = ($q - 1) * 3 + 1;
                $endMonth = $q * 3;

                $assets = Asset::where(function ($query) use ($year, $startMonth, $endMonth) {
                    $query->where(function ($q2) use ($year, $startMonth, $endMonth) {
                        $q2->whereNotNull('purchase_date')
                           ->whereRaw("EXTRACT(YEAR FROM purchase_date) = ?", [$year])
                           ->whereRaw("EXTRACT(MONTH FROM purchase_date) >= ?", [$startMonth])
                           ->whereRaw("EXTRACT(MONTH FROM purchase_date) <= ?", [$endMonth]);
                    })->orWhere(function ($q2) use ($year, $startMonth, $endMonth) {
                        // Fallback: jika hanya purchase_year tanpa purchase_date
                        $q2->whereNull('purchase_date')
                           ->where('purchase_year', $year);
                        // Untuk yang hanya punya purchase_year, masukkan semua ke Q1
                        if ($startMonth !== 1) {
                            $q2->whereRaw('1 = 0'); // exclude dari Q2-Q4
                        }
                    });
                })->get();

                $units = $assets->count();
                $cost = $assets->sum('purchase_cost');

                $yearData['quarters'][$q] = ['units' => $units, 'cost' => $cost];
                $yearData['grand_total_units'] += $units;
                $yearData['grand_total_cost'] += $cost;
            }
            $yearlyData[] = $yearData;
        }

        // --- RENDER BAGIAN 1 ---

        // Row 1: Title
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A1', 'PENAMBAHAN ASET - GONDOWANGI GROUP');
        $this->applyStyle($sheet, 'A1:N1', self::COLOR_PRIMARY, self::COLOR_HEADER_TEXT, 16, true, Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(40);

        // Row 2: Subtitle
        $sheet->mergeCells('A2:N2');
        $sheet->setCellValue('A2', 'Rekap aset baru yang ditambahkan ke inventaris berdasarkan tahun pembelian & keterangan bulan');
        $this->applyStyle($sheet, 'A2:N2', self::COLOR_SECONDARY, self::COLOR_HEADER_TEXT, 10, false, Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(25);

        // Row 3: Empty separator
        $sheet->getRowDimension(3)->setRowHeight(8);

        // Row 4: Quarter headers
        $quarterLabels = ['Q1 - Jan-Mar', 'Q2 - Apr-Jun', 'Q3 - Jul-Sep', 'Q4 - Okt-Des'];
        $colIndex = 1; // A=1
        foreach ($quarterLabels as $label) {
            $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 2);
            $sheet->mergeCells("{$startCol}4:{$endCol}4");
            $sheet->setCellValue("{$startCol}4", $label);
            $this->applyStyle($sheet, "{$startCol}4:{$endCol}4", self::COLOR_PRIMARY, self::COLOR_HEADER_TEXT, 11, true, Alignment::HORIZONTAL_CENTER);
            $colIndex += 3;
        }
        // Grand Total header
        $startCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
        $endCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
        $sheet->mergeCells("{$startCol}4:{$endCol}4");
        $sheet->setCellValue("{$startCol}4", 'GRAND TOTAL');
        $this->applyStyle($sheet, "{$startCol}4:{$endCol}4", self::COLOR_PRIMARY, self::COLOR_HEADER_TEXT, 11, true, Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // Row 5+: Data rows per year
        $dataRow = 5;
        foreach ($yearlyData as $idx => $yd) {
            $colIndex = 1;
            $bgColor = $idx % 2 === 0 ? self::COLOR_WHITE : self::COLOR_LIGHT;

            for ($q = 1; $q <= 4; $q++) {
                $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
                $col3 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 2);

                $sheet->setCellValue("{$col1}{$dataRow}", $yd['year']);
                $sheet->setCellValue("{$col2}{$dataRow}", $yd['quarters'][$q]['units'] . ' Unit');
                $sheet->setCellValue("{$col3}{$dataRow}", $this->formatRupiah($yd['quarters'][$q]['cost']));

                $this->applyDataRowStyle($sheet, "{$col1}{$dataRow}:{$col3}{$dataRow}", $bgColor);
                $sheet->getStyle("{$col1}{$dataRow}")->getFont()->setBold(true);
                $sheet->getStyle("{$col3}{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $colIndex += 3;
            }

            // Grand Total columns
            $col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $col2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1);
            $sheet->setCellValue("{$col1}{$dataRow}", $yd['grand_total_units'] . ' Unit');
            $sheet->setCellValue("{$col2}{$dataRow}", $this->formatRupiah($yd['grand_total_cost']));
            $this->applyDataRowStyle($sheet, "{$col1}{$dataRow}:{$col2}{$dataRow}", $bgColor);
            $sheet->getStyle("{$col1}{$dataRow}")->getFont()->setBold(true);
            $sheet->getStyle("{$col2}{$dataRow}")->getFont()->setBold(true);
            $sheet->getStyle("{$col2}{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            $sheet->getRowDimension($dataRow)->setRowHeight(25);
            $dataRow++;
        }

        // ============================================================
        // BAGIAN 2: RINGKASAN PER JENIS ASSET & QUARTER
        // ============================================================

        $dataRow += 1; // blank row separator
        $sectionStartRow = $dataRow;

        // Title row
        $sheet->mergeCells("A{$dataRow}:N{$dataRow}");
        $sheet->setCellValue("A{$dataRow}", 'RINGKASAN PENAMBAHAN BERDASARKAN JENIS ASSET & QUARTER');
        $this->applyStyle($sheet, "A{$dataRow}:N{$dataRow}", self::COLOR_PRIMARY, self::COLOR_HEADER_TEXT, 12, true, Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($dataRow)->setRowHeight(35);
        $dataRow++;

        // Blank row
        $sheet->getRowDimension($dataRow)->setRowHeight(8);
        $dataRow++;

        // Ambil semua kategori
        $categories = Category::orderBy('name')->pluck('name', 'id')->toArray();

        // Build header columns: JENIS ASSET | Q1 Y1 | Q2 Y1 | Q3 Y1 | Q4 Y1 | TOTAL Y1 | Q1 Y2 | ...
        $headerRow = $dataRow;
        $sheet->setCellValue("A{$headerRow}", 'JENIS ASSET');
        $this->applyStyle($sheet, "A{$headerRow}", self::COLOR_PRIMARY, self::COLOR_HEADER_TEXT, 10, true, Alignment::HORIZONTAL_CENTER);

        $colIndex = 2; // Start from B
        foreach ($years as $year) {
            for ($q = 1; $q <= 4; $q++) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue("{$col}{$headerRow}", "Q{$q} {$year}");
                $this->applyStyle($sheet, "{$col}{$headerRow}", self::COLOR_SECONDARY, self::COLOR_HEADER_TEXT, 9, true, Alignment::HORIZONTAL_CENTER);
                $colIndex++;
            }
            // TOTAL Year
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue("{$col}{$headerRow}", "TOTAL {$year}");
            $this->applyStyle($sheet, "{$col}{$headerRow}", self::COLOR_PRIMARY, self::COLOR_HEADER_TEXT, 9, true, Alignment::HORIZONTAL_CENTER);
            $colIndex++;
        }
        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex - 1);
        $sheet->getRowDimension($headerRow)->setRowHeight(28);
        $dataRow++;

        // Data rows per category
        foreach ($categories as $catId => $catName) {
            $colIndex = 1;
            $bgColor = ($dataRow % 2 === 0) ? self::COLOR_WHITE : self::COLOR_LIGHT;

            $sheet->setCellValue("A{$dataRow}", $catName);
            $sheet->getStyle("A{$dataRow}")->getFont()->setBold(true);
            $sheet->getStyle("A{$dataRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);

            $colIndex = 2;
            foreach ($years as $year) {
                $yearTotal = 0;
                for ($q = 1; $q <= 4; $q++) {
                    $startMonth = ($q - 1) * 3 + 1;
                    $endMonth = $q * 3;

                    $count = Asset::whereHas('assetModel', function ($query) use ($catId) {
                        $query->where('category_id', $catId);
                    })->where(function ($query) use ($year, $startMonth, $endMonth) {
                        $query->where(function ($q2) use ($year, $startMonth, $endMonth) {
                            $q2->whereNotNull('purchase_date')
                               ->whereRaw("EXTRACT(YEAR FROM purchase_date) = ?", [$year])
                               ->whereRaw("EXTRACT(MONTH FROM purchase_date) >= ?", [$startMonth])
                               ->whereRaw("EXTRACT(MONTH FROM purchase_date) <= ?", [$endMonth]);
                        })->orWhere(function ($q2) use ($year, $startMonth, $endMonth) {
                            $q2->whereNull('purchase_date')
                               ->where('purchase_year', $year);
                            if ($startMonth !== 1) {
                                $q2->whereRaw('1 = 0');
                            }
                        });
                    })->count();

                    $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                    $sheet->setCellValue("{$col}{$dataRow}", $count > 0 ? $count : '-');
                    $sheet->getStyle("{$col}{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("{$col}{$dataRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);

                    $yearTotal += $count;
                    $colIndex++;
                }

                // TOTAL Year for this category
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue("{$col}{$dataRow}", $yearTotal > 0 ? $yearTotal : '-');
                $sheet->getStyle("{$col}{$dataRow}")->getFont()->setBold(true);
                $sheet->getStyle("{$col}{$dataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("{$col}{$dataRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);
                $colIndex++;
            }

            // Apply border to entire row
            $sheet->getStyle("A{$dataRow}:{$lastCol}{$dataRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB(self::COLOR_BORDER);
            $sheet->getRowDimension($dataRow)->setRowHeight(22);
            $dataRow++;
        }

        // ============================================================
        // AUTO-WIDTH COLUMNS
        // ============================================================
        $highestCol = $sheet->getHighestColumn();
        $highestColIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);
        for ($i = 1; $i <= $highestColIndex; $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // ============================================================
        // OUTPUT FILE
        // ============================================================
        $filename = 'Penambahan_Aset_Gondowangi_' . date('Ymd_His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // ============================================================
    // HELPER STYLING METHODS
    // ============================================================

    private function applyStyle($sheet, string $range, string $bgColor, string $fontColor, int $fontSize, bool $bold, string $horizontalAlign): void
    {
        $style = $sheet->getStyle($range);
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);
        $style->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($fontColor))->setSize($fontSize)->setBold($bold);
        $style->getAlignment()->setHorizontal($horizontalAlign)->setVertical(Alignment::VERTICAL_CENTER);
        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB(self::COLOR_BORDER);
    }

    private function applyDataRowStyle($sheet, string $range, string $bgColor): void
    {
        $style = $sheet->getStyle($range);
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($bgColor);
        $style->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB(self::COLOR_BORDER);
    }

    private function formatRupiah(float $amount): string
    {
        if ($amount <= 0) {
            return 'Rp0';
        }
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
