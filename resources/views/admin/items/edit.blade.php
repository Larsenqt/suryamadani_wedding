@extends('layouts.admin-sidebar')

@section('title', 'Kelola item')

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

        input, select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.9375rem;
            transition: all 0.2s;
            font-family: inherit;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Price input specific styling */
        .price-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .price-prefix {
            position: absolute;
            left: 1rem;
            color: #64748b;
            font-weight: 500;
            pointer-events: none;
        }

        .price-input {
            padding-left: 2.5rem !important;
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

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .warning-text {
            font-size: 0.75rem;
            color: #f59e0b;
            margin-top: 0.5rem;
        }

        @media (max-width: 640px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .admin-container {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <a href="{{ route('admin.items.index') }}" class="btn-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Item
        </a>

        <div class="card">
            <div class="card-header">
                <h2>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 3l4 4L7 21H3v-4L17 3z"/>
                        <path d="M15 5l4 4"/>
                    </svg>
                    Edit Item
                </h2>
                <p>Perbarui informasi barang persewaan</p>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.items.update', $item->id) }}" enctype="multipart/form-data" id="itemForm">
                    @csrf @method('PUT')
                    
                    <div class="form-group">
                        <label>Jenis Item <span class="required">*</span></label>
                        <select name="item_type_id" required>
                            <option value="">Pilih Jenis Item</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" 
                                    {{ $item->item_type_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nama Item <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $item->name) }}" required>
                    </div>

                    <div class="info-grid">
                        <div class="form-group">
                            <label>Harga (Rp) <span class="required">*</span></label>
                            <div class="price-input-wrapper">
                                <span class="price-prefix">Rp</span>
                                <input type="text" id="price" name="price" 
                                    value="{{ number_format(old('price', $item->price), 0, ',', '.') }}" 
                                    class="price-input" 
                                    required 
                                    onkeyup="formatPrice(this)"
                                    onblur="formatPriceOnBlur(this)">
                            </div>
                            <input type="hidden" id="price_raw" name="price_raw">
                        </div>

                        <div class="form-group">
                            <label>Stok <span class="required">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $item->stock) }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Gambar Saat Ini</label>
                        <div class="current-image">
                            @if($item->image && file_exists(storage_path('app/public/'.$item->image)))
                                <img src="{{ asset('storage/'.$item->image) }}" alt="Current Image">
                                <div class="current-image-label">Gambar saat ini</div>
                            @else
                                <div style="color:#94a3b8;">Tidak ada gambar</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Ganti Gambar (Opsional)</label>
                        <input type="file" name="image" accept="image/*" id="imageInput">
                        <div class="image-preview" id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                            <div class="current-image-label">Preview gambar baru</div>
                        </div>
                        <div class="warning-text">* Kosongkan jika tidak ingin mengubah gambar</div>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('admin.items.index') }}" class="btn-secondary">Batal</a>
                        <button type="submit" class="btn-primary">Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk memformat harga (saat typing)
        function formatPrice(input) {
            // Hapus semua karakter non-digit
            let value = input.value.replace(/[^\d]/g, '');
            
            if (value === '') {
                input.value = '';
                document.getElementById('price_raw').value = '';
                return;
            }
            
            // Konversi ke integer
            let number = parseInt(value, 10);
            
            // Format dengan titik sebagai pemisah ribuan
            input.value = number.toLocaleString('id-ID');
            document.getElementById('price_raw').value = number;
        }

        // Fungsi untuk memformat harga saat blur (keluar dari field)
        function formatPriceOnBlur(input) {
            let value = input.value.replace(/[^\d]/g, '');
            
            if (value === '') {
                input.value = '';
                document.getElementById('price_raw').value = '';
                return;
            }
            
            let number = parseInt(value, 10);
            input.value = number.toLocaleString('id-ID');
            document.getElementById('price_raw').value = number;
        }

        // Saat form di-submit, pastikan price_raw terisi
        document.getElementById('itemForm').addEventListener('submit', function(e) {
            const priceInput = document.getElementById('price');
            const priceRaw = document.getElementById('price_raw');
            
            // Jika price_raw kosong, ambil dari price input
            if (!priceRaw.value) {
                let rawValue = priceInput.value.replace(/[^\d]/g, '');
                priceRaw.value = rawValue ? parseInt(rawValue, 10) : '';
            }
            
            // Ganti value price dengan raw value untuk dikirim ke server
            if (priceRaw.value) {
                // Buat input hidden terpisah untuk mengirim nilai asli
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'price';
                hiddenInput.value = priceRaw.value;
                priceInput.name = 'price_display'; // rename input display
                this.appendChild(hiddenInput);
            }
        });

        // Preview gambar
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