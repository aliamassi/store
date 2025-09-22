<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::get('/', fn () => redirect()->route('menu.index'));
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

Route::post('/cart/add', [MenuController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [MenuController::class, 'remove'])->name('cart.remove');       // remove 1 qty
Route::post('/cart/remove-line', [MenuController::class, 'removeLine'])->name('cart.removeLine'); // remove entire line
Route::post('/cart/clear', [MenuController::class, 'clear'])->name('cart.clear');
Route::post('/order', [MenuController::class, 'order'])->name('order');
