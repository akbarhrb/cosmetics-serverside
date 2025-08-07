<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//check health
Route::get('/health', function () {
    return response()->json(['message' => 'API is working']);
});

//categories
Route::post('/add-category' , [CategoryController::class , 'store'])->name('categories.add');
