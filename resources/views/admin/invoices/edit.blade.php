@extends('layouts.admin-sidebar')

@section('title', 'Kelola Pesanan')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="p-2 hover:bg-gray-200 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Invoice</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $invoice->invoice_number }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PUT')
                
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-900 to-black border-b border-gray-800">
                        <h2 class="text-lg font-semibold text-white">Invoice Information</h2>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Invoice Number & Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Invoice Number</label>
                                <input type="text" value="{{ $invoice->invoice_number }}" readonly
                                       class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg font-mono">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Order Date</label>
                                <input type="date" name="order_date" value="{{ $invoice->order_date->format('Y-m-d') }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date (Tanggal Acara)</label>
                                <input type="date" name="event_date" value="{{ $invoice->event_date ? $invoice->event_date->format('Y-m-d') : '' }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                <p class="text-xs text-gray-500 mt-1">Tanggal pelaksanaan acara/pemesanan</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Invoice</label>
                                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                    <option value="draft" {{ $invoice->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="approved" {{ $invoice->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="cancelled" {{ $invoice->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date</label>
                                <input type="date" name="due_date" value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="border-t pt-6">
                            <h3 class="text-md font-semibold text-gray-800 mb-4">Customer Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Customer Name *</label>
                                    <input type="text" name="customer_name" value="{{ $invoice->customer_name }}" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" name="customer_email" value="{{ $invoice->customer_email }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone</label>
                                    <input type="text" name="customer_phone" value="{{ $invoice->customer_phone }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                                    <textarea name="customer_address" rows="2"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">{{ $invoice->customer_address }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="border-t pt-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-md font-semibold text-gray-800">Invoice Items</h3>
                                <button type="button" onclick="addItem()"
                                        class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">
                                    + Add Item
                                </button>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full" id="itemsTable">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Item Name</th>
                                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Description</th>
                                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600 w-20">Qty</th>
                                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 w-32">Unit Price</th>
                                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 w-32">Subtotal</th>
                                            <th class="px-3 py-2 text-center w-10"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsBody">
                                        @foreach($invoice->items as $index => $item)
                                        <tr class="item-row">
                                            <td class="px-3 py-2"><input type="text" name="items[{{ $index }}][item_name]" value="{{ $item->item_name }}" required class="w-full px-2 py-1 border rounded"></td>
                                            <td class="px-3 py-2"><input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" class="w-full px-2 py-1 border rounded"></td>
                                            <td class="px-3 py-2"><input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" step="1" min="1" required class="w-full px-2 py-1 border rounded text-center calculate"></td>
                                            <td class="px-3 py-2"><input type="number" name="items[{{ $index }}][unit_price]" value="{{ $item->unit_price }}" step="1000" min="0" required class="w-full px-2 py-1 border rounded text-right calculate"></td>
                                            <td class="px-3 py-2"><span class="subtotal-display block text-right font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span><input type="hidden" name="items[{{ $index }}][subtotal]" class="subtotal-input" value="{{ $item->subtotal }}"></td>
                                            <td class="px-3 py-2 text-center"><button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">✕</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50">
                                            <td colspan="4" class="px-3 py-2 text-right font-semibold">Subtotal</td>
                                            <td class="px-3 py-2 text-right font-bold" id="subtotalDisplay">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="px-3 py-2 text-right">Tax (%)</td>
                                            <td class="px-3 py-2"><input type="number" name="tax" id="tax" value="{{ $invoice->tax }}" step="1" class="w-full px-2 py-1 border rounded text-right"></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="px-3 py-2 text-right">Discount</td>
                                            <td class="px-3 py-2"><input type="number" name="discount" id="discount" value="{{ $invoice->discount }}" step="1000" class="w-full px-2 py-1 border rounded text-right"></td>
                                            <td></td>
                                        </tr>
                                        <tr class="border-t-2">
                                            <td colspan="4" class="px-3 py-3 text-right text-lg font-bold">TOTAL</td>
                                            <td class="px-3 py-3 text-right text-xl font-bold text-red-600" id="totalDisplay">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- DP Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-md font-semibold text-gray-800 mb-4">Down Payment (DP)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah DP (Rp)</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                        <input type="text" name="dp_amount" id="dp_amount" value="{{ number_format($invoice->dp_amount, 0, ',', '.') }}" 
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-right"
                                               onkeyup="formatPrice(this)" onblur="formatPriceOnBlur(this)">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Jatuh Tempo DP</label>
                                    <input type="date" name="dp_due_date" value="{{ $invoice->dp_due_date ? $invoice->dp_due_date->format('Y-m-d') : '' }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                </div>
                            </div>
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Tagihan:</span>
                                    <span class="font-semibold" id="totalDisplayPreview">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1">
                                    <span class="text-gray-600">DP Dibayarkan:</span>
                                    <span class="font-semibold text-blue-600" id="dpPreview">Rp {{ number_format($invoice->dp_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm mt-1 pt-1 border-t border-blue-200">
                                    <span class="font-semibold">Sisa Pembayaran:</span>
                                    <span class="font-semibold text-red-600" id="remainingPreview">Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="border-t pt-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Notes / Terms</label>
                            <textarea name="notes" rows="3" 
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">{{ $invoice->notes }}</textarea>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800">
                            Update Invoice
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
    let itemIndex = 1;

    function formatPrice(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value === '') {
            input.value = '';
            return;
        }
        let number = parseInt(value, 10);
        input.value = number.toLocaleString('id-ID');
    }

    function formatPriceOnBlur(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value === '') {
            input.value = '';
            return;
        }
        let number = parseInt(value, 10);
        input.value = number.toLocaleString('id-ID');
    }

    // Fungsi untuk mendapatkan nilai bersih dari input yang diformat
    function getRawValue(formattedValue) {
        return formattedValue.replace(/[^\d]/g, '');
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach((row, idx) => {
            const qty = parseFloat(row.querySelector('input[name*="[quantity]"]')?.value) || 0;
            const price = parseFloat(row.querySelector('input[name*="[unit_price]"]')?.value) || 0;
            const rowSubtotal = qty * price;
            subtotal += rowSubtotal;
            
            row.querySelector('.subtotal-display').innerText = 'Rp ' + rowSubtotal.toLocaleString('id-ID');
            row.querySelector('.subtotal-input').value = rowSubtotal;
        });
        
        document.getElementById('subtotalDisplay').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        
        const taxPercent = parseFloat(document.getElementById('tax')?.value) || 0;
        const discount = parseFloat(document.getElementById('discount')?.value) || 0;
        const taxAmount = subtotal * taxPercent / 100;
        const total = subtotal + taxAmount - discount;
        
        document.getElementById('totalDisplay').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
        
        const totalPreview = document.getElementById('totalDisplayPreview');
        if (totalPreview) {
            totalPreview.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }
        
        calculateRemaining();
    }

    function calculateRemaining() {
        const totalDisplay = document.getElementById('totalDisplay');
        const dpInput = document.getElementById('dp_amount');
        const dpPreview = document.getElementById('dpPreview');
        const remainingPreview = document.getElementById('remainingPreview');
        
        let total = 0;
        if (totalDisplay) {
            total = parseFloat(totalDisplay.innerText.replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;
        }
        
        let dp = 0;
        if (dpInput) {
            dp = parseFloat(dpInput.value.replace(/[^\d]/g, '')) || 0;
        }
        
        const remaining = total - dp;
        
        if (dpPreview) dpPreview.innerText = 'Rp ' + dp.toLocaleString('id-ID');
        if (remainingPreview) remainingPreview.innerText = 'Rp ' + remaining.toLocaleString('id-ID');
    }

    // Sebelum submit form, konversi nilai DP ke angka tanpa format
    document.getElementById('invoiceForm').addEventListener('submit', function(e) {
        const dpInput = document.getElementById('dp_amount');
        if (dpInput) {
            // Ambil nilai bersih (hilangkan titik)
            const rawValue = dpInput.value.replace(/[^\d]/g, '');
            // Buat hidden input untuk mengirim nilai asli
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'dp_amount';
            hiddenInput.value = rawValue;
            // Rename input asli agar tidak terkirim
            dpInput.name = 'dp_amount_display';
            this.appendChild(hiddenInput);
        }
    });

    function addItem() {
        const tbody = document.getElementById('itemsBody');
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td class="px-3 py-2"><input type="text" name="items[${itemIndex}][item_name]" required class="w-full px-2 py-1 border rounded"></td>
            <td class="px-3 py-2"><input type="text" name="items[${itemIndex}][description]" class="w-full px-2 py-1 border rounded"></td>
            <td class="px-3 py-2"><input type="number" name="items[${itemIndex}][quantity]" value="1" step="1" min="1" required class="w-full px-2 py-1 border rounded text-center calculate" data-index="${itemIndex}"></td>
            <td class="px-3 py-2"><input type="number" name="items[${itemIndex}][unit_price]" value="0" step="1000" min="0" required class="w-full px-2 py-1 border rounded text-right calculate" data-index="${itemIndex}"></td>
            <td class="px-3 py-2"><span class="subtotal-display block text-right font-semibold">Rp 0</span><input type="hidden" name="items[${itemIndex}][subtotal]" class="subtotal-input" value="0"></td>
            <td class="px-3 py-2 text-center"><button type="button" onclick="removeItem(this)" class="text-red-500 hover:text-red-700">✕</button></td>
        `;
        tbody.appendChild(newRow);
        itemIndex++;
        
        attachEvents();
    }

    function removeItem(btn) {
        const row = btn.closest('.item-row');
        if (document.querySelectorAll('.item-row').length > 1) {
            row.remove();
            calculateTotals();
        } else {
            alert('Minimal harus ada 1 item');
        }
    }

    function attachEvents() {
        document.querySelectorAll('.calculate').forEach(el => {
            el.removeEventListener('input', calculateTotals);
            el.addEventListener('input', calculateTotals);
        });
    }

    document.getElementById('tax')?.addEventListener('input', calculateTotals);
    document.getElementById('discount')?.addEventListener('input', calculateTotals);
    document.getElementById('dp_amount')?.addEventListener('input', calculateRemaining);
    document.getElementById('dp_amount')?.addEventListener('blur', calculateRemaining);
    
    attachEvents();
    calculateTotals();
</script>
@endsection