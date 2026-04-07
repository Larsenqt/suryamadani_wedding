<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
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
        
        return response()->json([
            'success' => true,
            'items' => $cartItems,
            'total' => $total,
            'count' => count($cart)
        ]);
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1'
        ]);
        
        $item = Item::findOrFail($request->item_id);
        
        if ($item->stock < $request->qty) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak mencukupi! Tersisa {$item->stock}"
            ], 400);
        }
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->item_id])) {
            $newQty = $cart[$request->item_id]['qty'] + $request->qty;

            if ($item->stock < $newQty) {
                return response()->json([
                    'success' => false,
                    'message' => "Total stok tidak mencukupi! Tersisa {$item->stock}"
                ], 400);
            }
            
            $cart[$request->item_id]['qty'] = $newQty;
        } else {
            $cart[$request->item_id] = [
                'qty' => $request->qty
            ];
        }
        
        session()->put('cart', $cart);
        
        $totalItems = array_sum(array_column($cart, 'qty'));
        
        return response()->json([
            'success' => true,
            'message' => "{$item->name} berhasil ditambahkan ke keranjang",
            'cart_count' => $totalItems
        ]);
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'qty' => 'required|integer|min:1'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (!isset($cart[$request->item_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang'
            ], 404);
        }
        
        $item = Item::findOrFail($request->item_id);
        
        if ($item->stock < $request->qty) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak mencukupi! Tersisa {$item->stock}"
            ], 400);
        }
        
        $cart[$request->item_id]['qty'] = $request->qty;
        session()->put('cart', $cart);
        
        $total = 0;
        foreach ($cart as $id => $details) {
            $item = Item::find($id);
            if ($item) {
                $total += $item->price * $details['qty'];
            }
        }
        
        $totalItems = array_sum(array_column($cart, 'qty'));
        
        return response()->json([
            'success' => true,
            'message' => 'Jumlah item berhasil diupdate',
            'total' => $total,
            'cart_count' => $totalItems
        ]);
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->item_id])) {
            unset($cart[$request->item_id]);
            session()->put('cart', $cart);
        }
        
        $total = 0;
        foreach ($cart as $id => $details) {
            $item = Item::find($id);
            if ($item) {
                $total += $item->price * $details['qty'];
            }
        }
        
        $totalItems = array_sum(array_column($cart, 'qty'));
        
        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang',
            'total' => $total,
            'cart_count' => $totalItems
        ]);
    }
    
    public function clear()
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan'
        ]);
    }
    
    public function getCount()
    {
        $cart = session()->get('cart', []);
        $totalItems = array_sum(array_column($cart, 'qty'));
        
        return response()->json([
            'count' => $totalItems
        ]);
    }
}