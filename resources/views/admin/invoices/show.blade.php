{{-- resources/views/admin/invoices/show.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Invoice Details - {{ $invoice->invoice_number }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', 'Inter', sans-serif;
            background: #f5f7fa;
            color: #1f2937;
        }

        /* Navbar Admin */
        .admin-navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 2rem;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .logo img {
            height: 40px;
            width: auto;
        }

        .nav-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #475569;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #2563eb;
        }

        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            color: #475569;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-group {
            display: flex;
            gap: 0.75rem;
        }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #0f172a;
            color: white;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-download:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #d97706;
            color: white;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-edit:hover {
            background: #b45309;
            transform: translateY(-1px);
        }

        /* Invoice Card */
        .invoice-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .invoice-content {
            padding: 2rem;
        }

        /* Action Footer */
        .action-footer {
            margin-top: 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-approve {
            padding: 0.5rem 1rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-approve:hover {
            background: #1d4ed8;
        }

        .btn-paid {
            padding: 0.5rem 1rem;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-paid:hover {
            background: #059669;
        }

        .btn-settle {
            padding: 0.5rem 1rem;
            background: #8b5cf6;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-settle:hover {
            background: #7c3aed;
            transform: translateY(-1px);
        }

        .btn-cancel {
            padding: 0.5rem 1rem;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-cancel:hover {
            background: #dc2626;
        }

        /* Invoice Template Styles */
        .invoice-box {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
        }

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
            object-fit: cover;
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
        .text-right { text-align: right; }

        .totals-table {
            width: 280px;
            margin-left: auto;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .totals-table td {
            padding: 6px 8px;
            font-size: 12px;
        }

        .totals-table tr:last-child td {
            border-top: 2px solid #e2e8f0;
            font-weight: bold;
            font-size: 14px;
        }

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

        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }
            .info-table {
                border-spacing: 0;
            }
            .info-table td {
                display: block;
                width: 100%;
                margin-bottom: 10px;
            }
            .totals-table {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="main-container">
        <!-- Header Actions -->
        <div class="header-actions">
            <a href="{{ route('admin.invoices.index') }}" class="btn-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <div class="btn-group">
                <a href="{{ route('admin.invoices.preview-pdf', $invoice) }}" target="_blank" class="btn-download">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Download PDF
                </a>
                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn-edit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 3l4 4L7 21H3v-4L17 3z"/>
                        <path d="M15 5l4 4"/>
                    </svg>
                    Edit Invoice
                </a>
            </div>
        </div>

        <!-- Invoice Display -->
        <div class="invoice-card">
            <div class="invoice-content">
                @include('admin.invoices.partials.invoice-template')
            </div>
        </div>

        <!-- Action Footer -->
        <div class="action-footer">
            @if($invoice->status == 'draft')
                <form action="{{ route('admin.invoices.approve', $invoice) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-approve">✓ Approve Invoice</button>
                </form>
            @endif
            
            @if($invoice->status == 'approved')
                <form action="{{ route('admin.invoices.mark-paid', $invoice) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-paid">✓ Mark as Paid</button>
                </form>
            @endif
            
            {{-- Tombol Pelunasan untuk sisa pembayaran --}}
            @if($invoice->status == 'approved' && $invoice->remaining_amount > 0)
                <form action="{{ route('admin.invoices.settle', $invoice) }}" method="POST" 
                      onsubmit="return confirm('Lunaskan sisa pembayaran Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}?')">
                    @csrf
                    <button type="submit" class="btn-settle">💰 Pelunasan (Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }})</button>
                </form>
            @endif
            
            @if(in_array($invoice->status, ['draft', 'approved']))
                <form action="{{ route('admin.invoices.cancel', $invoice) }}" method="POST" onsubmit="return confirm('Cancel this invoice?')">
                    @csrf
                    <button type="submit" class="btn-cancel">✗ Cancel Invoice</button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>