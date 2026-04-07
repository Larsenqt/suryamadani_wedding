<?php
// app/Http/Controllers/Admin/InvoiceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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

        return view('admin.invoices.index', compact('invoices', 'stats'));
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
            
            // Tentukan status DP
            $dpStatus = 'unpaid';
            if ($dpAmount >= $total) {
                $dpStatus = 'paid';
            } elseif ($dpAmount > 0) {
                $dpStatus = 'partial';
            }

            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'order_date' => $request->order_date,
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
            
            // Tentukan status DP
            $dpStatus = 'unpaid';
            if ($dpAmount >= $total) {
                $dpStatus = 'paid';
            } elseif ($dpAmount > 0) {
                $dpStatus = 'partial';
            }

            $invoice->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'order_date' => $request->order_date,
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
            ]);


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

            return redirect()->route('admin.invoices.show', $invoice)
                ->with('success', 'Invoice berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate invoice: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice berhasil dihapus');
    }

    public function approve(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Invoice berhasil disetujui');
    }

    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Invoice ditandai sebagai Lunas');
    }

    public function cancel(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Invoice dibatalkan');
    }

    public function generatePdf(Invoice $invoice)
    {
        $invoice->load('items');
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        
        $fileName = 'invoice-' . $invoice->id . '-' . date('Ymd_His') . '.pdf';
        
        return $pdf->download($fileName);
    }

    public function previewPdf(Invoice $invoice)
    {
        $invoice->load('items');
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        
        $fileName = 'invoice-' . $invoice->id . '-' . date('Ymd_His') . '.pdf';
        
        return $pdf->stream($fileName);
    }
}