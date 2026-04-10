<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ItemTypeController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\WelcomeController;


Route::get('/', [WelcomeController::class, 'index']);
Route::get('/testimonials/paginate', [WelcomeController::class, 'getTestimonials'])->name('testimonials.paginate');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('item-types', ItemTypeController::class)->except(['show']);
    Route::resource('items', ItemController::class)->except(['show']);
    Route::resource('testimonials', TestimonialController::class);
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::delete('/orders/{uuid}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::delete('/orders/bulk-destroy', [OrderController::class, 'bulkDestroy'])->name('orders.bulk-destroy');
    Route::get('/orders/{uuid}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{uuid}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{uuid}', [OrderController::class, 'update'])->name('orders.update');
    Route::post('/orders/{uuid}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    Route::get('/orders/{uuid}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');
    Route::get('/orders/{uuid}/invoice/view', [OrderController::class, 'viewInvoice'])->name('orders.invoice.view');
    Route::post('/orders/{uuid}/reject', [OrderController::class, 'reject'])->name('orders.reject');
    Route::post('/orders/{uuid}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::get('/orders/export/excel', [OrderController::class, 'exportExcel'])->name('orders.export.excel');
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
    Route::post('/invoices/{invoice}/approve', [\App\Http\Controllers\Admin\InvoiceController::class, 'approve'])->name('invoices.approve');
    Route::post('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\Admin\InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
    Route::post('/invoices/{invoice}/cancel', [\App\Http\Controllers\Admin\InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::get('/invoices/{invoice}/pdf', [\App\Http\Controllers\Admin\InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    Route::get('/invoices/{invoice}/preview', [\App\Http\Controllers\Admin\InvoiceController::class, 'previewPdf'])->name('invoices.preview-pdf');
    Route::get('/invoices/export/excel', [InvoiceController::class, 'exportExcel'])->name('invoices.export.excel');
    Route::post('/invoices/{invoice}/settle', [InvoiceController::class, 'settle'])->name('invoices.settle');
});

// Customer Routes (Role: customer)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerOrderController::class, 'dashboard'])->name('dashboard');
    Route::get('/catalog', [CustomerOrderController::class, 'catalog'])->name('catalog');
    Route::get('/item/{id}', [CustomerOrderController::class, 'itemDetail'])->name('item.detail');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count'); 
    Route::get('/checkout', [CustomerOrderController::class, 'checkout'])->name('checkout');
    Route::post('/order', [CustomerOrderController::class, 'store'])->name('order.store');
    Route::get('/order/{uuid}', [CustomerOrderController::class, 'show'])->name('order.show');       
    Route::get('/orders', [CustomerOrderController::class, 'history'])->name('orders.history');
    Route::post('/order/{uuid}/cancel', [CustomerOrderController::class, 'cancel'])->name('order.cancel');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('customer.catalog');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';