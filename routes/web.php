<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/customers', [CustomerController::class, 'customer']); // oneToOne
Route::get('/orders', [OrderController::class, 'orders']); // oneToMany
Route::get('/products', [ProductController::class, 'products']); // all products
Route::get('/products-with-categories', [ProductController::class, 'productsWithCategories']); // manyToMany
Route::get('/self-referential-products', [ProductController::class, 'selfReferentialProducts']); // self-referential
Route::get('/report-summary', [ReportController::class, 'summary']); // report summary
