<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\WhatsAppService;

class CustomerOrderController extends Controller
{
    public function catalog(Request $request)
    {
        $query = Item::with('type')->where('stock', '>', 0);
        
        if ($request->has('type') && $request->type != '') {
            $query->where('item_type_id', $request->type);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $items = $query->latest()->paginate(12); // 12 item per halaman
        $itemTypes = \App\Models\ItemType::all();
        
        return view('customer.catalog', compact('items', 'itemTypes'));
    }
    
    public function itemDetail($id)
    {
        $item = Item::with('type')->findOrFail($id);
        return response()->json([
            'success' => true,
            'item' => $item
        ]);
    }
    
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('customer.catalog')->with('error', 'Keranjang belanja kosong!');
        }
        
        $cartItems = [];
        $total = 0;
        
        foreach ($cart as $id => $details) {
            $item = Item::find($id);
            if ($item) {
                $subtotal = $item->price * $details['qty'];
                $total += $subtotal;
                $cartItems[] = [
                    'id' => $id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'qty' => $details['qty'],
                    'subtotal' => $subtotal,
                    'stock' => $item->stock,
                    'image' => $item->image
                ];
            }
        }
        
        return view('customer.checkout', compact('cartItems', 'total'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_date' => 'required|date|after_or_equal:today',
            'address' => 'required|string|min:10|max:500',
            'phone' => 'required|string|min:10|max:15',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1'
        ], [
            'order_date.required' => 'Tanggal pemesanan harus diisi',
            'order_date.after_or_equal' => 'Tanggal pemesanan tidak boleh kurang dari hari ini',
            'address.required' => 'Alamat pengiriman harus diisi',
            'address.min' => 'Alamat minimal 10 karakter',
            'phone.required' => 'Nomor telepon harus diisi',
            'phone.min' => 'Nomor telepon minimal 10 digit',
            'items.required' => 'Minimal 1 item harus dipesan',
            'items.*.qty.min' => 'Jumlah item minimal 1'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        DB::beginTransaction();
        
        try {
            // Cek stok
            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['id']);
                if ($item->stock < $itemData['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$item->name} tidak mencukupi! (Tersedia: {$item->stock})"
                    ], 400);
                }
            }
            
            // Buat order dengan alamat dan nomor telepon
            $order = Order::create([
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => auth()->id(),
                'order_date' => $request->order_date,
                'total_price' => 0,
                'status' => 'pending',
                'address' => $request->address,
                'phone' => $request->phone,
                'notes' => $request->notes
            ]);
            
            $total = 0;
            
            // Buat order details
            foreach ($request->items as $itemData) {
                $item = Item::findOrFail($itemData['id']);
                $subtotal = $itemData['qty'] * $item->price;
                
                OrderDetail::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'qty' => $itemData['qty'],
                    'price' => $item->price,
                    'subtotal' => $subtotal
                ]);
                
                $total += $subtotal;
            }
            
            // Update total price
            $order->update([
                'total_price' => $total
            ]);
            
            DB::commit();
            
            // Clear cart session
            session()->forget('cart');
        
            // Kirim notifikasi WhatsApp
            $this->sendWhatsAppNotification($order);
            
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'order_uuid' => $order->uuid
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memesan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show($uuid)
    {
        $order = Order::with(['details.item', 'user'])
            ->where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        return view('customer.order-success', compact('order'));
    }
    
    public function history()
    {
        $orders = Order::with('details.item')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('customer.order-history', compact('orders'));
    }
    
    public function cancel($uuid)
    {
        $order = Order::where('uuid', $uuid)
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->firstOrFail();
            
        DB::beginTransaction();
        
        try {
            foreach ($order->details as $detail) {
                $item = $detail->item;
                $item->increment('stock', $detail->qty);
            }
            
            $order->update(['status' => 'cancelled']);
            
            DB::commit();
            
            return redirect()->route('customer.orders.history')
                ->with('success', 'Pesanan berhasil dibatalkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan pesanan');
        }
    }
    
    public function dashboard()
    {
        $totalOrders = Order::where('user_id', auth()->id())->count();
        $pendingOrders = Order::where('user_id', auth()->id())->where('status', 'pending')->count();
        $completedOrders = Order::where('user_id', auth()->id())->where('status', 'completed')->count();
        $recentOrders = Order::where('user_id', auth()->id())->latest()->take(5)->get();
        
        return view('customer.dashboard', compact('totalOrders', 'pendingOrders', 'completedOrders', 'recentOrders'));
    }
    
    private function sendWhatsAppNotification($order)
    {
        try {
            $order->load(['user', 'details.item']);
            
            $message = "🛍️ *PESANAN BARU!*\n\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "📋 *Detail Pesanan*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "🆔 Order ID: {$order->uuid}\n";
            $message .= "👤 Pelanggan: {$order->user->name}\n";
            $message .= "📞 No. HP: {$order->phone}\n";
            $message .= "📧 Email: {$order->user->email}\n";
            $message .= "📍 Alamat: {$order->address}\n";
            $message .= "📅 Tanggal Sewa: " . date('d/m/Y', strtotime($order->order_date)) . "\n";
            $message .= "⏰ Waktu Order: " . $order->created_at->format('d/m/Y H:i') . "\n\n";
            
            if ($order->notes) {
                $message .= "📝 Catatan: {$order->notes}\n\n";
            }
            
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "📦 *Item yang Dipesan*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            
            foreach ($order->details as $detail) {
                $message .= "• {$detail->item->name}\n";
                $message .= "  Jumlah: {$detail->qty} x Rp " . number_format($detail->price, 0, ',', '.') . "\n";
                $message .= "  Subtotal: Rp " . number_format($detail->subtotal, 0, ',', '.') . "\n\n";
            }
            
            $message .= "━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "💰 *TOTAL: Rp " . number_format($order->total_price, 0, ',', '.') . "*\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━\n\n";
            
            $message .= "🔗 *Link Admin:* " . route('admin.orders.show', $order->uuid) . "\n\n";
            $message .= "✅ Segera lakukan approve pesanan!";
            
            $adminPhone = env('ADMIN_PHONE_NUMBER', '628134648067');
            
            \Log::info('WhatsApp Notification: ' . $message);
            
        } catch (\Exception $e) {
            \Log::error('WhatsApp notification failed: ' . $e->getMessage());
        }
    }
}