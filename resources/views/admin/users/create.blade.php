@extends('layouts.admin-sidebar')

@section('title', 'Tambah User')

@section('content')
<div class="admin-container">
    <a href="{{ route('admin.users.index') }}" class="btn-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar User
    </a>

    <div class="form-card">
        <div class="form-card-header">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Tambah User Baru
            </h2>
            <p>Buat akun baru untuk admin atau customer</p>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Telepon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="081234567890">
                        @error('phone')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Role <span class="required">*</span></label>
                        <select name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="address" rows="3" placeholder="Jl. Contoh No. 123, Kota">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Password <span class="required">*</span></label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-container {
        max-width: 800px;
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
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }

    .form-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .form-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e2e8f0;
        background: #fafcff;
    }

    .form-card-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-card-header p {
        color: #64748b;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        margin-left: 1.75rem;
    }

    .form-card-body {
        padding: 2rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #334155;
        font-size: 0.875rem;
    }

    .required {
        color: #ef4444;
    }

    input, select, textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-family: inherit;
        transition: all 0.2s;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    textarea {
        resize: vertical;
    }

    .error-text {
        color: #ef4444;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-primary, .btn-secondary {
        flex: 1;
        padding: 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-primary {
        background: #0f172a;
        color: white;
    }

    .btn-primary:hover {
        background: #1e293b;
        transform: translateY(-1px);
    }

    .btn-secondary {
        background: white;
        color: #475569;
        text-decoration: none;
        border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #f8fafc;
    }

    @media (max-width: 640px) {
        .admin-container {
            padding: 1rem;
        }
        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }
        .form-card-header h2 {
            font-size: 1.1rem;
        }
    }
</style>
@endsection