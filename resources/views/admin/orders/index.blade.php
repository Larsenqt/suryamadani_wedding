@extends('layouts.admin-sidebar')

@section('title', 'Kelola Pesanan')

@section('content') 
<div class="admin-container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali ke Dashboard
    </a>

    <div class="page-header">
        <h2>Manajemen Pesanan</h2>
        <p>Kelola dan pantau semua pesanan dari pelanggan</p>
    </div>

    <!-- Filter Section - Status Filter -->
    <div class="filter-section">
        <span class="filter-label">Filter Status:</span>
        <div class="filter-buttons">
            <a href="{{ route('admin.orders.index') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="filter-btn {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.orders.index', ['status' => 'approved']) }}" class="filter-btn {{ request('status') == 'approved' ? 'active' : '' }}">Approved</a>
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="filter-btn {{ request('status') == 'completed' ? 'active' : '' }}">Completed</a>
            <a href="{{ route('admin.orders.index', ['status' => 'rejected']) }}" class="filter-btn {{ request('status') == 'rejected' ? 'active' : '' }}">Rejected</a>
        </div>
    </div>

    <!-- Export Filter Section -->
    <div class="export-section">
        <div class="export-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
            Export Data ke Excel
        </div>
        
        <form action="{{ route('admin.orders.export.excel') }}" method="GET" class="export-form">
            <div class="export-grid">
                <div class="export-field">
                    <label>Rentang Tanggal</label>
                    <div class="date-input-group">
                        <input type="date" name="start_date" placeholder="Dari" class="date-input">
                        <span class="date-separator">s/d</span>
                        <input type="date" name="end_date" placeholder="Sampai" class="date-input">
                    </div>
                </div>
                <div class="export-field">
                    <label>Bulan & Tahun</label>
                    <div class="select-group">
                        <select name="month" class="select-input">
                            <option value="">Pilih Bulan</option>
                            @foreach($months as $key => $month)
                                <option value="{{ $key }}">{{ $month }}</option>
                            @endforeach
                        </select>
                        <select name="year" class="select-input">
                            <option value="">Pilih Tahun</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="export-field">
                    <label>Status Pesanan</label>
                    <select name="status" class="select-input full-width">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="completed">Completed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="export-field export-button">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn-export">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Export ke Excel
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div class="bulk-actions" style="display: none; margin-bottom: 1rem;" id="bulkActions">
        <div class="bulk-actions-content">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            <span id="selectedCount">0</span> pesanan dipilih
            <button type="button" class="btn-bulk-delete" onclick="confirmBulkDelete()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    <line x1="10" y1="11" x2="10" y2="17"/>
                    <line x1="14" y1="11" x2="14" y2="17"/>
                </svg>
                Hapus yang Dipilih
            </button>
            <button type="button" class="btn-clear-selection" onclick="clearSelection()">Batal</button>
        </div>
    </div>

    <!-- Form Delete -->
    <form id="deleteForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <form id="bulkDeleteForm" action="{{ route('admin.orders.bulk-destroy') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="order_ids" id="bulkOrderIds">
    </form>

    <!-- Table Content -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">
                        <input type="checkbox" id="select-all" style="cursor: pointer;">
                    </th>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>ID Order</th>
                    <th>Pelanggan</th>
                    <th>Total Pesanan</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th style="text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $index => $order)
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" class="order-checkbox" value="{{ $order->uuid }}" style="cursor: pointer;">
                    </td>
                    <td style="text-align: center;">
                        {{ $orders->firstItem() + $index }}
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
                                default:
                                    $statusClass = 'badge-pending';
                                    $statusText = $order->status;
                            }
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                    <td class="order-date">
                        {{ $order->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="action-buttons-cell">
                        <a href="{{ route('admin.orders.show', $order->uuid) }}" class="btn-detail" title="Detail">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Detail
                        </a>
                        <a href="{{ route('admin.orders.edit', $order->uuid) }}" class="btn-edit" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 3l4 4L7 21H3v-4L17 3z"/>
                                <path d="M15 5l4 4"/>
                            </svg>
                            Edit
                        </a>
                        @if($order->status == 'approved' || $order->status == 'completed')
                        <a href="{{ route('admin.orders.invoice.view', $order->uuid) }}" class="btn-invoice" target="_blank" title="Lihat Invoice">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                            Invoice
                        </a>
                        @endif
                        <button type="button" class="btn-delete" onclick="confirmDelete('{{ $order->uuid }}', '{{ addslashes($order->user->name) }}', '{{ route('admin.orders.destroy', $order->uuid) }}')" title="Hapus">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                <line x1="10" y1="11" x2="10" y2="17"/>
                                <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                            Hapus
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-state">
                        <div class="empty-state-content">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            <p>Belum ada pesanan</p>
                            <p class="empty-subtitle">Pesanan akan muncul ketika pelanggan melakukan pemesanan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $orders->links() }}
    </div>
</div>

<style>
    .admin-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }
    .page-header {
        margin-bottom: 2rem;
    }
    .page-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
        border-left: 3px solid #3b82f6;
        padding-left: 1rem;
    }
    .page-header p {
        color: #64748b;
        font-size: 0.875rem;
        padding-left: 1rem;
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
        margin-bottom: 1rem;
    }
    .btn-back:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }
    .filter-section {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-label {
        font-weight: 500;
        color: #475569;
        font-size: 0.875rem;
    }
    .filter-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .filter-btn {
        padding: 0.375rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        background: white;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .filter-btn:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }
    .filter-btn.active {
        background: #eff6ff;
        color: #2563eb;
        border-color: #3b82f6;
        font-weight: 600;
    }
    .export-section {
        background: white;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }
    .export-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .export-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        align-items: end;
    }
    .export-field {
        display: flex;
        flex-direction: column;
    }
    .export-field label {
        font-size: 0.7rem;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .date-input-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .date-input {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-family: inherit;
        background: white;
    }
    .date-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    .date-separator {
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .select-group {
        display: flex;
        gap: 0.5rem;
    }
    .select-input {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-family: inherit;
        background: white;
        cursor: pointer;
    }
    .select-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }
    .full-width {
        width: 100%;
    }
    .export-button {
        display: flex;
        flex-direction: column;
    }
    .btn-export {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.5rem 1rem;
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        height: 38px;
    }
    .btn-export:hover {
        background: #1e293b;
        transform: translateY(-1px);
    }
    .table-container {
        background: white;
        border-radius: 1rem;
        overflow-x: auto;
        border: 1px solid #e2e8f0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    th {
        background: #fafcff;
        padding: 1rem 1rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
    }
    td {
        padding: 1rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }
    tr:last-child td {
        border-bottom: none;
    }
    tr:hover td {
        background: #fafcff;
    }
    .order-id {
        font-family: monospace;
        font-size: 0.8rem;
        color: #1e293b;
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
    .order-date {
        font-size: 0.875rem;
        color: #64748b;
        white-space: nowrap;
    }
    .badge {
        display: inline-flex;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.3px;
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
        background: #e0e7ff;
        color: #3730a3;
    }
    .action-buttons-cell {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }
    .btn-detail, .btn-edit, .btn-invoice {
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
    .btn-detail:hover, .btn-edit:hover, .btn-invoice:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }
    .btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.75rem;
        font-weight: 500;
        transition: all 0.2s;
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        cursor: pointer;
        font-family: inherit;
    }
    .btn-delete:hover {
        background: #fee2e2;
        border-color: #dc2626;
        color: #b91c1c;
        transform: translateY(-1px);
    }
    .bulk-actions {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 0.75rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        animation: slideDown 0.3s ease-out;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .bulk-actions-content {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .bulk-actions-content svg {
        color: #3b82f6;
    }
    .bulk-actions-content span {
        font-weight: 700;
        color: #3b82f6;
        font-size: 1rem;
    }
    .btn-bulk-delete {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        background: #dc2626;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-bulk-delete:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
    }
    .btn-clear-selection {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-clear-selection:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #3b82f6;
    }
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
    .empty-subtitle {
        font-size: 0.75rem;
        color: #cbd5e1;
    }
    .pagination-container {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
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
        background: #0f172a;
        border-color: #0f172a;
        color: white;
    }
    .pagination-container .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f8fafc;
    }
    @media (max-width: 1024px) {
        .export-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }
    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }
        th, td {
            padding: 0.75rem;
        }
        .action-buttons-cell {
            flex-direction: column;
            align-items: flex-start;
        }
        .export-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        .date-input-group {
            flex-direction: column;
        }
        .date-separator {
            display: none;
        }
        .select-group {
            flex-direction: column;
        }
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

    document.addEventListener('DOMContentLoaded', function() {
        initCheckboxHandlers();
        showNotifications();
    });

    function confirmDelete(uuid, customerName, deleteUrl) {
        Swal.fire({
            title: 'Hapus Pesanan?',
            html: `
                <div style="text-align: left;">
                    <p>Apakah Anda yakin ingin menghapus pesanan dari:</p>
                    <p style="font-weight: bold; color: #dc2626; margin: 10px 0;">"${customerName}"</p>
                    <p style="font-size: 12px; color: #666;">ID Order: ${uuid.substring(0, 8)}</p>
                    <hr style="margin: 15px 0;">
                    <p style="font-size: 13px; color: #999;">Tindakan ini tidak dapat dibatalkan!</p>
                    <p style="font-size: 12px; color: #f59e0b;">Stok akan dikembalikan otomatis jika pesanan sudah Approved/Completed</p>
                </div>
            `,
            icon: 'warning',
            iconColor: '#64748b',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    const form = document.getElementById('deleteForm');
                    form.action = deleteUrl;
                    form.submit();
                    resolve();
                });
            }
        });
    }

    function confirmBulkDelete() {
        const checkboxes = document.querySelectorAll('.order-checkbox:checked');
        const count = checkboxes.length;
        const orderIds = Array.from(checkboxes).map(cb => cb.value);
        
        if (count === 0) {
            Swal.fire({
                title: 'Tidak Ada Pesanan Dipilih',
                text: 'Silakan pilih pesanan yang ingin dihapus terlebih dahulu.',
                icon: 'info',
                iconColor: '#64748b',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        let customerNames = [];
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            const customerName = row.querySelector('.customer-name')?.innerText || 'Unknown';
            customerNames.push(customerName);
        });
        
        Swal.fire({
            title: `Hapus ${count} Pesanan?`,
            html: `
                <div style="text-align: left; max-height: 300px; overflow-y: auto;">
                    <p>Anda akan menghapus pesanan dari:</p>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        ${customerNames.map(name => `<li style="margin: 5px 0;">${name}</li>`).join('')}
                    </ul>
                    <hr style="margin: 15px 0;">
                    <p style="font-size: 13px; color: #999;">Tindakan ini tidak dapat dibatalkan!</p>
                    <p style="font-size: 12px; color: #f59e0b;">Stok akan dikembalikan otomatis untuk pesanan yang sudah Approved/Completed</p>
                </div>
            `,
            icon: 'warning',
            iconColor: '#64748b',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: `Ya, Hapus ${count} Pesanan!`,
            cancelButtonText: 'Batal',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    document.getElementById('bulkOrderIds').value = JSON.stringify(orderIds);
                    document.getElementById('bulkDeleteForm').submit();
                    resolve();
                });
            }
        });
    }

    function initCheckboxHandlers() {
        const selectAllCheckbox = document.getElementById('select-all');
        const orderCheckboxes = document.querySelectorAll('.order-checkbox');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCountSpan = document.getElementById('selectedCount');
        
        if (!selectAllCheckbox || !bulkActions) return;
        
        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const count = checkedBoxes.length;
            
            if (count > 0) {
                bulkActions.style.display = 'block';
                if (selectedCountSpan) selectedCountSpan.textContent = count;
            } else {
                bulkActions.style.display = 'none';
            }
            
            if (selectAllCheckbox) {
                const totalCheckboxes = document.querySelectorAll('.order-checkbox').length;
                selectAllCheckbox.checked = count === totalCheckboxes && totalCheckboxes > 0;
                selectAllCheckbox.indeterminate = count > 0 && count < totalCheckboxes;
            }
        }
        
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = selectAllCheckbox.checked;
            orderCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateBulkActions();
        });
        
        orderCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });
        
        updateBulkActions();
    }

    function clearSelection() {
        document.querySelectorAll('.order-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        const selectAll = document.getElementById('select-all');
        if (selectAll) selectAll.checked = false;
        
        const bulkActions = document.getElementById('bulkActions');
        if (bulkActions) bulkActions.style.display = 'none';
    }

    function showNotifications() {
        @if(session('success'))
        Swal.fire({
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            icon: 'success',
            iconColor: '#64748b',
            confirmButtonColor: '#3b82f6',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
        @endif

        @if(session('error'))
        Swal.fire({
            title: 'Gagal!',
            text: '{{ session('error') }}',
            icon: 'error',
            iconColor: '#64748b',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'OK'
        });
        @endif
    }
    </script>
@endsection