<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(10);
        
        // Data untuk filter di view
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $years = Order::selectRaw('YEAR(created_at) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $statuses = ['pending', 'approved', 'completed', 'rejected'];
        
        return view('admin.orders.index', compact('orders', 'months', 'years', 'statuses'));
    }

    public function show($uuid)
    {
        $order = Order::with('details.item', 'user')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

    public function edit($uuid)
    {
        $order = Order::with('details.item', 'user')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return view('admin.orders.edit', compact('order'));
    }

    public function destroy($uuid)
{
    $order = Order::where('uuid', $uuid)->firstOrFail();
    
    try {
        // Cek status order sebelum hapus
        // Jika order sudah approved, kembalikan stok terlebih dahulu
        if ($order->status == 'approved' || $order->status == 'completed') {
            foreach ($order->details as $detail) {
                $item = $detail->item;
                $item->stock += $detail->qty;
                $item->save();
            }
        }
        
        // Simpan informasi untuk pesan sukses
        $orderId = $order->uuid;
        $orderNumber = '#' . substr($order->uuid, 0, 8);
        
        // Hapus order beserta detailnya (cascade akan menangani detail)
        $order->delete();
        
        return redirect()->route('admin.orders.index')
            ->with('success', "Pesanan {$orderNumber} berhasil dihapus" . 
                   ($order->status == 'approved' || $order->status == 'completed' ? 
                   ' dan stok telah dikembalikan' : ''));
                   
    } catch (\Exception $e) {
        return redirect()->route('admin.orders.index')
            ->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
    }
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,uuid'
        ]);
        
        $deletedCount = 0;
        $errors = [];
        
        foreach ($request->order_ids as $uuid) {
            try {
                $order = Order::where('uuid', $uuid)->first();
                
                if ($order) {

                    if ($order->status == 'approved' || $order->status == 'completed') {
                        foreach ($order->details as $detail) {
                            $item = $detail->item;
                            $item->stock += $detail->qty;
                            $item->save();
                        }
                    }
                    
                    $order->delete();
                    $deletedCount++;
                }
            } catch (\Exception $e) {
                $errors[] = '#' . substr($uuid, 0, 8);
            }
        }
        
        if ($deletedCount > 0) {
            $message = "Berhasil menghapus {$deletedCount} pesanan";
            if (!empty($errors)) {
                $message .= ". Gagal menghapus: " . implode(', ', $errors);
            }
            return redirect()->route('admin.orders.index')
                ->with('success', $message);
        }
        
        return redirect()->route('admin.orders.index')
            ->with('error', 'Gagal menghapus pesanan yang dipilih');
    }

    public function update(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'order_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $order->update([
            'order_date' => $request->order_date,
            'notes' => $request->notes,
            'status' => $request->status
        ]);

        return redirect()->route('admin.orders.show', $order->uuid)
            ->with('success', 'Order berhasil diperbarui');
    }

    public function approve($uuid)
    {
        $order = Order::with('details.item')
            ->where('uuid', $uuid)
            ->firstOrFail();
        
        foreach ($order->details as $detail) {
            $item = $detail->item;
            if ($item->stock < $detail->qty) {
                return back()->with('error', "Stok {$item->name} tidak mencukupi! (Tersedia: {$item->stock})");
            }
        }
        
        foreach ($order->details as $detail) {
            $item = $detail->item;
            $item->stock -= $detail->qty;
            $item->save();
        }
        
        $invoice = 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        
        $order->update([
            'status' => 'approved',
            'invoice_number' => $invoice,
            'approved_at' => now() 
        ]);
        
        return back()->with('success', 'Order berhasil di approve');
    }

    public function generateInvoice($uuid)
    {
        $order = Order::with('details.item', 'user')
            ->where('uuid', $uuid)
            ->firstOrFail();

        if (!$order->invoice_number) {
            $order->update([
                'invoice_number' => 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)
            ]);
            $order->refresh();
        }
        
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        
        return $pdf->download('invoice-' . $order->invoice_number . '.pdf');
    }

    public function viewInvoice($uuid)
    {
        $order = Order::with('details.item', 'user')
            ->where('uuid', $uuid)
            ->firstOrFail();

        if (!$order->invoice_number) {
            $order->update([
                'invoice_number' => 'INV-' . str_pad($order->id, 5, '0', STR_PAD_LEFT)
            ]);
            $order->refresh();
        }

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        
        return $pdf->stream('invoice-' . $order->invoice_number . '.pdf');
    }

    public function reject($uuid)
    {
        $order = Order::with('details.item')
            ->where('uuid', $uuid)
            ->firstOrFail();
        
        if ($order->status == 'approved') {
            foreach ($order->details as $detail) {
                $item = $detail->item;
                $item->stock += $detail->qty;
                $item->save();
            }
        }
        
        $order->update([
            'status' => 'rejected',
            'rejected_at' => now()
        ]);
        
        return back()->with('success', 'Order berhasil ditolak');
    }

    public function complete($uuid)
    {
        $order = Order::where('uuid', $uuid)->firstOrFail();
        
        $order->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        return back()->with('success', 'Order selesai');
    }

    public function exportExcel(Request $request)
    {
        $query = Order::with('user');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('created_at', $request->month)
                  ->whereYear('created_at', $request->year);
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        if ($orders->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diexport!');
        }

        $fileName = 'laporan_pesanan_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new OrdersExport(
            $orders, 
            $request->start_date, 
            $request->end_date,
            $request->month,
            $request->year,
            $request->status
        ), $fileName);
    }
}