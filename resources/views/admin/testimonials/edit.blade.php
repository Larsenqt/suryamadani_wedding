@extends('layouts.admin-sidebar')

@section('title', 'kelola Testimoni Dokumentasi')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
        }

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
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateX(-2px);
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
            font-weight: 600;
            color: #334155;
            font-size: 0.875rem;
        }

        label .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        input, textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.9375rem;
            transition: all 0.2s;
            font-family: inherit;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .current-image {
            margin-top: 0.5rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            text-align: center;
        }

        .current-image img {
            max-width: 200px;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .current-image-label {
            font-size: 0.75rem;
            color: #64748b;
            margin-bottom: 0.5rem;
            display: block;
        }

        .image-preview {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            text-align: center;
            border: 2px dashed #e2e8f0;
        }

        .image-preview img {
            max-width: 200px;
            border-radius: 0.5rem;
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

        .warning-text {
            font-size: 0.75rem;
            color: #f59e0b;
            margin-top: 0.5rem;
        }

        @media (max-width: 640px) {
            .admin-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <a href="{{ route('admin.testimonials.index') }}" class="btn-back">
            ← Kembali ke Daftar Testimoni
        </a>

        <div class="card">
            <div class="card-header">
                <h2>
                    <span>✏️</span> Edit Testimoni
                </h2>
                <p>Perbarui testimoni pelanggan</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.testimonials.update', $testimonial->id) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    <div class="form-group">
                        <label>Nama / Title <span class="required">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $testimonial->title) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Testimoni <span class="required">*</span></label>
                        <textarea name="description" required>{{ old('description', $testimonial->description) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Foto Saat Ini</label>
                        <div class="current-image">
                            @if($testimonial->image && file_exists(storage_path('app/public/'.$testimonial->image)))
                                <img src="{{ asset('storage/'.$testimonial->image) }}" alt="Current Image">
                                <div class="current-image-label">Foto saat ini</div>
                            @else
                                <div style="color:#94a3b8;">Tidak ada foto</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ganti Foto (Opsional)</label>
                        <input type="file" name="image" accept="image/*" id="imageInput">
                        <div class="image-preview" id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                            <div class="current-image-label">Preview foto baru</div>
                        </div>
                        <div class="warning-text">* Kosongkan jika tidak ingin mengubah foto</div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">Update Testimoni</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
</body>
@endsection