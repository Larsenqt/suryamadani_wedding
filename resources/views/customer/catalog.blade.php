@extends('layouts.customer') 

@section('title', 'Catalog')

@section('content')
<style>

    .catalog-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .catalog-header {
        margin-bottom: 2rem;
    }

    .catalog-header h1 {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        margin-bottom: 0.5rem;
    }

    .catalog-header p {
        color: #64748b;
    }

    .filter-section {
        background: white;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        border: 1px solid #e2e8f0;
    }

    .filter-group {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-select, .search-input {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
    }

    .filter-select:focus, .search-input:focus {
        outline: none;
        border-color: #2563eb;
    }

    .btn-filter {
        background: #0f172a;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-filter:hover {
        background: #1e293b;
    }

    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .product-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #eef2f6;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        border-color: #e2e8f0;
    }

    .product-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        z-index: 10;
    }

    .badge-stock {
        background: #dcfce7;
        color: #166534;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: 500;
    }

    .badge-stock-low {
        background: #fef3c7;
        color: #92400e;
    }

    .product-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: #f8fafc;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.02);
    }

    .product-info {
        padding: 1rem;
    }

    .product-category {
        font-size: 0.7rem;
        color: #2563eb;
        background: #eff6ff;
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .product-name {
        font-weight: 600;
        font-size: 1rem;
        color: #0f172a;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2563eb;
        margin-bottom: 0.5rem;
    }

    .product-stock {
        font-size: 0.7rem;
        color: #64748b;
        margin-bottom: 1rem;
    }

    .stock-available {
        color: #10b981;
    }

    .stock-out {
        color: #ef4444;
    }

    .btn-add-cart {
        width: 100%;
        background: #0f172a;
        color: white;
        border: none;
        padding: 0.625rem;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-add-cart:hover {
        background: #1e293b;
        transform: translateY(-1px);
    }

    .btn-add-cart:disabled {
        background: #cbd5e1;
        cursor: not-allowed;
        transform: none;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        border-radius: 1rem;
        max-width: 450px;
        width: 90%;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
        transition: color 0.2s;
    }

    .close-modal:hover {
        color: #0f172a;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-item-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        background: #f1f5f9;
    }

    .modal-item-name {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }

    .modal-item-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2563eb;
        margin-bottom: 1rem;
    }

    .modal-item-stock {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 1rem;
    }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .qty-control label {
        font-weight: 500;
        color: #334155;
    }

    .qty-input-wrapper {
        display: flex;
        align-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .qty-btn {
        width: 36px;
        height: 36px;
        background: #f8fafc;
        border: none;
        font-size: 1.125rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .qty-btn:hover {
        background: #e2e8f0;
    }

    .qty-input {
        width: 60px;
        height: 36px;
        text-align: center;
        border: none;
        border-left: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        font-size: 1rem;
    }

    .qty-input:focus {
        outline: none;
    }

    .modal-total {
        background: #f8fafc;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .btn-confirm {
        width: 100%;
        background: #0f172a;
        color: white;
        border: none;
        padding: 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-confirm:hover {
        background: #1e293b;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }

    .pagination {
        display: flex;
        gap: 0.25rem;
        list-style: none;
        flex-wrap: wrap;
    }

    .page-item .page-link {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        color: #475569;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s;
        background: white;
    }

    .page-item .page-link:hover {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #2563eb;
    }

    .page-item.active .page-link {
        background: #0f172a;
        border-color: #0f172a;
        color: white;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: #0f172a;
        color: white;
        padding: 0.75rem 1.25rem;
        border-radius: 0.5rem;
        z-index: 1100;
        animation: slideInRight 0.3s ease;
        display: none;
        align-items: center;
        gap: 0.5rem;
    }

    .toast.success {
        background: #10b981;
    }

    .toast.error {
        background: #ef4444;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .catalog-container {
            padding: 1rem;
        }
        .items-grid {
            gap: 1rem;
        }
    }
</style>

<div class="catalog-container">
    <div class="catalog-header">
        <h1>Katalog Item</h1>
        <p>Pilih perlengkapan untuk acara spesial Anda</p>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-group">
            <select id="typeFilter" class="filter-select">
                <option value="">Semua Jenis</option>
                @foreach($itemTypes ?? [] as $type)
                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari item..." value="{{ request('search') }}">
            <button onclick="applyFilters()" class="btn-filter">Filter</button>
        </div>
    </div>

    <!-- Items Grid -->
    @if($items->count() > 0)
    <div class="items-grid" id="itemsGrid">
        @foreach($items as $item)
        <div class="product-card" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}" data-stock="{{ $item->stock }}" data-image="{{ $item->image }}" data-category="{{ $item->type->name ?? 'Umum' }}">
            <div class="product-badge">
                @if($item->stock <= 5 && $item->stock > 0)
                    <span class="badge-stock badge-stock-low">Stok Terbatas!</span>
                @elseif($item->stock > 0)
                    <span class="badge-stock">Tersedia</span>
                @endif
            </div>
            @if($item->image && file_exists(storage_path('app/public/'.$item->image)))
                <img src="{{ asset('storage/'.$item->image) }}" class="product-image" alt="{{ $item->name }}">
            @else
                <div class="product-image" style="display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #94a3b8;">
                    📷 No Image
                </div>
            @endif
            <div class="product-info">
                <div class="product-category">{{ $item->type->name ?? 'Umum' }}</div>
                <div class="product-name">{{ $item->name }}</div>
                <div class="product-price">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                <div class="product-stock">
                    @if($item->stock > 0)
                        <span class="stock-available">✓ Stok: {{ $item->stock }}</span>
                    @else
                        <span class="stock-out">✗ Stok Habis</span>
                    @endif
                </div>
                <button class="btn-add-cart" onclick="openModal({{ $item->id }})" {{ $item->stock <= 0 ? 'disabled' : '' }}>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"/>
                        <circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    Tambah ke Keranjang
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        {{ $items->appends(request()->query())->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 4rem; background: white; border-radius: 1rem;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
        <p style="color: #64748b;">Belum ada item tersedia</p>
    </div>
    @endif
</div>

<!-- Modal -->
<div id="itemModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Pilih Jumlah</h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <img id="modalImage" class="modal-item-image" src="" alt="">
            <div id="modalItemName" class="modal-item-name"></div>
            <div id="modalItemPrice" class="modal-item-price"></div>
            <div id="modalItemStock" class="modal-item-stock"></div>
            
            <div class="qty-control">
                <label>Jumlah</label>
                <div class="qty-input-wrapper">
                    <button class="qty-btn" onclick="decrementQty()">-</button>
                    <input type="number" id="modalQty" class="qty-input" value="1" min="1" readonly>
                    <button class="qty-btn" onclick="incrementQty()">+</button>
                </div>
            </div>
            
            <div class="modal-total">
                <span>Total</span>
                <strong id="modalTotal">Rp 0</strong>
            </div>
            
            <button class="btn-confirm" onclick="confirmAddToCart()">Tambah ke Keranjang</button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="toast"></div>

<script>
    let currentItem = null;
    let currentQty = 1;

    function openModal(itemId) {
        const card = document.querySelector(`.product-card[data-id="${itemId}"]`);
        if (!card) return;
        
        const name = card.dataset.name;
        const price = parseInt(card.dataset.price);
        const stock = parseInt(card.dataset.stock);
        const image = card.dataset.image;
        const category = card.dataset.category;
        
        currentItem = { id: itemId, name, price, stock, image, category };
        currentQty = 1;
        
        document.getElementById('modalItemName').textContent = name;
        document.getElementById('modalItemPrice').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(price)}`;
        document.getElementById('modalItemStock').innerHTML = stock > 0 ? 
            `<span style="color: #10b981;">✓ Stok tersedia: ${stock}</span>` : 
            `<span style="color: #ef4444;">✗ Stok habis</span>`;
        
        const modalImage = document.getElementById('modalImage');
        if (image && image !== 'null') {
            modalImage.src = `/storage/${image}`;
            modalImage.style.display = 'block';
        } else {
            modalImage.style.display = 'none';
        }
        
        document.getElementById('modalQty').value = 1;
        updateModalTotal();
        
        document.getElementById('itemModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('itemModal').style.display = 'none';
        currentItem = null;
    }

    function incrementQty() {
        if (!currentItem) return;
        if (currentQty < currentItem.stock) {
            currentQty++;
            document.getElementById('modalQty').value = currentQty;
            updateModalTotal();
        } else {
            showToast('Stok maksimal ' + currentItem.stock, 'error');
        }
    }

    function decrementQty() {
        if (currentQty > 1) {
            currentQty--;
            document.getElementById('modalQty').value = currentQty;
            updateModalTotal();
        }
    }

    function updateModalTotal() {
        if (!currentItem) return;
        const total = currentItem.price * currentQty;
        document.getElementById('modalTotal').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `toast ${type}`;
        toast.style.display = 'flex';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }

    async function confirmAddToCart() {
        if (!currentItem) return;
        
        const itemId = currentItem.id;
        const qty = currentQty;
        
        try {
            const response = await fetch('{{ route("customer.cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ item_id: itemId, qty: qty })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showToast(data.message, 'success');
                closeModal();
                
                const countResponse = await fetch('{{ route("customer.cart.count") }}');
                const countData = await countResponse.json();
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) cartCount.textContent = countData.count;
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Gagal menambahkan ke keranjang', 'error');
        }
    }

    function applyFilters() {
        const type = document.getElementById('typeFilter').value;
        const search = document.getElementById('searchInput').value;
        window.location.href = `{{ route("customer.catalog") }}?type=${type}&search=${search}`;
    }

    window.onclick = function(event) {
        const modal = document.getElementById('itemModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endsection