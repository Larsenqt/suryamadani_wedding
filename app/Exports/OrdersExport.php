<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Http\Request;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $orders;
    protected $startDate;
    protected $endDate;
    protected $month;
    protected $year;
    protected $status;

    public function __construct($orders, $startDate = null, $endDate = null, $month = null, $year = null, $status = null)
    {
        $this->orders = $orders;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->month = $month;
        $this->year = $year;
        $this->status = $status;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'NO',
            'ID ORDER',
            'TANGGAL ORDER',
            'TANGGAL SEWA',
            'PELANGGAN',
            'EMAIL',
            'TELEPON',
            'ALAMAT',
            'TOTAL HARGA',
            'STATUS',
            'NO INVOICE',
            'CATATAN',
            'APPROVED AT',
        ];
    }

    public function map($order): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        return [
            $rowNumber,
            $order->uuid,
            $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-',
            $order->order_date ? date('d/m/Y', strtotime($order->order_date)) : '-',
            $order->user->name ?? '-',
            $order->user->email ?? '-',
            $order->phone ?? '-',
            $order->address ?? '-',
            'Rp ' . number_format($order->total_price, 0, ',', '.'),
            $this->getStatusText($order->status),
            $order->invoice_number ?? '-',
            $order->notes ?? '-',
            $order->approved_at ? date('d/m/Y H:i', strtotime($order->approved_at)) : '-',
        ];
    }

    private function getStatusText($status)
    {
        switch($status) {
            case 'pending': return 'Pending';
            case 'approved': return 'Disetujui';
            case 'completed': return 'Selesai';
            case 'rejected': return 'Ditolak';
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

                // Border untuk semua data
                $sheet->getStyle('A1:' . $highestColumn . $highestRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Alignment untuk kolom tertentu
                $sheet->getStyle('A:A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I:I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                
                // Set tinggi baris header
                $sheet->getRowDimension(1)->setRowHeight(20);
            },
        ];
    }
}