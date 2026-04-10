@extends('layouts.admin-sidebar')

@section('title', 'Manage Invoice')

@section('content') 
<div class="admin-container">

    <div class="page-header">
        <h2>Manajemen Invoice</h2>
        <p>Kelola dan pantau semua invoice</p>
    </div>

    <!-- Filter Section - Status Filter -->
    <div class="filter-section">
        <span class="filter-label">Filter Status:</span>
        <div class="filter-buttons">
            <a href="{{ route('admin.invoices.index') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('admin.invoices.index', ['status' => 'draft']) }}" class="filter-btn {{ request('status') == 'draft' ? 'active' : '' }}">Draft</a>
            <a href="{{ route('admin.invoices.index', ['status' => 'approved']) }}" class="filter-btn {{ request('status') == 'approved' ? 'active' : '' }}">Approved</a>
            <a href="{{ route('admin.invoices.index', ['status' => 'paid']) }}" class="filter-btn {{ request('status') == 'paid' ? 'active' : '' }}">Paid</a>
            <a href="{{ route('admin.invoices.index', ['status' => 'cancelled']) }}" class="filter-btn {{ request('status') == 'cancelled' ? 'active' : '' }}">Cancelled</a>
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
        
        <form action="{{ route('admin.invoices.export.excel') }}" method="GET" class="export-form">
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
                    <label>Status</label>
                    <select name="status" class="select-input full-width">
                        <option value="all">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="approved">Approved</option>
                        <option value="paid">Paid</option>
                        <option value="cancelled">Cancelled</option>
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

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Total Invoices</p>
                <p class="stat-value">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Draft</p>
                <p class="stat-value">{{ $stats['draft'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Approved</p>
                <p class="stat-value">{{ $stats['approved'] }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-info">
                <p class="stat-label">Paid</p>
                <p class="stat-value">{{ $stats['paid'] }}</p>
            </div>
        </div>
    </div>
    

    <!-- Filter & Search -->
    <div class="filter-search">
        <form method="GET" class="filter-search-form">
            <input type="text" name="search" placeholder="Cari invoice atau customer..." value="{{ request('search') }}" class="search-input">
            <button type="submit" class="btn-filter-search">Cari</button>
            <a href="{{ route('admin.invoices.index') }}" class="btn-reset">Reset</a>
        </form>
    </div>

    <div class="create-button-wrapper">
        <a href="{{ route('admin.invoices.create') }}" class="btn-create">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Buat Invoice Baru
        </a>
    </div>

<!-- Table -->
<div class="table-container">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>DP</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $index => $invoice)
                <tr class="hover:bg-gray-50">
                    <td style="text-align: center;">{{ $invoices->firstItem() + $index }}</td>
                    <td><span class="font-mono text-sm font-semibold">{{ $invoice->invoice_number }}</span></td>
                    <td>
                        <div class="font-medium text-gray-900">{{ $invoice->customer_name }}</div>
                        <div class="text-xs text-gray-500">{{ $invoice->customer_email ?? '-' }}</div>
                    </td>
                    <td class="text-sm text-gray-600">{{ $invoice->order_date->format('d M Y') }}</td>
                    <td class="font-semibold">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($invoice->dp_amount, 0, ',', '.') }}
                        @if($invoice->dp_due_date)
                            <div class="text-xs text-gray-400">Due: {{ $invoice->dp_due_date->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td class="font-semibold {{ $invoice->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                        Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}
                    </td>
                    <td>
                        <div class="space-y-1">
                            @if($invoice->status == 'draft')
                                <span class="badge badge-draft">Draft</span>
                            @elseif($invoice->status == 'approved')
                                <span class="badge badge-approved">Approved</span>
                            @elseif($invoice->status == 'paid')
                                <span class="badge badge-paid">Paid</span>
                            @else
                                <span class="badge badge-cancelled">Cancelled</span>
                            @endif
                        </div>
                    </td>
                    <td class="action-buttons-cell">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn-action" title="View">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Detail
                        </a>
                        <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn-action" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 3l4 4L7 21H3v-4L17 3z"/>
                                <path d="M15 5l4 4"/>
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.invoices.preview-pdf', $invoice) }}" target="_blank" class="btn-action" title="Download PDF">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            PDF
                        </a>
                        <button type="button" class="btn-action-delete" onclick="confirmDelete({{ $invoice->id }}, '{{ addslashes($invoice->invoice_number) }}', '{{ addslashes($invoice->customer_name) }}')" title="Hapus">
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
                    <td colspan="9" class="empty-state">
                        <div class="empty-state-content">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            <p>Belum ada invoice</p>
                            <p class="empty-subtitle">Klik "Buat Invoice Baru" untuk memulai</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

        
        <!-- PAGINATION -->
        <div class="pagination-wrapper">
            @if($invoices->hasPages())
                <div class="pagination-container">
                    {{ $invoices->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
    <form id="deleteForm" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
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

    /* Filter Section */
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

    .filter-btn:hover, .filter-btn.active {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }

    /* Export Section */
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

    .date-input, .select-input {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background: white;
    }

    .date-separator {
        color: #94a3b8;
        font-size: 0.75rem;
    }

    .select-group {
        display: flex;
        gap: 0.5rem;
    }

    .full-width {
        width: 100%;
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
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1rem;
        border: 1px solid #e2e8f0;
    }

    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0f172a;
    }

    /* Filter Search */
    .filter-search {
        background: white;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .filter-search-form {
        display: flex;
        gap: 0.5rem;
    }

    .search-input {
        flex: 1;
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }

    .btn-filter-search {
        padding: 0.5rem 1rem;
        background: #0f172a;
        color: white;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
    }

    .btn-reset {
        padding: 0.5rem 1rem;
        background: white;
        color: #475569;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
    }

    /* Table */
    .table-container {
        background: white;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .overflow-x-auto {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    th {
        background: #f8fafc;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover td {
        background: #fafcff;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .badge-draft { background: #f1f5f9; color: #475569; }
    .badge-approved { background: #dbeafe; color: #1e40af; }
    .badge-paid { background: #dcfce7; color: #166534; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }

    .action-buttons-cell {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        color: #475569;
        font-size: 0.7rem;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-action:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
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

    .empty-subtitle {
        font-size: 0.75rem;
        color: #cbd5e1;
    }

    /* Pagination Styles */
    .pagination-wrapper {
        padding: 1rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: center;
    }

    .pagination-container {
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
        cursor: pointer;
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

    /* Create Button Styles */
    .create-button-wrapper {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: flex-end;
    }

    .btn-create {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); 
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .btn-create:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); 
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        color: white;
    }

    .btn-create svg {
        width: 18px;
        height: 18px;
    }

    @media (max-width: 1024px) {
        .export-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }
        .export-grid {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .filter-search-form {
            flex-direction: column;
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
        .pagination-container .pagination {
            gap: 0.125rem;
        }
        .pagination-container .page-item .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    /* Delete Button Styles */
    .btn-action-delete {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 0.375rem;
        color: #dc2626;
        font-size: 0.7rem;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-action-delete:hover {
        background: #fee2e2;
        border-color: #dc2626;
        color: #b91c1c;
        transform: translateY(-1px);
    }
</style>
{{-- Tambahkan sebelum tag @endsection --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ============================================
// DELETE INVOICE - Konfirmasi Hapus
// ============================================
function confirmDelete(invoiceId, invoiceNumber, customerName) {
    Swal.fire({
        title: 'Hapus Invoice?',
        html: `
            <div style="text-align: left;">
                <p>Apakah Anda yakin ingin menghapus invoice:</p>
                <p style="font-weight: bold; color: #dc2626; margin: 10px 0;">
                    "${invoiceNumber}" - ${customerName}
                </p>
                <hr style="margin: 15px 0;">
                <p style="font-size: 13px; color: #999;">Tindakan ini tidak dapat dibatalkan!</p>
                <p style="font-size: 12px; color: #f59e0b;">Semua data invoice akan dihapus secara permanen.</p>
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
                form.action = `/admin/invoices/${invoiceId}`;
                form.submit();
                resolve();
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
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
});
</script>
@endsection
