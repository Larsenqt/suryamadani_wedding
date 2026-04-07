@extends('layouts.admin-sidebar')

@section('title', 'Kelola Item')

@section('content')
<div class="admin-container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali ke Dashboard
    </a>

    <div class="page-header">
        <h2>Manajemen Item</h2>
        <p>Kelola koleksi barang persewaan untuk acara (Wedding, Pesta, dll)</p>
    </div>

    <div class="header-actions">
        <div></div>
        <a href="{{ route('admin.items.create') }}" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Item Baru
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">No</th>
                    <th style="width: 80px; text-align: center;">Gambar</th>
                    <th style="text-align: left;">Nama Item</th>
                    <th style="text-align: left;">Jenis</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: center;">Stok</th>
                    <th style="text-align: center; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                <tr>
                    <td style="text-align: center; vertical-align: middle;">
                        {{ $items->firstItem() + $index }}
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        @if($item->image && file_exists(storage_path('app/public/'.$item->image)))
                            <img src="{{ asset('storage/' . $item->image) }}" class="product-image" alt="{{ $item->name }}">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </td>
                    <td style="text-align: left; vertical-align: middle;">
                        <strong>{{ $item->name }}</strong>
                    </td>
                    <td style="text-align: left; vertical-align: middle;">
                        <span class="category-badge">
                            {{ $item->type->name ?? 'Tidak ada jenis' }}
                        </span>
                    </td>
                    <td style="text-align: right; vertical-align: middle;" class="price">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        @php
                            $stockClass = $item->stock <= 0 ? 'stock-out' : ($item->stock <= 5 ? 'stock-low' : 'stock-normal');
                            $stockText = $item->stock <= 0 ? 'Habis' : ($item->stock <= 5 ? 'Menipis' : 'Tersedia');
                        @endphp
                        <span class="stock-badge {{ $stockClass }}">
                            {{ $item->stock }} ({{ $stockText }})
                        </span>
                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <div class="action-buttons">
                            <a href="{{ route('admin.items.edit', $item->id) }}" class="btn-edit" title="Edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 3l4 4L7 21H3v-4L17 3z"/>
                                    <path d="M15 5l4 4"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item {{ $item->name }}?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Hapus">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <div class="empty-state-content">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                            </svg>
                            <p>Belum ada item</p>
                            <p class="empty-subtitle">Klik "Tambah Item Baru" untuk memulai</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $items->links() }}
    </div>
</div>

<style>
    .admin-container {
        max-width: 1400px;
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
        margin-bottom: 1rem;
    }

    .btn-back:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
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

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #0f172a;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
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
        min-width: 800px;
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
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover td {
        background: #fafcff;
    }

    .product-image {
        width: 50px;
        height: 50px;
        border-radius: 0.5rem;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }

    .no-image {
        width: 50px;
        height: 50px;
        background: #f1f5f9;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .category-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: #f1f5f9;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 500;
        color: #475569;
    }

    .price {
        font-weight: 600;
        color: #0f172a;
    }

    .stock-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .stock-normal {
        background: #dcfce7;
        color: #166534;
    }

    .stock-low {
        background: #fef3c7;
        color: #92400e;
    }

    .stock-out {
        background: #fee2e2;
        color: #991b1b;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        justify-content: center;
    }

    .btn-edit, .btn-delete {
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
        cursor: pointer;
    }

    .btn-edit:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }

    .btn-delete:hover {
        background: #fef2f2;
        border-color: #fee2e2;
        color: #dc2626;
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

    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }
        
        th, td {
            padding: 0.75rem;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
        }
    }
</style>
@endsection