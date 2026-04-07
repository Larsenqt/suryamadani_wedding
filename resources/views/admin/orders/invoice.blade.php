<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $order->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: #fff;
            padding: 20px;
        }

        .invoice-box {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
        }

        /* HEADER - pakai table agar DomPDF bisa render 2 kolom */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        .header-table td {
            padding: 0;
            vertical-align: middle;
        }

        .logo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .logo-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #2563eb;
            text-align: center;
            line-height: 100px;
            font-size: 32px;
            font-weight: bold;
            color: white;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 32px;
            color: #1e293b;
            letter-spacing: 2px;
        }

        .invoice-title small {
            color: #64748b;
            font-size: 13px;
        }

        /* COMPANY INFO */
        .company-info {
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .company-detail {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
        }

        /* INFO SECTION - pakai table 2 kolom */
        .info-table {
            width: 100%;
            margin: 16px 0;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .info-table td {
            width: 50%;
            background: #f8fafc;
            padding: 12px 14px;
            border-radius: 8px;
            vertical-align: top;
            font-size: 13px;
        }

        .info-card-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .info-card-content {
            font-size: 13px;
            line-height: 1.6;
        }

        .info-card-content strong {
            font-size: 14px;
            color: #0f172a;
            display: block;
            margin-bottom: 4px;
        }

        /* Info rows pakai table dalam card */
        .inner-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        .inner-table td {
            background: none;
            padding: 2px 0;
            font-size: 12px;
            border-radius: 0;
        }

        .label-col {
            color: #64748b;
            width: 90px;
            vertical-align: top;
        }

        .value-col {
            color: #0f172a;
            font-weight: 500;
        }

        .amount-box {
            background: #eef2ff;
            padding: 8px 12px;
            text-align: right;
            border-radius: 6px;
            margin-top: 10px;
        }

        .amount-label {
            font-size: 11px;
            color: #4338ca;
        }

        .amount-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }

        /* ITEMS TABLE */
        .items-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            font-size: 12px;
        }

        .items-table th {
            background: #f1f5f9;
            padding: 8px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }

        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            background: none;
            border-radius: 0;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .item-name {
            font-weight: 600;
            color: #0f172a;
        }

        .item-meta {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        .text-center { text-align: center; }
        .text-right  { text-align: right; }

        /* TOTALS */
        .totals-table {
            width: 280px;
            margin-left: auto;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .totals-table td {
            padding: 6px 8px;
            font-size: 12px;
            background: none;
            border-radius: 0;
        }

        .totals-table tr:last-child td {
            border-top: 2px solid #e2e8f0;
            font-weight: bold;
            font-size: 14px;
        }

        /* NOTES */
        .notes-section {
            margin-top: 25px;
            padding: 12px;
            background: #fef9e3;
            border-radius: 8px;
            font-size: 11px;
            color: #92400e;
        }

        .notes-title {
            font-weight: 600;
            margin-bottom: 6px;
        }

        .notes-list {
            margin-left: 20px;
            line-height: 1.5;
        }

        /* FOOTER */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            text-align: right;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature-line {
            width: 200px;
            margin-left: auto;
            border-top: 1px solid #cbd5e1;
            margin-bottom: 5px;
        }

        @media print {
            body { padding: 0; background: white; }
            .invoice-box { max-width: 100%; }
        }
    </style>
</head>

<body>
<div class="invoice-box">

    <!-- HEADER: Logo kiri | Invoice kanan (table-based, DomPDF compatible) -->
    <table class="header-table">
        <tr>
            <td class="logo">
                @php
                    $logoPath = public_path('images/logo.png');
                    if (!file_exists($logoPath)) $logoPath = public_path('logo.png');
                    if (!file_exists($logoPath)) $logoPath = public_path('assets/logo.png');
                    $logoExists = file_exists($logoPath);
                @endphp
                @if($logoExists)
                    <img src="{{ $logoPath }}" alt="Logo">
                @else
                    <div class="logo-placeholder">SM</div>
                @endif
            </td>
            <td class="invoice-title">
                <h1>INVOICE</h1>
                <small># {{ $order->invoice_number }}</small>
            </td>
        </tr>
    </table>

    <!-- COMPANY INFO -->
    <div class="company-info">
        <div class="company-name">SURYA MADANI WEDDING</div>
        <div class="company-detail">Wedding &amp; Party Rentals<br>Desa Sugihwaras No. 19 RT.26 / 02 Maospati Kab. Magetan </div>
    </div>

    <!-- INFO SECTION: Customer kiri | Detail Invoice kanan (table-based) -->
    <table class="info-table">
        <tr>
            <!-- KIRI: Info Customer -->
            <td>
                <div class="info-card-title">KEPADA CUSTOMER</div>
                <div class="info-card-content">
                    <strong>{{ $order->user->name }}</strong>
                    <table class="inner-table">
                        <tr>
                            <td class="label-col">Alamat Pengiriman</td>
                            <td class="value-col">{{ $order->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Telepon</td>
                            <td class="value-col">{{ $order->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Email</td>
                            <td class="value-col">{{ $order->user->email }}</td>
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
                            <td class="value-col">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Tgl. Sewa</td>
                            <td class="value-col">{{ date('d M Y', strtotime($order->order_date)) }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Metode</td>
                            <td class="value-col">Transfer / Cash</td>
                        </tr>
                    </table>
                    <div class="amount-box">
                        <div class="amount-label">TOTAL TAGIHAN</div>
                        <div class="amount-value">Rp {{ number_format($order->total_price,0,',','.') }}</div>
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
        @foreach($order->details as $detail)
        <tr>
            <td>
                <div class="item-name">{{ $detail->item->name }}</div>
                <div class="item-meta">{{ $detail->item->type->name ?? 'Item' }}</div>
            </td>
            <td class="text-center">{{ $detail->qty }}</td>
            <td class="text-right">Rp {{ number_format($detail->price,0,',','.') }}</td>
            <td class="text-right">Rp {{ number_format($detail->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <table class="totals-table">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp {{ number_format($order->total_price,0,',','.') }}</td>
        </tr>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td class="text-right"><strong>Rp {{ number_format($order->total_price,0,',','.') }}</strong></td>
        </tr>
    </table>

    <!-- NOTES -->
    <div class="notes-section">
        <div class="notes-title">CATATAN PENTING</div>
        <ul class="notes-list">
            <li>Pelunasan dilakukan H-1 sebelum acara</li>
            <li>Pembayaran via transfer ke rekening BNI 1234567890 a.n Surya Madani</li>
            <li>Barang akan diantar H-1 acara atau sesuai kesepakatan</li>
            <li>Kerusakan barang menjadi tanggung jawab penyewa</li>
        </ul>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <div>Terima kasih atas kepercayaan Anda</div>
        <div class="signature">
            <div class="signature-line"></div>
            <div>Madiun, {{ $order->created_at->format('d F Y') }}</div>
            <div><strong>SuryaMadani Wedding</strong></div>
        </div>
    </div>

</div>
</body>
</html>