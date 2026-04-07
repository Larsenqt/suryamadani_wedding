@extends('layouts.admin-sidebar')

@section('title', 'Edit Pesanan')

@section('content')
<div class="admin-container" style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
        <a href="{{ route('admin.orders.show', $order->uuid) }}" class="btn-back" style="display: inline-flex; align-items: center; gap: 0.5rem; background: white; color: #475569; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.875rem; border: 1px solid #e2e8f0;">
            ← Kembali
        </a>
    </div>

    <div class="card" style="background: white; border-radius: 1.5rem; box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08); overflow: hidden; border: 1px solid #e9eef3;">
        <div class="card-header" style="padding: 1.5rem 2rem; border-bottom: 1px solid #f0f2f5; background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);">
            <h2 style="font-size: 1.5rem; font-weight: 600; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">
                ✏️ Edit Pesanan
            </h2>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.25rem;">ID Pesanan: #{{ $order->uuid }}</p>
        </div>
        <div class="card-body" style="padding: 2rem;">
            <form method="POST" action="{{ route('admin.orders.update', $order->uuid) }}">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155;">Tanggal Pemesanan</label>
                    <input type="date" name="order_date" value="{{ old('order_date', $order->order_date) }}" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-family: inherit;">
                    @error('order_date')
                        <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155;">Status Pesanan</label>
                    <select name="status" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-family: inherit;">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    @error('status')
                        <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #334155;">Catatan Pesanan</label>
                    <textarea name="notes" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-family: inherit; resize: vertical;" placeholder="Tambahkan catatan untuk pesanan ini...">{{ old('notes', $order->notes) }}</textarea>
                    @error('notes')
                        <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;">{{ $message }}</p>
                    @enderror
                    <p style="font-size: 0.7rem; color: #64748b; margin-top: 0.25rem;">Catatan akan ditampilkan di halaman detail pesanan</p>
                </div>

                <div style="background: #f8fafc; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1.5rem;">
                    <h4 style="margin-bottom: 0.5rem; color: #1e293b;">📦 Ringkasan Item</h4>
                    @foreach($order->details as $detail)
                    <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0;">
                        <span>{{ $detail->item->name }} (x{{ $detail->qty }})</span>
                        <span style="font-weight: 600;">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                    <div style="display: flex; justify-content: space-between; padding: 0.75rem 0; margin-top: 0.5rem; border-top: 2px solid #e2e8f0;">
                        <strong>Total</strong>
                        <strong style="color: #2563eb;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                    </div>
                    <p style="font-size: 0.7rem; color: #64748b; margin-top: 0.5rem;">* Item tidak dapat diubah, hanya status dan catatan yang dapat diedit</p>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <a href="{{ route('admin.orders.show', $order->uuid) }}" style="flex: 1; background: white; color: #475569; padding: 0.75rem; border-radius: 0.5rem; text-align: center; text-decoration: none; border: 2px solid #e2e8f0;">
                        Batal
                    </a>
                    <button type="submit" style="flex: 1; background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 0.75rem; border-radius: 0.5rem; border: none; cursor: pointer; font-weight: 600;">
                        Simpan Perubahan
                    </button>
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
    
    @media (max-width: 640px) {
        .admin-container {
            padding: 1rem;
        }
    }
</style>
@endsection