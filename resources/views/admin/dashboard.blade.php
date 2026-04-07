@extends('layouts.admin-sidebar')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="welcome-section">
        <h1>Dashboard</h1>
        <p>Selamat datang, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Total Pesanan</p>
                <p class="stat-value">{{ $totalOrders ?? 0 }}</p>
            </div>
            <div class="stat-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 6h18M8 6V4h8v2M3 10h18M5 14h14M7 18h10"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Pending</p>
                <p class="stat-value">{{ $pendingOrders ?? 0 }}</p>
            </div>
            <div class="stat-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Total Item</p>
                <p class="stat-value">{{ $totalItems ?? 0 }}</p>
            </div>
            <div class="stat-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M20 7h-4.18A3 3 0 0 0 16 5.18V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1.18A3 3 0 0 0 8.18 7H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                    <path d="M12 11v4M10 13h4"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Testimoni</p>
                <p class="stat-value">{{ $totalTestimonials ?? 0 }}</p>
            </div>
            <div class="stat-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="recent-orders">
        <div class="section-header">
            <h3>Pesanan Terbaru</h3>
        </div>
        <div class="table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-id">ID Order</th>
                        <th class="col-customer">Pelanggan</th>
                        <th class="col-total">Total</th>
                        <th class="col-status">Status</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $index => $order)
                    <tr>
                        <td class="text-center">
                            {{ $recentOrders->firstItem() + $index }}
                        </td>
                        <td>
                            <span class="order-id">#{{ substr($order->uuid, 0, 8) }}</span>
                        </td>
                        <td>
                            <div class="customer-name">{{ $order->user->name }}</div>
                            <div class="customer-email">{{ $order->user->email }}</div>
                        </td>
                        <td class="price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
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
                                        $statusText = $order->status;
                                }
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->uuid) }}" class="btn-detail" title="Detail">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <div class="empty-state-content">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                </svg>
                                <p>Belum ada pesanan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-container">
            {{ $recentOrders->links() }}
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Welcome Section */
    .welcome-section {
        margin-bottom: 2rem;
    }

    .welcome-section h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
        border-left: 3px solid #3b82f6;
        padding-left: 1rem;
    }

    .welcome-section p {
        color: #64748b;
        font-size: 0.875rem;
        padding-left: 1rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #bfdbfe;
    }

    .stat-info {
        flex: 1;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #0f172a;
    }

    .stat-icon {
        color: #94a3b8;
    }

    /* Recent Orders Section */
    .recent-orders {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .section-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #fafcff 0%, #ffffff 100%);
    }

    .section-header h3 {
        font-weight: 600;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-header h3::before {
        content: '';
        width: 4px;
        height: 20px;
        background: #3b82f6;
        border-radius: 2px;
    }

    .table-container {
        overflow-x: auto;
    }

    .orders-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .orders-table th {
        background: #f8fafc;
        padding: 1rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }

    /* Warna biru pada header tabel */
    .orders-table th.col-no {
        background: #eff6ff;
        color: #1e40af;
        width: 60px;
        text-align: center;
    }

    .orders-table th.col-id {
        background: #eff6ff;
        color: #1e40af;
    }

    .orders-table th.col-customer {
        background: #eff6ff;
        color: #1e40af;
    }

    .orders-table th.col-total {
        background: #eff6ff;
        color: #1e40af;
    }

    .orders-table th.col-status {
        background: #eff6ff;
        color: #1e40af;
    }

    .orders-table th.col-action {
        background: #eff6ff;
        color: #1e40af;
        text-align: center;
    }

    .orders-table td {
        padding: 1rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
        transition: background 0.2s;
    }

    .orders-table tr:hover td {
        background: #f0f9ff;
    }

    .orders-table tr:last-child td {
        border-bottom: none;
    }

    .text-center {
        text-align: center;
    }

    .order-id {
        font-family: monospace;
        font-size: 0.8rem;
        color: #1e293b;
        background: #f1f5f9;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        display: inline-block;
    }

    .customer-name {
        font-weight: 600;
        color: #0f172a;
    }

    .customer-email {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 2px;
    }

    .price {
        font-weight: 600;
        color: #0f172a;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.3px;
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

    /* Action Button */
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s;
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .btn-detail:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem;
    }

    .empty-state-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        color: #94a3b8;
    }

    .empty-state-content svg {
        color: #cbd5e1;
    }

    /* Pagination */
    .pagination-container {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
        background: #fafcff;
    }

    .pagination-container nav {
        display: inline-block;
    }

    .pagination-container .pagination {
        display: flex;
        gap: 0.25rem;
        list-style: none;
        flex-wrap: wrap;
        justify-content: center;
    }

    .pagination-container .page-item .page-link {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        color: #475569;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: white;
    }

    .pagination-container .page-item .page-link:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }

    .pagination-container .page-item.active .page-link {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
    }

    .pagination-container .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8fafc;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .stat-icon svg {
            width: 24px;
            height: 24px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection