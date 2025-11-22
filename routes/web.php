<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/customers', [TestController::class, 'customer']); // oneToOne
Route::get('/orders', [TestController::class, 'orders']); // oneToMany
