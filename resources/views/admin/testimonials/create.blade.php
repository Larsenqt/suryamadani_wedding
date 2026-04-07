@extends('layouts.admin-sidebar')

@section('title', 'Tambah Testimoni')

@section('content')
<div class="admin-container">
    <a href="{{ route('admin.testimonials.index') }}" class="btn-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Testimoni
    </a>

    <div class="form-card">
        <div class="form-card-header">
            <h2>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                Tambah Testimoni Baru
            </h2>
            <p>Masukkan testimoni dari pelanggan yang puas dengan layanan kami</p>
        </div>
        <div class="form-card-body">
            <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Judul / Nama Pelanggan <span class="required">*</span></label>
                    <input type="text" name="title" placeholder="Contoh: Bapak Ahmad - Acara Pernikahan" value="{{ old('title') }}" required>
                    @error('title')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Testimoni</label>
                    <textarea name="description" rows="4" placeholder="Tulis testimoni dari pelanggan...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Foto Pelanggan <span class="required">*</span></label>
                    <input type="file" name="image" accept="image/*" id="imageInput" required>
                    <div class="image-preview" id="imagePreview" style="display: none;">
                        <img id="previewImg" src="" alt="Preview">
                        <div class="preview-text">Preview gambar</div>
                    </div>
                    @error('image')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Testimoni</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-container {
        max-width: 700px;
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

    .required {
        color: #ef4444;
    }

    input, textarea, select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-family: inherit;
        transition: all 0.2s;
    }

    input:focus, textarea:focus, select:focus {
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

    .image-preview {
        margin-top: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.5rem;
        text-align: center;
        border: 1px dashed #e2e8f0;
    }

    .image-preview img {
        max-width: 150px;
        border-radius: 0.5rem;
    }

    .preview-text {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 0.5rem;
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
        .form-card-header h2 {
            font-size: 1.1rem;
        }
    }
</style>

<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const img = document.getElementById('previewImg');
        
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                img.src = event.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(e.target.files[0]);
        } else {
            preview.style.display = 'none';
        }
    });
</script>
@endsection