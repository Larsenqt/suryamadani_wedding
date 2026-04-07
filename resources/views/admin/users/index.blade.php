@extends('layouts.admin-sidebar')

@section('title', 'Kelola User')

@section('content')
<div class="admin-container">
    <a href="{{ route('admin.dashboard') }}" class="btn-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali ke Dashboard
    </a>

    <div class="page-header">
        <h2>Manajemen User</h2>
        <p>Kelola semua akun pengguna (Admin & Customer)</p>
    </div>


    <!-- Filter & Search Section -->
    <div class="filter-section">
        <div class="filter-group">
            <span class="filter-label">Filter Role:</span>
            <div class="filter-buttons">
                <a href="{{ route('admin.users.index') }}" class="filter-btn {{ !request('role') ? 'active' : '' }}">Semua</a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="filter-btn {{ request('role') == 'admin' ? 'active' : '' }}">Admin</a>
                <a href="{{ route('admin.users.index', ['role' => 'customer']) }}" class="filter-btn {{ request('role') == 'customer' ? 'active' : '' }}">Customer</a>
            </div>
        </div>
        
        <form action="{{ route('admin.users.index') }}" method="GET" class="search-form">
            <input type="hidden" name="role" value="{{ request('role') }}">
            <input type="text" name="search" placeholder="Cari nama atau email..." value="{{ request('search') }}" class="search-input">
            <button type="submit" class="search-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users.index', ['role' => request('role')]) }}" class="clear-btn">Clear</a>
            @endif
        </form>
    </div>

    <div class="header-actions">
        <div></div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Tambah User Baru
        </a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Tanggal Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td>
                        {{ $users->firstItem() + $index }}
                    </td>
                    <td>
                        <div class="user-name">{{ $user->name }}</div>
                    </td>
                    <td>
                        <div class="user-email">{{ $user->email }}</div>
                    </td>
                    <td>
                        {{ $user->phone ?? '-' }}
                    </td>
                    <td>
                        @php
                            $roleClass = $user->role === 'admin' ? 'badge-admin' : 'badge-customer';
                            $roleText = $user->role === 'admin' ? 'Admin' : 'Customer';
                        @endphp
                        <span class="badge {{ $roleClass }}">{{ $roleText }}</span>
                    </td>
                    <td>
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="action-buttons-cell">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-edit" title="Edit">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 3l4 4L7 21H3v-4L17 3z"/>
                                <path d="M15 5l4 4"/>
                            </svg>
                            Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Hapus">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <div class="empty-state-content">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            <p>Belum ada user</p>
                            <p class="empty-subtitle">Klik "Tambah User Baru" untuk memulai</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $users->appends(request()->query())->links() }}
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

    .flash-message {
        margin-bottom: 1.5rem;
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .flash-message.error {
        background: #fef2f2;
        border-color: #fee2e2;
        color: #991b1b;
    }

    .filter-section {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
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

    .search-form {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .search-input {
        padding: 0.375rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        width: 250px;
    }

    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
    }

    .search-btn, .clear-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
    }

    .search-btn {
        background: #0f172a;
        color: white;
        border: none;
    }

    .search-btn:hover {
        background: #1e293b;
    }

    .clear-btn {
        background: white;
        color: #475569;
        text-decoration: none;
        border: 1px solid #e2e8f0;
    }

    .clear-btn:hover {
        background: #f1f5f9;
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

    .user-name {
        font-weight: 600;
        color: #0f172a;
    }

    .user-email {
        font-size: 0.75rem;
        color: #64748b;
        margin-top: 2px;
    }

    .badge {
        display: inline-flex;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .badge-admin {
        background: #e0e7ff;
        color: #3730a3;
    }

    .badge-customer {
        background: #f1f5f9;
        color: #475569;
    }

    .action-buttons-cell {
        display: flex;
        gap: 0.75rem;
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

    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }
        
        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-form {
            width: 100%;
        }
        
        .search-input {
            flex: 1;
        }
        
        th, td {
            padding: 0.75rem;
        }
    }
</style>
@endsection