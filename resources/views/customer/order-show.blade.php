@extends('layouts.customer')  

@section('title', 'Order Show')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Detail Pesanan</h2>
            </div>
            <div class="card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">ID Pesanan</div>
                        <div class="info-value">{{ $order->uuid }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            @php
                                $statusText = '';
                                $statusClass = '';
                                switch($order->status) {
                                    case 'pending': $statusText = 'Menunggu Konfirmasi'; $statusClass = 'badge-pending'; break;
                                    case 'approved': $statusText = 'Disetujui'; $statusClass = 'badge-approved'; break;
                                    case 'completed': $statusText = 'Selesai'; $statusClass = 'badge-completed'; break;
                                    case 'cancelled': $statusText = 'Dibatalkan'; $statusClass = 'badge-cancelled'; break;
                                }
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Pemesanan</div>
                        <div class="info-value">{{ date('d/m/Y', strtotime($order->order_date)) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Waktu Order</div>
                        <div class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>

                <h3 style="margin: 1rem 0; font-size: 1rem;">Item yang Dipesan</h3>
                <table>
                    <thead>
                        <tr><th>Item</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $detail)
                        <tr>
                            <td>{{ $detail->item->name }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                            <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                @if($order->status == 'pending')
                <div style="margin-top: 1.5rem; text-align: right;">
                    <form action="{{ route('customer.order.cancel', $order->uuid) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                        @csrf
                        <button type="submit" class="btn-cancel">Batalkan Pesanan</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
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

        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
        }

        .btn-back {
            background: #f8fafc;
            color: #475569;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e9eef3;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
        }

        .card-body {
            padding: 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .info-label {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 600;
            color: #1e293b;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-completed { background: #dbeafe; color: #1e40af; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        th {
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
        }

        .total-row {
            background: #f8fafc;
            font-weight: 700;
        }

        .btn-cancel {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 500;
            margin-right: 1rem;
        }

        .btn-cancel:hover {
            background: #fecaca;
        }

        @media (max-width: 640px) {
            .container {
                padding: 1rem;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection