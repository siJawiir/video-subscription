<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\VideoAccessController;
use App\Http\Controllers\Api\WatchSessionController;
use App\Http\Controllers\Api\CustomerOrderController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\RedisTestController;
use App\Http\Controllers\Api\VideoCategoryController;
use App\Http\Controllers\Api\VideoTagController;


// ====== Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/videos', [VideoController::class, 'index']);
Route::get('/videos/{id}', [VideoController::class, 'show']);
Route::get('popular-videos', [OrderController::class, 'popularVideos']);
Route::get('/latest-videos', [VideoController::class, 'latest']);
Route::get('/video-resources', [VideoController::class, 'resources']);


Route::get('/video-categories', [VideoCategoryController::class, 'index']);
Route::get('/video-categories/{id}', [VideoCategoryController::class, 'show']);
Route::get('/popular-video-categories', [OrderController::class, 'popularVideoCategories']);
Route::get('/video-category-resources', [VideoCategoryController::class, 'resources']);

Route::get('/video-tags', [VideoTagController::class, 'index']);
Route::get('/video-tags/{id}', [VideoTagController::class, 'show']);
Route::get('/video-tag-resources', [VideoTagController::class, 'resources']);



// ====== Authenticated Routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'profile']);

    // Cart
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::put('/cart/update', [CartController::class, 'updateItem']);
    Route::post('/cart/remove', [CartController::class, 'removeItem']);
    Route::post('/cart/checkout', [CartController::class, 'checkout']);

    // Customer Orders
    Route::get('/my-orders', [CustomerOrderController::class, 'index']);
    Route::get('/my-orders/{id}', [CustomerOrderController::class, 'show']);

    Route::post('/orders', [OrderController::class, 'store']);

    // Video Access
    Route::get('/video-access', [VideoAccessController::class, 'index']);
    Route::get('/video-access/{id}', [VideoAccessController::class, 'show']);

    // Watch Session
    Route::post('/watch/start', [WatchSessionController::class, 'start']);
    Route::post('/watch/ping', [WatchSessionController::class, 'ping']);
    Route::post('/watch/end', [WatchSessionController::class, 'end']);
});

// ======== Admin Routes - requires auth and has abilities

// Dashboard Management
Route::middleware([
    'auth:sanctum',
    'abilities:dashboard'
])->group(function () {
    Route::post('/dashboard-stats', [DashboardController::class, 'stats']);
});


// Video Management
Route::middleware([
    'auth:sanctum',
    'abilities:manage-videos'
])->group(function () {
    Route::post('/videos', [VideoController::class, 'store']);
    Route::put('/videos/{id}', [VideoController::class, 'update']);
    Route::delete('/videos/{id}', [VideoController::class, 'destroy']);

    Route::post('/video-categories', [VideoCategoryController::class, 'store']);
    Route::put('/video-categories/{id}', [VideoCategoryController::class, 'update']);
    Route::delete('/video-categories/{id}', [VideoCategoryController::class, 'destroy']);

    Route::post('/video-tags', [VideoTagController::class, 'store']);
    Route::put('/video-tags/{id}', [VideoTagController::class, 'update']);
    Route::delete('/video-tags/{id}', [VideoTagController::class, 'destroy']);
});

// Order Management
Route::middleware([
    'auth:sanctum',
    'abilities:manage-orders'
])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);

    Route::post('/orders/{id}/approve', [OrderController::class, 'approve']);
    Route::post('/orders/{id}/reject', [OrderController::class, 'reject']);
});

// Video Access Management
Route::middleware([
    'auth:sanctum',
    'abilities:manage-video-access'
])->group(function () {
    Route::post('/video-access/{id}/block', [VideoAccessController::class, 'block']);
});

// Test Redis Connection
Route::get('/redis-test', [RedisTestController::class, 'test']);

//Debug route to check current user and token
Route::middleware('auth:sanctum')->get('/debug-token', function () {
    return response()->json([
        'user' => request()->user(),
        'token' => request()->user()->currentAccessToken(),
    ]);
});
