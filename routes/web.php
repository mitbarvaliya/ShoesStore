<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/shop', [App\Http\Controllers\HomeController::class, 'shop'])->name('shop');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [App\Http\Controllers\HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\AdminController::class, 'login'])->name('login.post');
    Route::post('logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('logout');
    
    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('shoes', App\Http\Controllers\ShoeController::class)->names('shoes');
        Route::get('orders', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('completed-orders', [App\Http\Controllers\AdminOrderController::class, 'completedOrders'])->name('orders.completed');
        Route::get('orders/{id}', [App\Http\Controllers\AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{id}/status', [App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
        Route::delete('/', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('index');
        Route::post('/add/{shoe}', [App\Http\Controllers\CartController::class, 'add'])->name('add');
        Route::post('/update/{cart}', [App\Http\Controllers\CartController::class, 'updateQuantity'])->name('update');
        Route::delete('/remove/{cart}', [App\Http\Controllers\CartController::class, 'remove'])->name('remove');
    });

    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/my-orders', [App\Http\Controllers\OrderController::class, 'myOrders'])->name('my-orders');
    Route::post('/order/cancel/{id}', [App\Http\Controllers\OrderController::class, 'cancelOrder'])->name('order.cancel');
});

require __DIR__.'/auth.php';
