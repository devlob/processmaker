<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('protected')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');

    Route::put('/products/{product}', [ProductController::class, 'update'])->name('api.products.update');
});
