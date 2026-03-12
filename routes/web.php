<?php

use App\Http\Controllers\SparepartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::resource('/sparepart', SparepartController::class);
