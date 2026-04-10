<?php
// app/Exports/InvoicesExport.php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $invoices;

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    public function collection()
    {
        return $this->invoices;
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO INVOICE',
            'TANGGAL INVOICE',
            'TANGGAL JATUH TEMPO',
            'PELANGGAN',
            'EMAIL',
            'TELEPON',
            'ALAMAT',
            'SUBTOTAL',
            'PAJAK',
            'DISKON',
            'DP',
            'SISA PEMBAYARAN',
            'TOTAL',
            'STATUS',
            'CATATAN',
        ];
    }

    public function map($invoice): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        return [
            $rowNumber,
            $invoice->invoice_number,
            $invoice->order_date ? $invoice->order_date->format('d/m/Y') : '-',
            $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-',
            $invoice->customer_name ?? '-',
            $invoice->customer_email ?? '-',
            $invoice->customer_phone ?? '-',
            $invoice->customer_address ?? '-',
            'Rp ' . number_format($invoice->subtotal, 0, ',', '.'),
            $invoice->tax . '%',
            'Rp ' . number_format($invoice->discount, 0, ',', '.'),
            'Rp ' . number_format($invoice->dp_amount, 0, ',', '.'),
            'Rp ' . number_format($invoice->remaining_amount, 0, ',', '.'),
            'Rp ' . number_format($invoice->total_amount, 0, ',', '.'),
            $this->getStatusText($invoice->status),
            $invoice->notes ?? '-',
        ];
    }

    private function getStatusText($status)
    {
        switch($status) {
            case 'draft': return 'Draft';
            case 'approved': return 'Disetujui';
            case 'paid': return 'Lunas';
            case 'cancelled': return 'Dibatalkan';
            default: return $status;
        }
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E8F0']],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                
                $sheet->getRowDimension(1)->setRowHeight(20);
            },
        ];
    }
}