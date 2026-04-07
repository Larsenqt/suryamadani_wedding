@extends('layouts.admin-sidebar')

@section('title', 'Kelola Testimoni')

@section('content')
<div class="admin-container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali ke Dashboard
    </a>

    <div class="page-header">
        <h2>Manajemen Testimoni</h2>
        <p>Kelola ulasan dan testimoni dari pelanggan yang telah menggunakan jasa kami</p>
    </div>

    <div class="header-actions">
        <div></div>
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah Testimoni
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gambar</th>
                    <th>Judul</th>
                    <th>Testimoni</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($testimonials as $index => $t)
                <tr>
                    <td class="text-center">
                        {{ $testimonials->firstItem() + $index }}
                    </td>
                    <td class="text-center">
                        @if($t->image && file_exists(storage_path('app/public/'.$t->image)))
                            <img src="{{ asset('storage/'.$t->image) }}" class="testimonial-image" alt="{{ $t->title }}">
                        @else
                            <div class="no-image">No Image</div>
                        @endif
                    </td>
                    <td>
                        <div class="testimonial-title">{{ $t->title }}</div>
                    </td>
                    <td>
                        <div class="testimonial-description">
                            {{ Str::limit($t->description, 100) }}
                        </div>
                    </td>
                    <td class="date-column">
                        {{ $t->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="action-buttons-cell">
                        <a href="{{ route('admin.testimonials.edit', $t->id) }}" class="btn-edit" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 3l4 4L7 21H3v-4L17 3z"/>
                                <path d="M15 5l4 4"/>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Hapus">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <div class="empty-state-content">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <p>Belum ada testimoni</p>
                            <p class="empty-subtitle">Klik "Tambah Testimoni" untuk memulai</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $testimonials->links() }}
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

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
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
        text-align: left;
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

    .text-center {
        text-align: center;
    }

    .testimonial-image {
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

    .testimonial-title {
        font-weight: 600;
        color: #0f172a;
    }

    .testimonial-description {
        font-size: 0.813rem;
        color: #475569;
        line-height: 1.4;
    }

    .date-column {
        font-size: 0.813rem;
        color: #64748b;
        white-space: nowrap;
    }

    .action-buttons-cell {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
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
    }

    .pagination-container .page-item .page-link {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        color: #475569;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s;
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
    }

    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }
        
        th, td {
            padding: 0.75rem;
        }
    }
</style>
@endsection