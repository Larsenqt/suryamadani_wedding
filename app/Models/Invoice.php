<?php
// app/Models/Invoice.php - COPY PASTE SELURUHNYA

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'order_date',      
        'event_date',      
        'due_date',
        'subtotal',
        'tax',
        'discount',
        'dp_amount',
        'remaining_amount',
        'dp_status',
        'dp_due_date',
        'total_amount',
        'notes',
        'status',
        'created_by',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'event_date' => 'date',      
        'due_date' => 'date',
        'dp_due_date' => 'date',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Auto calculate remaining amount before save
    protected static function booted()
    {
        static::saving(function ($invoice) {
            // Auto calculate remaining amount
            $invoice->remaining_amount = $invoice->total_amount - $invoice->dp_amount;
            
            // Auto update dp_status
            if ($invoice->dp_amount >= $invoice->total_amount) {
                $invoice->dp_status = 'paid';
            } elseif ($invoice->dp_amount > 0) {
                $invoice->dp_status = 'partial';
            } else {
                $invoice->dp_status = 'unpaid';
            }
            
            // Auto update status if dp is fully paid
            if ($invoice->dp_amount >= $invoice->total_amount && $invoice->status != 'paid') {
                $invoice->status = 'paid';
                $invoice->paid_at = now();
            }
        });
    }

    /**
     * GENERATE INVOICE NUMBER - PALING AMAN (TANPA MIGRASI)
     * Menggunakan timestamp + random + unique check
     */
    public static function generateInvoiceNumber()
    {
        do {
            // Format: INV-20260409-143025-1234 (TahunBulanTanggal-JamMenitDetik-Random)
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . date('His') . '-' . rand(1000, 9999);
        } while (self::where('invoice_number', $invoiceNumber)->exists());
        
        return $invoiceNumber;
    }

    // Hitung sisa pembayaran setelah DP
    public function calculateRemainingAmount()
    {
        return $this->total_amount - $this->dp_amount;
    }

    // Update DP status berdasarkan DP amount
    public function updateDpStatus()
    {
        if ($this->dp_amount >= $this->total_amount) {
            $this->dp_status = 'paid';
        } elseif ($this->dp_amount > 0) {
            $this->dp_status = 'partial';
        } else {
            $this->dp_status = 'unpaid';
        }
        $this->remaining_amount = $this->calculateRemainingAmount();
        $this->save();
    }

    // Status badge helper
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'draft':
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Draft</span>';
            case 'approved':
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Approved</span>';
            case 'paid':
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Paid</span>';
            case 'cancelled':
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Cancelled</span>';
            default:
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">' . $this->status . '</span>';
        }
    }

    public function getDpStatusBadgeAttribute()
    {
        switch ($this->dp_status) {
            case 'paid':
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">DP Lunas</span>';
            case 'partial':
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">DP Sebagian</span>';
            default:
                return '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Belum DP</span>';
        }
    }
}