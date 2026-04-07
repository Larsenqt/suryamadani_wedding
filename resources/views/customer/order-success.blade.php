@extends('layouts.customer') 

@section('title', 'Order Succes')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: 2rem;">
    <div class="success-card" style="background: white; border-radius: 1rem; padding: 2rem; text-align: center; border: 1px solid #e9eef3; box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08);">
        <div class="success-icon" style="width: 80px; height: 80px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 3rem;">
            ✅
        </div>
        <h1 style="color: #166534; margin-bottom: 0.5rem;">Pesanan Berhasil!</h1>
        <p style="color: #64748b;">Terima kasih telah memesan. Pesanan Anda akan segera diproses.</p>

        <div class="order-info" style="background: #f8fafc; border-radius: 0.75rem; padding: 1rem; margin: 1.5rem 0; text-align: left;">
            <div class="order-row" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #475569;">ID Pesanan</span>
                <strong style="color: #1e293b;">{{ $order->uuid }}</strong>
            </div>
            <div class="order-row" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #475569;">Tanggal Sewa</span>
                <strong style="color: #1e293b;">{{ date('d/m/Y', strtotime($order->order_date)) }}</strong>
            </div>
            <div class="order-row" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #475569;">Alamat Pengiriman</span>
                <strong style="color: #1e293b;">{{ $order->address ?? '-' }}</strong>
            </div>
            <div class="order-row" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #475569;">Nomor Telepon</span>
                <strong style="color: #1e293b;">{{ $order->phone ?? '-' }}</strong>
            </div>
            @if($order->notes)
            <div class="order-row" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #475569;">Catatan</span>
                <strong style="color: #1e293b;">{{ $order->notes }}</strong>
            </div>
            @endif
            <div class="order-row" style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #e2e8f0;">
                <span style="color: #475569;">Total Pembayaran</span>
                <strong style="color: #2563eb;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
            </div>
            <div class="order-row" style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                <span style="color: #475569;">Status</span>
                <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem;">Menunggu Konfirmasi</span>
            </div>
        </div>

        <div class="btn-group" style="display: flex; gap: 1rem; justify-content: center; margin-top: 1.5rem;">
            <a href="{{ route('customer.catalog') }}" class="btn-secondary" style="background: white; color: #475569; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 500; border: 1px solid #e2e8f0;">
                ← Lanjut Belanja
            </a>
            <a href="{{ route('customer.orders.history') }}" class="btn-primary" style="background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 500;">
                Lihat Riwayat Pesanan →
            </a>
        </div>
    </div>
</div>

<style>
    @media (max-width: 640px) {
        .container {
            padding: 1rem;
        }
        .btn-group {
            flex-direction: column;
        }
    }
</style>
@endsection