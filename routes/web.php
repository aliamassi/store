<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::get('/', fn () => redirect()->route('menu.index'));
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

Route::post('/cart/add', [MenuController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [MenuController::class, 'remove'])->name('cart.remove');
Route::post('/cart/remove-line', [MenuController::class, 'removeLine'])->name('cart.removeLine');
Route::post('/cart/clear', [MenuController::class, 'clear'])->name('cart.clear');

// WhatsApp sharing routes
Route::get('/share-whatsapp', [MenuController::class, 'shareWithProductImages'])->name('whatsapp.share');
Route::get('/share-whatsapp-all', [MenuController::class, 'shareWithAllImages'])->name('whatsapp.share.all');
