<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [ItemController::class, 'index'])->name('users.index');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');
Route::get('/items', [ItemController::class, 'index'])->name('items.index');


Route::middleware('auth')->group(function () {
    Route::post('/like/{item_id}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/unlike/{item_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/items/{item_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->name('purchase.index');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'changeAddress'])->name('shipping_address');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('shipping_address.update');

    Route::get('/mypage/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [UserController::class, 'update'])->name('profile.update');
    Route::get('/mypage', [UserController::class, 'mypage'])->name('profile.show');

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
});
