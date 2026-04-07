{{-- resources/views/admin/invoices/pdf.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
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

        /* HEADER */
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

        /* INFO SECTION */
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

        .notes-content {
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
        @include('admin.invoices.partials.invoice-template')
    </div>
</body>
</html>