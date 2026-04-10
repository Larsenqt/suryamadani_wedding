{{-- resources/views/admin/invoices/partials/invoice-template.blade.php --}}

@php
    // Cari file logo dan konversi ke base64
    $logoPaths = [
        public_path('images/logo.png'),
        public_path('logo.png'),
        public_path('assets/logo.png'),
        public_path('images/navbar.png'),
    ];
    
    $logoBase64 = null;
    $logoFile = null;
    
    foreach ($logoPaths as $path) {
        if (file_exists($path)) {
            $logoFile = $path;
            $imageData = base64_encode(file_get_contents($path));
            $imageType = pathinfo($path, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $imageType . ';base64,' . $imageData;
            break;
        }
    }
    
    $logoExists = !is_null($logoBase64);
@endphp

<div class="invoice-box">
    <!-- HEADER: Logo kiri | Invoice kanan -->
    <table class="header-table">
        <tr>
            <td class="logo">
                @if($logoExists)
                    {{-- Gunakan base64 untuk semua (browser & PDF) --}}
                    <img src="{{ $logoBase64 }}" alt="Logo">
                @else
                    <div class="logo-placeholder">SM</div>
                @endif
            </td>
            <td class="invoice-title">
                <h1>INVOICE</h1>
                <small># {{ $invoice->invoice_number }}</small>
            </td>
        </tr>
    </table>

    <!-- COMPANY INFO -->
    <div class="company-info">
        <div class="company-name">SURYA MADANI WEDDING</div>
        <div class="company-detail">
            Wedding & Party Rentals<br>
            Persewaan Alat Alat Pesta: Terop - Sound System - Kursi & Meja - Gerabah - Dekorasi - Karpet<br>
            Desa Sugihwaras No. 19 RT.26 / 02 Maospati Kab. Magetan
        </div>
    </div>

    <!-- INFO SECTION: Customer kiri | Detail Invoice kanan -->
    <table class="info-table">
        <tr>
            <!-- KIRI: Info Customer -->
            <td>
                <div class="info-card-title">KEPADA CUSTOMER</div>
                <div class="info-card-content">
                    <strong>{{ $invoice->customer_name }}</strong>
                    <table class="inner-table">
                        <tr>
                            <td class="label-col">Alamat Pengiriman</td>
                            <td class="value-col">{{ $invoice->customer_address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Telepon</td>
                            <td class="value-col">{{ $invoice->customer_phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Email</td>
                            <td class="value-col">{{ $invoice->customer_email ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </td>

            <!-- KANAN: Detail Invoice -->
            <td>
                <div class="info-card-title">DETAIL INVOICE</div>
                <div class="info-card-content">
                    <table class="inner-table">
                        <tr>
                            <td class="label-col">Tgl. Invoice</td>
                            <td class="value-col">{{ $invoice->order_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Tgl. Jatuh Tempo</td>
                            <td class="value-col">{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Tgl. Acara</td>
                            <td class="value-col">{{ $invoice->event_date ? $invoice->event_date->format('d M Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Metode</td>
                            <td class="value-col">Transfer / Cash</td>
                        </tr>
                    </table>
                    <div class="amount-box">
                        <div class="amount-label">TOTAL TAGIHAN</div>
                        <div class="amount-value">Rp {{ number_format($invoice->total_amount,0,',','.') }}</div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- ITEMS TABLE -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%;">Item</th>
                <th style="width: 15%; text-align: center;">Qty</th>
                <th style="width: 20%; text-align: right;">Harga</th>
                <th style="width: 20%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($invoice->items as $item)
        <tr>
            <td>
                <div class="item-name">{{ $item->item_name }}</div>
                <div class="item-meta">{{ $item->description ?? 'Item' }}</div>
            </td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-right">Rp {{ number_format($item->unit_price,0,',','.') }}</td>
            <td class="text-right">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <table class="totals-table">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp {{ number_format($invoice->subtotal,0,',','.') }}</td>
        </tr>
        @if($invoice->tax > 0)
        <tr>
            <td>Tax ({{ $invoice->tax }}%)</td>
            <td class="text-right">Rp {{ number_format($invoice->subtotal * $invoice->tax / 100,0,',','.') }}</td>
        </tr>
        @endif
        @if($invoice->discount > 0)
        <tr>
            <td>Discount</td>
            <td class="text-right">- Rp {{ number_format($invoice->discount,0,',','.') }}</td>
        </tr>
        @endif
        <tr class="border-t-2 border-gray-200">
            <td class="font-bold">TOTAL</td>
            <td class="text-right font-bold">Rp {{ number_format($invoice->total_amount,0,',','.') }}</td>
        </tr>
        @if($invoice->dp_amount > 0)
        <tr class="border-t-2 border-gray-200">
            <td class="font-semibold">DP (Down Payment)</td>
            <td class="text-right font-semibold text-blue-600">Rp {{ number_format($invoice->dp_amount,0,',','.') }}</td>
        </tr>
        <tr>
            <td class="font-semibold">Sisa Pembayaran</td>
            <td class="text-right font-semibold text-red-600">Rp {{ number_format($invoice->remaining_amount,0,',','.') }}</td>
        </tr>
        @if($invoice->dp_due_date)
        <tr>
            <td class="text-sm text-gray-500">Tgl Jatuh Tempo DP</td>
            <td class="text-right text-sm text-gray-500">{{ $invoice->dp_due_date->format('d M Y') }}</td>
        </tr>
        @endif
        @endif
    </table>
    
    <!-- NOTES -->
    <div class="notes-section">
        <div class="notes-title">CATATAN PENTING</div>
        <ul class="notes-list">
            <li>Pembayaran via cash/transfer ke rekening Bank Mandiri 1710018817083 a.n DWI ANGGRIAWAN</li>
            <li>Barang akan diantar H-1 acara atau sesuai kesepakatan</li>
            <li>Kerusakan barang menjadi tanggung jawab penyewa</li>
            <li>Nomer Wa: 082244735038 atau 08884088042 </li>
        </ul>
    </div>
    
    <!-- FOOTER -->
    <div class="footer">
        <div>Terima kasih atas kepercayaan Anda</div>
        <div class="signature">
            <div class="signature-line"></div>
            <div>{{ $invoice->order_date->format('d F Y') }}</div>
            <div><strong>SuryaMadani Wedding</strong></div>
        </div>
    </div>
</div>