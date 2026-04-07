@extends('layouts.admin-sidebar')

@section('title', 'Tambah Jenis Item')

@section('content')
<div class="admin-container">
    <div class="card">
        <div class="card-header">
            <h2>
                <span>➕</span> Tambah Jenis Item
            </h2>
            <p>Buat kategori baru untuk koleksi barang persewaan</p>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.item-types.store') }}">
                @csrf
                <div class="form-group">
                    <label>Nama Jenis Item</label>
                    <input type="text" name="name" placeholder="Contoh: Dekorasi, Kursi, Panggung, Lampu" required autofocus>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.item-types.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Jenis Item</button>
                </div>
            </form>

            <div class="info-box">
                <p>💡 <strong>Tips:</strong> Gunakan nama jenis yang spesifik dan mudah dipahami, seperti "Dekorasi Bunga", "Sofa Pernikahan", atau "Lighting System".</p>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem;
    }

    .card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e9eef3;
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f0f2f5;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    }

    .card-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-header p {
        color: #64748b;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .card-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #334155;
        font-size: 0.875rem;
    }

    input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.9375rem;
        transition: all 0.2s;
        font-family: inherit;
    }

    input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    input::placeholder {
        color: #cbd5e1;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary {
        flex: 1;
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
        padding: 0.875rem 1.5rem;
        border-radius: 0.75rem;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
    }

    .btn-secondary {
        flex: 1;
        background: white;
        color: #475569;
        padding: 0.875rem 1.5rem;
        border-radius: 0.75rem;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        text-align: center;
        border: 2px solid #e2e8f0;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        border-color: #2563eb;
        color: #2563eb;
        background: #f8fafc;
    }

    .info-box {
        background: #eff6ff;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-top: 1.5rem;
        border-left: 4px solid #3b82f6;
    }

    .info-box p {
        font-size: 0.8rem;
        color: #1e40af;
        line-height: 1.5;
    }

    @media (max-width: 640px) {
        .admin-container {
            padding: 1rem;
        }
    }
</style>
@endsection