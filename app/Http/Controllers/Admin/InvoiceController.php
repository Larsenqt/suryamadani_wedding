<?php
// app/Http/Controllers/Admin/InvoiceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Exports\InvoicesExport;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('items', 'creator');

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%' . $request->search . '%')
                ->orWhere('customer_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'total' => Invoice::count(),
            'draft' => Invoice::where('status', 'draft')->count(),
            'approved' => Invoice::where('status', 'approved')->count(),
            'paid' => Invoice::where('status', 'paid')->count(),
        ];

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $years = Invoice::selectRaw('YEAR(created_at) as year')
                    ->distinct()
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        return view('admin.invoices.index', compact('invoices', 'stats', 'months', 'years'));
    }

    public function create()
    {
        $invoiceNumber = Invoice::generateInvoiceNumber();
        return view('admin.invoices.create', compact('invoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'order_date' => 'required|date',
            'event_date' => 'nullable|date',  
            'due_date' => 'nullable|date|after_or_equal:order_date',
            'dp_due_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $request->tax ?? 0;
            $discount = $request->discount ?? 0;
            $dpAmount = $request->dp_amount ?? 0;
            $total = $subtotal + $tax - $discount;
            $remainingAmount = $total - $dpAmount;
            
            $dpStatus = 'unpaid';
            if ($dpAmount >= $total) {
                $dpStatus = 'paid';
            } elseif ($dpAmount > 0) {
                $dpStatus = 'partial';
            }

            $invoiceNumber = $request->invoice_number ?? Invoice::generateInvoiceNumber();

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'order_date' => $request->order_date,
                'event_date' => $request->event_date,  
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'dp_amount' => $dpAmount,
                'remaining_amount' => $remainingAmount,
                'dp_status' => $dpStatus,
                'dp_due_date' => $request->dp_due_date,
                'total_amount' => $total,
                'notes' => $request->notes,
                'status' => 'draft',
                'created_by' => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'Invoice berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items', 'creator');
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        return view('admin.invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'order_date' => 'required|date',
            'event_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:order_date',
            'status' => 'required|in:draft,approved,paid,cancelled',
            'dp_due_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            $tax = $request->tax ?? 0;
            $discount = $request->discount ?? 0;
            $dpAmount = $request->dp_amount ?? 0;
            $total = $subtotal + $tax - $discount;
            $remainingAmount = $total - $dpAmount;
            
            $dpStatus = 'unpaid';
            if ($dpAmount >= $total) {
                $dpStatus = 'paid';
            } elseif ($dpAmount > 0) {
                $dpStatus = 'partial';
            }

            $updateData = [
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'order_date' => $request->order_date,
                'event_date' => $request->event_date,
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'dp_amount' => $dpAmount,
                'remaining_amount' => $remainingAmount,
                'dp_status' => $dpStatus,
                'dp_due_date' => $request->dp_due_date,
                'total_amount' => $total,
                'notes' => $request->notes,
                'status' => $request->status,
            ];

            $oldStatus = $invoice->status;
            $newStatus = $request->status;

            if ($newStatus == 'approved') {
                $updateData['approved_at'] = now();
                $updateData['paid_at'] = null;
            } elseif ($newStatus == 'paid') {
                $updateData['paid_at'] = now();
                if (is_null($invoice->approved_at)) {
                    $updateData['approved_at'] = now();
                }
            } elseif ($newStatus == 'draft' || $newStatus == 'cancelled') {
                $updateData['approved_at'] = null;
                $updateData['paid_at'] = null;
            }

            if ($newStatus == 'paid' && $dpAmount < $total) {
                $updateData['status'] = 'approved';
                $updateData['paid_at'] = null;
                if (is_null($invoice->approved_at)) {
                    $updateData['approved_at'] = now();
                }
            }

            $invoice->update($updateData);
            $invoice->items()->delete();

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_name' => $item['item_name'],
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            DB::commit();

            $statusMessage = $oldStatus != $newStatus ? " Status berubah dari {$oldStatus} menjadi {$newStatus}." : '';
            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'Invoice berhasil diupdate.' . $statusMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoiceNumber = $invoice->invoice_number;
            $invoice->delete();
            return redirect()->route('admin.invoices.index')
                ->with('success', "Invoice {$invoiceNumber} berhasil dihapus");
        } catch (\Exception $e) {
            return redirect()->route('admin.invoices.index')
                ->with('error', 'Gagal menghapus invoice: ' . $e->getMessage());
        }
    }

    public function approve(Invoice $invoice)
    {
        if ($invoice->dp_amount >= $invoice->total_amount) {
            $status = 'paid';
            $paidAt = now();
        } else {
            $status = 'approved';
            $paidAt = null;
        }
        
        $invoice->update([
            'status' => $status,
            'approved_at' => now(),
            'paid_at' => $paidAt,
        ]);

        return back()->with('success', 'Invoice berhasil disetujui');
    }

    public function markAsPaid(Invoice $invoice)
    {
        $dpAmount = $invoice->total_amount;
        $remainingAmount = 0;
        $dpStatus = 'paid';
        
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'dp_amount' => $dpAmount,
            'remaining_amount' => $remainingAmount,
            'dp_status' => $dpStatus,
        ]);

        return back()->with('success', 'Invoice ditandai sebagai Lunas');
    }

    public function updateDp(Request $request, Invoice $invoice)
    {
        $request->validate([
            'dp_amount' => 'required|numeric|min:0|max:' . $invoice->total_amount,
        ]);

        $dpAmount = $request->dp_amount;
        $remainingAmount = $invoice->total_amount - $dpAmount;
        
        if ($dpAmount >= $invoice->total_amount) {
            $dpStatus = 'paid';
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        } elseif ($dpAmount > 0) {
            $dpStatus = 'partial';
        } else {
            $dpStatus = 'unpaid';
        }
        
        $invoice->update([
            'dp_amount' => $dpAmount,
            'remaining_amount' => $remainingAmount,
            'dp_status' => $dpStatus,
        ]);

        return back()->with('success', 'DP berhasil diupdate');
    }

    public function cancel(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Invoice dibatalkan');
    }

    public function settle(Invoice $invoice)
    {
        $invoice->update([
            'dp_amount' => $invoice->total_amount,
            'remaining_amount' => 0,
            'dp_status' => 'paid',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Pelunasan berhasil, invoice telah lunas');
    }

    public function generatePdf(Invoice $invoice)
    {
        $invoice->load('items');
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        
        $fileName = 'invoice-' . $invoice->invoice_number . '.pdf';
        
        return $pdf->download($fileName);
    }

    public function previewPdf(Invoice $invoice)
    {
        $invoice->load('items');
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        
        $fileName = 'invoice-' . $invoice->invoice_number . '.pdf';
        
        return $pdf->stream($fileName);
    }

    public function exportExcel(Request $request)
    {
        $query = Invoice::query();

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

        $invoices = $query->orderBy('created_at', 'desc')->get();

        if ($invoices->isEmpty()) {
            return back()->with('error', 'Tidak ada data untuk diexport!');
        }

        $fileName = 'laporan_invoice_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new InvoicesExport($invoices), $fileName);
    }
}