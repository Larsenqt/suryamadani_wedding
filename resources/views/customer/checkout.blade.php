@extends('layouts.customer') 

@section('title', 'Checkout')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>🛒 Checkout</h1>
        </div>

        <div class="checkout-grid">
            <div class="cart-items">
                <h3 style="margin-bottom: 1rem;">Item yang Dipesan</h3>
                <div id="cartItemsContainer">
                    <!-- Cart items will be loaded here -->
                </div>
            </div>

            <div class="order-summary">
                <div class="summary-title">Ringkasan Pesanan</div>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal">Rp 0</span>
                </div>
                <div class="summary-row summary-total">
                    <strong>Total</strong>
                    <strong id="total">Rp 0</strong>
                </div>

                <div class="form-group">
                    <label>Tanggal Sewa <span class="required">*</span></label>
                    <input type="date" id="orderDate" min="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap <span class="required">*</span></label>
                    <textarea id="address" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos"></textarea>
                    <div id="addressError" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label>Nomor Telepon <span class="required">*</span></label>
                    <input type="tel" id="phone" placeholder="081234567890">
                    <div id="phoneError" class="error-message"></div>
                </div>

                <div class="form-group">
                    <label>Catatan (Opsional)</label>
                    <textarea id="notes" placeholder="Catatan tambahan untuk pesanan..."></textarea>
                </div>

                <button class="btn-order" id="orderBtn" onclick="placeOrder()">Pesan Sekarang</button>
            </div>
        </div>
    </div>
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

        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
        }

        .btn-back {
            background: #f8fafc;
            color: #475569;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            font-size: 1.875rem;
            font-weight: 600;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        .cart-items {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #e9eef3;
        }

        .cart-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .cart-item-image {
            width: 80px;
            height: 80px;
            border-radius: 0.5rem;
            object-fit: cover;
            background: #f1f5f9;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .cart-item-price {
            color: #2563eb;
            font-weight: 600;
        }

        .cart-item-qty {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .qty-btn {
            background: #f1f5f9;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 0.25rem;
            cursor: pointer;
            font-weight: bold;
        }

        .remove-item {
            color: #ef4444;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.75rem;
            margin-left: 1rem;
        }

        .order-summary {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #e9eef3;
            height: fit-content;
            position: sticky;
            top: 80px;
        }

        .summary-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
        }

        .summary-total {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2563eb;
            border-top: 2px solid #e2e8f0;
            margin-top: 0.5rem;
            padding-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: #334155;
        }

        label .required {
            color: #ef4444;
            margin-left: 0.25rem;
        }

        input, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            font-family: inherit;
            font-size: 0.875rem;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .btn-order {
            width: 100%;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            border: none;
            padding: 0.875rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn-order:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-order:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .empty-cart {
            text-align: center;
            padding: 3rem;
            color: #94a3b8;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            .checkout-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <script>
        let cartItems = [];

        async function loadCart() {
            try {
                const response = await fetch('{{ route("customer.cart.index") }}');
                const data = await response.json();
                
                if (data.success) {
                    cartItems = data.items;
                    renderCart();
                    updateSummary();
                    // Tambahkan update cart count di navbar
                    updateNavbarCartCount();
                }
            } catch (error) {
                console.error('Failed to load cart:', error);
            }
        }

        function renderCart() {
            const container = document.getElementById('cartItemsContainer');
            
            if (!cartItems || cartItems.length === 0) {
                container.innerHTML = `
                    <div class="empty-cart">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🛒</div>
                        <p>Keranjang belanja kosong</p>
                        <a href="{{ route('customer.catalog') }}" style="color: #2563eb; margin-top: 1rem; display: inline-block;">Mulai Belanja</a>
                    </div>
                `;
                document.getElementById('orderBtn').disabled = true;
                return;
            }

            document.getElementById('orderBtn').disabled = false;
            
            container.innerHTML = cartItems.map(item => `
                <div class="cart-item">
                    ${item.image ? 
                        `<img src="/storage/${item.image}" class="cart-item-image" alt="${item.name}">` :
                        `<div class="cart-item-image" style="display: flex; align-items: center; justify-content: center;">📷</div>`
                    }
                    <div class="cart-item-details">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</div>
                        <div class="cart-item-qty">
                            <button class="qty-btn" onclick="updateQty(${item.id}, ${item.qty - 1})">-</button>
                            <span>${item.qty}</span>
                            <button class="qty-btn" onclick="updateQty(${item.id}, ${item.qty + 1})">+</button>
                            <button class="remove-item" onclick="removeItem(${item.id})">Hapus</button>
                        </div>
                    </div>
                    <div style="font-weight: 600;">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</div>
                </div>
            `).join('');
        }

        function updateSummary() {
            const total = cartItems.reduce((sum, item) => sum + item.subtotal, 0);
            document.getElementById('subtotal').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
            document.getElementById('total').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(total)}`;
        }

        // Fungsi untuk update cart count di navbar
        async function updateNavbarCartCount() {
            try {
                const response = await fetch('{{ route("customer.cart.count") }}');
                const data = await response.json();
                const cartCountElements = document.querySelectorAll('.cart-count');
                cartCountElements.forEach(el => {
                    if (el) el.textContent = data.count || 0;
                });
            } catch (error) {
                console.error('Failed to update cart count:', error);
            }
        }

        async function updateQty(itemId, newQty) {
            if (newQty < 1) return;
            
            const item = cartItems.find(i => i.id === itemId);
            if (newQty > item.stock) {
                alert(`Stok tidak mencukupi! Tersisa ${item.stock}`);
                return;
            }

            try {
                const response = await fetch('{{ route("customer.cart.update") }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ item_id: itemId, qty: newQty })
                });

                const data = await response.json();
                if (data.success) {
                    await loadCart(); // Load ulang cart
                    await updateNavbarCartCount(); // Update navbar count
                }
            } catch (error) {
                console.error('Failed to update quantity:', error);
            }
        }

        async function removeItem(itemId) {
            try {
                const response = await fetch('{{ route("customer.cart.remove") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ item_id: itemId })
                });

                const data = await response.json();
                if (data.success) {
                    await loadCart(); // Load ulang cart
                    await updateNavbarCartCount(); // Update navbar count
                }
            } catch (error) {
                console.error('Failed to remove item:', error);
            }
        }

        function validateForm() {
            let isValid = true;
            
            const orderDate = document.getElementById('orderDate').value;
            if (!orderDate) {
                alert('Silakan pilih tanggal sewa');
                isValid = false;
            }
            
            const address = document.getElementById('address').value;
            const addressError = document.getElementById('addressError');
            if (!address) {
                addressError.textContent = 'Alamat harus diisi';
                isValid = false;
            } else if (address.length < 10) {
                addressError.textContent = 'Alamat minimal 10 karakter';
                isValid = false;
            } else {
                addressError.textContent = '';
            }
            
            const phone = document.getElementById('phone').value;
            const phoneError = document.getElementById('phoneError');
            if (!phone) {
                phoneError.textContent = 'Nomor telepon harus diisi';
                isValid = false;
            } else if (phone.length < 10) {
                phoneError.textContent = 'Nomor telepon minimal 10 digit';
                isValid = false;
            } else if (!/^[0-9]+$/.test(phone)) {
                phoneError.textContent = 'Nomor telepon hanya boleh angka';
                isValid = false;
            } else {
                phoneError.textContent = '';
            }
            
            return isValid;
        }

        async function placeOrder() {
            if (!validateForm()) {
                return;
            }

            const orderDate = document.getElementById('orderDate').value;
            const address = document.getElementById('address').value;
            const phone = document.getElementById('phone').value;
            const notes = document.getElementById('notes').value;

            if (cartItems.length === 0) {
                alert('Keranjang belanja kosong');
                return;
            }

            const orderBtn = document.getElementById('orderBtn');
            orderBtn.disabled = true;
            orderBtn.textContent = 'Memproses...';

            try {
                const items = cartItems.map(item => ({
                    id: item.id,
                    qty: item.qty
                }));

                const response = await fetch('{{ route("customer.order.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order_date: orderDate,
                        address: address,
                        phone: phone,
                        notes: notes,
                        items: items
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Kosongkan cart setelah sukses
                    await updateNavbarCartCount();
                    window.location.href = `/customer/order/${data.order_uuid}`;
                } else {
                    if (data.errors) {
                        let errorMsg = '';
                        for (let key in data.errors) {
                            errorMsg += data.errors[key][0] + '\n';
                        }
                        alert(errorMsg);
                    } else {
                        alert(data.message || 'Gagal memesan');
                    }
                    orderBtn.disabled = false;
                    orderBtn.textContent = 'Pesan Sekarang';
                }
            } catch (error) {
                alert('Terjadi kesalahan. Silakan coba lagi.');
                orderBtn.disabled = false;
                orderBtn.textContent = 'Pesan Sekarang';
            }
        }

        loadCart();
    </script>
@endsection