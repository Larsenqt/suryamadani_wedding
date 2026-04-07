@extends('layouts.customer')

@section('title', 'Dashboard Customer')

@section('content')
<div class="dashboard-container">
    <!-- Welcome Section - Elegant -->
    <div class="welcome-section">
        <div class="welcome-text">
            <h1 class="welcome-title">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="welcome-subtitle">Temukan perlengkapan terbaik untuk acara spesial Anda</p>
        </div>
        <div class="welcome-decoration">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        </div>
    </div>

    <!-- Stats Cards - Premium Design -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-orders">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 6h18M8 6V4h8v2M3 10h18M5 14h14M7 18h10"/>
                </svg>
            </div>
            <div class="stat-info">
                <p class="stat-label">Total Pesanan</p>
                <p class="stat-value">{{ $totalOrders ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-pending">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="stat-info">
                <p class="stat-label">Menunggu Konfirmasi</p>
                <p class="stat-value">{{ $pendingOrders ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-completed">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
            <div class="stat-info">
                <p class="stat-label">Pesanan Selesai</p>
                <p class="stat-value">{{ $completedOrders ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section - Elegant Table -->
    <div class="recent-orders-card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Pesanan Terbaru</h3>
                <p class="card-subtitle">Riwayat pesanan terakhir Anda</p>
            </div>
            <a href="{{ route('customer.orders.history') }}" class="view-all-link">
                Lihat Semua
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="table-responsive">
            @if(isset($recentOrders) && $recentOrders->count() > 0)
            <table class="elegant-table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td class="order-id-cell">#{{ substr($order->uuid, 0, 8) }}</td>
                        <td class="order-date-cell">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="order-total-cell">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="order-status-cell">
                            @php
                                $statusClass = '';
                                $statusText = '';
                                switch($order->status) {
                                    case 'pending':
                                        $statusClass = 'status-pending';
                                        $statusText = 'Pending';
                                        break;
                                    case 'approved':
                                        $statusClass = 'status-approved';
                                        $statusText = 'Disetujui';
                                        break;
                                    case 'completed':
                                        $statusClass = 'status-completed';
                                        $statusText = 'Selesai';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'status-rejected';
                                        $statusText = 'Ditolak';
                                        break;
                                    default:
                                        $statusClass = 'status-pending';
                                        $statusText = ucfirst($order->status);
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td class="order-action-cell">
                            <a href="{{ route('customer.order.show', $order->uuid) }}" class="detail-link">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
                <h4>Belum Ada Pesanan</h4>
                <p>Mulai belanja sekarang untuk acara spesial Anda</p>
                <a href="{{ route('customer.catalog') }}" class="shop-now-btn">Mulai Belanja</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Help Section - Elegant -->
    <div class="help-card">
        <div class="help-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4M12 8h.01"/>
            </svg>
        </div>
        <div class="help-content">
            <h4>Butuh Bantuan?</h4>
            <p>Tim customer service kami siap membantu Anda 24/7</p>
        </div>
        <a href="#" class="help-btn">
            Hubungi CS
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 2L15 9M22 2v7m0-7h-7"/>
                <path d="M16 13v5a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h5"/>
            </svg>
        </a>
    </div>
</div>

<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    /* Welcome Section */
    .welcome-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 1.5rem;
        padding: 2rem 2rem;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #60a5fa, #2563eb);
    }

    .welcome-text {
        flex: 1;
    }

    .welcome-title {
        font-size: 1.75rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
        letter-spacing: -0.3px;
    }

    .welcome-subtitle {
        color: #64748b;
        font-size: 0.875rem;
    }

    .welcome-decoration {
        color: #e2e8f0;
        opacity: 0.5;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2563eb, #60a5fa);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        border-color: #e2e8f0;
    }

    .stat-card:hover::after {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .stat-icon-orders {
        background: #eff6ff;
        color: #2563eb;
    }

    .stat-icon-pending {
        background: #fffbeb;
        color: #d97706;
    }

    .stat-icon-completed {
        background: #ecfdf5;
        color: #10b981;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.05);
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }

    /* Recent Orders Card */
    .recent-orders-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .recent-orders-card:hover {
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        background: #fafcff;
    }

    .card-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
    }

    .card-subtitle {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 0.25rem;
    }

    .view-all-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #2563eb;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }

    .view-all-link:hover {
        background: #eff6ff;
        gap: 0.75rem;
    }

    /* Elegant Table */
    .table-responsive {
        overflow-x: auto;
    }

    .elegant-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }

    .elegant-table th {
        padding: 1rem 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
    }

    .elegant-table td {
        padding: 1rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    .elegant-table tr {
        transition: background 0.2s;
    }

    .elegant-table tr:hover {
        background: #f8fafc;
    }

    .elegant-table tr:last-child td {
        border-bottom: none;
    }

    .order-id-cell {
        font-family: monospace;
        font-weight: 600;
        color: #0f172a;
    }

    .order-date-cell {
        font-size: 0.875rem;
        color: #64748b;
    }

    .order-total-cell {
        font-weight: 600;
        color: #0f172a;
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 0.375rem;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background: #dcfce7;
        color: #166534;
    }

    .status-completed {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    /* Detail Link */
    .detail-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .detail-link:hover {
        background: #eff6ff;
        border-color: #2563eb;
        color: #2563eb;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-icon {
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
    }

    .shop-now-btn {
        display: inline-block;
        padding: 0.625rem 1.25rem;
        background: #0f172a;
        color: white;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .shop-now-btn:hover {
        background: #1e293b;
        transform: translateY(-2px);
    }

    /* Help Card */
    .help-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        transition: all 0.3s ease;
    }

    .help-card:hover {
        border-color: #bfdbfe;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .help-icon {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        border: 1px solid #e2e8f0;
    }

    .help-content {
        flex: 1;
    }

    .help-content h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }

    .help-content p {
        font-size: 0.75rem;
        color: #64748b;
    }

    .help-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        background: #0f172a;
        border: none;
        border-radius: 0.5rem;
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s;
    }

    .help-btn:hover {
        background: #2563eb;
        gap: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .welcome-title {
            font-size: 1.25rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .help-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endsection