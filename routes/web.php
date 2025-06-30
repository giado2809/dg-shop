<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;


// check admin-đăng nhập
    Route::middleware(['auth', 'admin'])->group(function () {
        // product controller
        Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product.index');
        Route::get('/admin/product/add', [ProductController::class, 'create'])->name('admin.product.add');
        Route::post('/admin/product/store', [ProductController::class, 'store'])->name('admin.product.store');
        Route::get('/admin/product/{id}/detail', [ProductController::class, 'show'])->name('admin.product.detail');    
        Route::get('/admin/product/{id}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
        Route::put('/admin/product/{id}/update', [ProductController::class, 'update'])->name('admin.product.update');
        Route::delete('/admin/product/{id}/delete', [ProductController::class, 'destroy'])->name('admin.product.destroy');
        Route::delete('/admin/product/bulk-delete', [ProductController::class, 'bulkDelete'])->name('admin.product.bulkDelete');

        // category controller
        Route::get('/admin/category', [CategoryController::class, 'index'])->name('admin.category.index');
        Route::get('/admin/category/add', [CategoryController::class, 'create'])->name('admin.category.add');
        Route::post('/admin/category/store', [CategoryController::class, 'store'])->name('admin.category.store');
        Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::put('/admin/category/{id}/update', [CategoryController::class, 'update'])->name('admin.category.update');
        Route::delete('/admin/category/{id}/delete', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
        Route::delete('/admin/category/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('admin.category.bulkDelete');

        // user controller
        Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user.index');
        Route::get('/admin/user/add', [UserController::class, 'create'])->name('admin.user.add');
        Route::post('/admin/user/store', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('/admin/user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::put('/admin/user/{id}/update', [UserController::class, 'update'])->name('admin.user.update');

        // order controller
        Route::get('/admin/order', [OrderController::class, 'index'])->name('admin.order.index');
        Route::get('/admin/order/{id}', [OrderController::class, 'show'])->name('admin.order.show');
        Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.order.updateStatus');

        // review controller
        Route::get('/admin/review', [ReviewController::class, 'index'])->name('admin.review.index');
        Route::delete('/admin/review/{id}/delete', [ReviewController::class, 'destroy'])->name('admin.review.destroy');

        // dashboard controller
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // voucher controller
        Route::get('/admin/voucher', [VoucherController::class, 'index'])->name('admin.voucher.index');
        Route::get('/admin/voucher/create', [VoucherController::class, 'create'])->name('admin.voucher.create');
        Route::post('/admin/voucher/store', [VoucherController::class, 'store'])->name('admin.voucher.store');
        Route::get('/admin/voucher/{id}/edit', [VoucherController::class, 'edit'])->name('admin.voucher.edit');
        Route::post('/admin/voucher/{id}/update', [VoucherController::class, 'update'])->name('admin.voucher.update');
        Route::delete('/admin/voucher/{id}/delete', [VoucherController::class, 'destroy'])->name('admin.voucher.destroy');
        Route::delete('/admin/voucher/bulk-delete', [VoucherController::class, 'bulkDelete'])->name('admin.voucher.bulkDelete');
    }); 
    //  route check áp dụng mã
        Route::post('/check-voucher', [VoucherController::class, 'check'])->name('voucher.check');

// ROUTE SHOP TĨNH - KHÔNG CẦN ĐĂNG NHẬP
Route::view('/about', 'shops.about')->name('about');
Route::view('/blog', 'shops.blog')->name('blog');
Route::view('/contact', 'shops.contact')->name('contact');

// ROUTE SHOP CHÍNH
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
Route::get('/detail/{id}', [HomeController::class, 'detail'])->name('detail');

// ROUTE AUTH KHÁCH
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/forgot', [AuthController::class, 'showForgotForm'])->name('forgot');
Route::post('/forgot', [AuthController::class, 'sendResetLink'])->name('forgot.send');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// ROUTE AUTH - ĐÃ LOGIN
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'formChangePassword'])->name('changePassword');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('changePassword.submit');
});


// ROUTE USER - ĐÃ LOGIN + KHÔNG BỊ KHÓA
Route::middleware(['auth', 'check.blocked'])->group(function () {

    // CART
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add-to-cart', [CartController::class, 'ajaxAddToCart'])->name('cart.add');
    Route::post('/cart/increase/{id}', [CartController::class, 'ajaxIncrease'])->name('cart.increase');
    Route::post('/cart/decrease/{id}', [CartController::class, 'ajaxDecrease'])->name('cart.decrease');
    Route::get('/cart/edit', [CartController::class, 'edit'])->name('cart.edit');
    Route::delete('/cart/{id}/delete', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart/delete-selected', [CartController::class, 'deleteSelected'])->name('cart.deleteSelected');

    // ORDER - CHECKOUT
    Route::post('/checkout', [OrderController::class, 'checkoutSelected'])->name('checkout');
    Route::post('/checkout/place-order', [OrderController::class, 'placeOrder'])->name('checkout.placeOrder');
    Route::get('/order', [OrderController::class, 'listOrder'])->name('order.index');
    Route::get('/order/{id}/detail', [OrderController::class, 'showOrder'])->name('order.detail');
    Route::put('/order/{id}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::get('/checkout/buy-one/{cartId}', [OrderController::class, 'checkoutOne'])->name('checkout.buyOne');
    Route::post('/checkout/place-order-one/{cartId}', [OrderController::class, 'placeOrderOne'])->name('checkout.placeOrderOne');

    // ĐÁNH GIÁ
    Route::get('/order/{id}/review', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/order/{id}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/order/{id}/review/show', [ReviewController::class, 'show'])->name('review.show');

    // USER PROFILE
    Route::get('/user/profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit');
    Route::put('/user/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
});





