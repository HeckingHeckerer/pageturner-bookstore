<?php
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;


// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
// Book browsing (public)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
// Category browsing (public)
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class,
'show'])->name('categories.show');
// Authenticated routes
Route::middleware('auth')->group(function () {
// Profile routes (from Breeze)
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Routes that require email verification
Route::middleware('verified')->group(function () {
    // Review routes
    Route::post('/books/{book}/reviews', [ReviewController::class,
    'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class,
    'destroy'])->name('reviews.destroy');
    
    // Cart routes
    Route::post('/cart/add/{book}', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{book}', [OrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::patch('/cart/update/{book}', [OrderController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
    
    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::patch('/orders/{order}/shipping', [OrderController::class, 'updateShipping'])->name('orders.updateShipping');
    
    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/count', [NotificationController::class, 'getCount']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});
});
// Admin-only routes (Category & Book management)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
// Admin Dashboard
Route::get('/', function() {
    return view('admin.dashboard');
})->name('dashboard');
// Category management
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class,
'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit',
[CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [CategoryController::class,
'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class,
'destroy'])->name('categories.destroy');
// API routes for admin modals
Route::get('/categories/search', [CategoryController::class, 'search'])->name('categories.search');
// Book management
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::post('/books', [BookController::class, 'store'])->name('books.store');
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
// API routes for admin modals
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
// Orders management
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

// User management
Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});
require __DIR__.'/auth.php';
