@extends('layouts.admin-sidebar')

@section('title', 'Kelola Pesanan')

@section('content') 
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
        }

        .admin-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            color: #475569;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateX(-2px);
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #eff6ff;
            color: #2563eb;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #bfdbfe;
            margin-left: 1rem;
        }

        .btn-edit:hover {
            background: #dbeafe;
            transform: translateY(-1px);
        }

        .card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e9eef3;
            margin-bottom: 1.5rem;
        }

        .card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #f0f2f5;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.75rem;
            border-left: 4px solid #3b82f6;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
        }

        .notes-section {
            background: #fef9e3;
            border-left: 4px solid #f59e0b;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .notes-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #b45309;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .notes-content {
            font-size: 0.875rem;
            color: #78350f;
            line-height: 1.5;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 0.875rem 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .summary {
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }

        .summary-total {
            font-size: 1.125rem;
            font-weight: 700;
            color: #2563eb;
            border-top: 2px solid #e2e8f0;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }

        .badge {
            display: inline-flex;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #dcfce7;
            color: #166534;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-completed {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            flex-wrap: wrap;
        }

        .btn-approve {
            flex: 1;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-reject {
            flex: 1;
            background: #fef2f2;
            color: #dc2626;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-reject:hover {
            background: #fee2e2;
            transform: translateY(-2px);
        }

        .btn-complete {
            flex: 1;
            background: #dcfce7;
            color: #166534;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-complete:hover {
            background: #bbf7d0;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: white;
            color: #475569;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            text-align: center;
            border: 2px solid #e2e8f0;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            border-color: #2563eb;
            color: #2563eb;
            background: #f8fafc;
        }

        .btn-print, .btn-invoice {
            background: white;
            color: #475569;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            border: 2px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-print:hover, .btn-invoice:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        /* Product Item */
        .product-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .product-image {
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            object-fit: cover;
            background: #f1f5f9;
        }

        @media (max-width: 640px) {
            .admin-container {
                padding: 1rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
            <a href="{{ route('admin.orders.index') }}" class="btn-back">
                ← Kembali ke Daftar Pesanan
            </a>
            <a href="{{ route('admin.orders.edit', $order->uuid) }}" class="btn-edit">
                ✏️ Edit Pesanan
            </a>
        </div>

        <!-- Order Info Card -->
        <div class="card">
            <div class="card-header">
                <h2>
                    <span>📄</span> Detail Pesanan
                </h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">ID Pesanan</div>
                        <div class="info-value" style="font-family: monospace;">#{{ $order->uuid }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @php
                                $statusClass = '';
                                $statusText = '';
                                switch($order->status) {
                                    case 'pending':
                                        $statusClass = 'badge-pending';
                                        $statusText = 'Pending';
                                        break;
                                    case 'approved':
                                        $statusClass = 'badge-approved';
                                        $statusText = 'Disetujui';
                                        break;
                                    case 'completed':
                                        $statusClass = 'badge-completed';
                                        $statusText = 'Selesai';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'badge-rejected';
                                        $statusText = 'Ditolak';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Pelanggan</div>
                        <div class="info-value">{{ $order->user->name }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $order->user->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Pemesanan</div>
                        <div class="info-value">{{ $order->order_date ? date('d F Y', strtotime($order->order_date)) : '-' }}</div>
                    </div>
                    @if($order->invoice_number)
                    <div class="info-item">
                        <div class="info-label">Nomor Invoice</div>
                        <div class="info-value">{{ $order->invoice_number }}</div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">Waktu Order</div>
                        <div class="info-value">{{ $order->created_at->format('d F Y H:i') }}</div>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Alamat Pengiriman</div>
                            <div class="info-value">{{ $order->address ?? '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Nomor Telepon</div>
                            <div class="info-value">{{ $order->phone ?? '-' }}</div>
                        </div>
                        @if($order->notes)
                        <div class="info-item">
                            <div class="info-label">Catatan Pesanan</div>
                            <div class="info-value">{{ $order->notes }}</div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($order->notes)
                <div class="notes-section">
                    <div class="notes-label">📝 Catatan Pesanan</div>
                    <div class="notes-content">{{ $order->notes }}</div>
                </div>
                @endif

                <!-- Items Table -->
                <h3 style="margin-bottom: 1rem; font-size: 1rem; color: #1e293b;">📦 Item yang Dipesan</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->details as $d)
                            <tr>
                                <td>
                                    <div class="product-item">
                                        @if($d->item->image && file_exists(storage_path('app/public/'.$d->item->image)))
                                            <img src="{{ asset('storage/'.$d->item->image) }}" class="product-image" alt="{{ $d->item->name }}">
                                        @endif
                                        <strong>{{ $d->item->name }}</strong>
                                    </div>
                                </td>
                                <td>{{ $d->qty }}</td>
                                <td>Rp {{ number_format($d->price, 0, ',', '.') }}</td>
                                <td class="price">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row summary-total">
                        <strong>TOTAL</strong>
                        <strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if($order->status == 'pending')
                    <form method="POST" action="{{ route('admin.orders.approve', $order->uuid) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn-approve" onclick="return confirm('Setujui pesanan ini?')">
                            ✓ Setujui Pesanan
                        </button>
                    </form>
                    @endif
                    
                    @if($order->status == 'pending')
                    <form method="POST" action="{{ route('admin.orders.reject', $order->uuid) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn-reject" onclick="return confirm('Tolak pesanan ini?')">
                            ✗ Tolak Pesanan
                        </button>
                    </form>
                    @endif
                    
                    @if($order->status == 'approved')
                    <form method="POST" action="{{ route('admin.orders.complete', $order->uuid) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn-complete" onclick="return confirm('Tandai pesanan sebagai selesai?')">
                            ✓ Selesai
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.orders.index') }}" class="btn-secondary">
                        Kembali
                    </a>
                    
                    <!-- Di bagian action buttons, ubah kondisi menjadi: -->
                    @if($order->status == 'approved' || $order->status == 'completed')
                    <a href="{{ route('admin.orders.invoice.view', $order->uuid) }}" class="btn-invoice" target="_blank">
                        👁️ Lihat Invoice
                    </a>
                    <a href="{{ route('admin.orders.invoice', $order->uuid) }}" class="btn-print">
                        📥 Download Invoice
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .btn-back, .btn-edit, .action-buttons, .btn-print, .btn-invoice {
                display: none;
            }
            body {
                background: white;
                padding: 0;
            }
            .card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</body>
@endsection