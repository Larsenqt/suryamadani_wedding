@extends('layouts.customer')  

@section('title', 'Order History')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>📋 Riwayat Pesanan</h1>
        </div>

        @forelse($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div>
                    <span class="order-id">#{{ $order->uuid }}</span>
                    <span style="margin-left: 1rem; color: #64748b; font-size: 0.875rem;">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
                <div>
                    @php
                        $statusText = '';
                        $statusClass = '';
                        switch($order->status) {
                            case 'pending': $statusText = 'Pending'; $statusClass = 'badge-pending'; break;
                            case 'approved': $statusText = 'Disetujui'; $statusClass = 'badge-approved'; break;
                            case 'completed': $statusText = 'Selesai'; $statusClass = 'badge-completed'; break;
                            case 'cancelled': $statusText = 'Dibatalkan'; $statusClass = 'badge-cancelled'; break;
                        }
                    @endphp
                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                </div>
            </div>
            <div class="order-body">
                <div class="order-items">
                    @foreach($order->details->take(3) as $detail)
                    <div class="order-item">
                        <span>{{ $detail->item->name }} x{{ $detail->qty }}</span>
                        <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                    @if($order->details->count() > 3)
                    <div class="order-item" style="color: #64748b;">
                        <span>+{{ $order->details->count() - 3 }} item lainnya</span>
                        <span></span>
                    </div>
                    @endif
                </div>
                <div class="order-footer">
                    <div>
                        <div style="font-size: 0.875rem; color: #64748b;">Tanggal Sewa</div>
                        <div style="font-weight: 500;">{{ date('d/m/Y', strtotime($order->order_date)) }}</div>
                    </div>
                    <div class="order-total">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </div>
                    <a href="{{ route('customer.order.show', $order->uuid) }}" class="btn-detail">Detail →</a>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 4rem; background: white; border-radius: 1rem;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
            <p>Belum ada pesanan</p>
            <a href="{{ route('customer.catalog') }}" style="color: #2563eb; margin-top: 1rem; display: inline-block;">Mulai Belanja</a>
        </div>
        @endforelse

        <div class="pagination">
            {{ $orders->links() }}
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


        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
        }


        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.875rem;
            font-weight: 600;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .order-card {
            background: white;
            border-radius: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e9eef3;
            overflow: hidden;
        }

        .order-header {
            background: #f8fafc;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .order-id {
            font-family: monospace;
            font-weight: 600;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #dcfce7; color: #166534; }
        .badge-completed { background: #dbeafe; color: #1e40af; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }

        .order-body {
            padding: 1rem 1.5rem;
        }

        .order-items {
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .order-total {
            font-size: 1.125rem;
            font-weight: 700;
            color: #2563eb;
        }

        .btn-detail {
            background: #eff6ff;
            color: #2563eb;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            .order-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection