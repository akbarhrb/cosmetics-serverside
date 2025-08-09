<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PharmacyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//check health
Route::get('/health', function () {
    return response()->json(['message' => 'API is working']);
});

//pharmacies
// index - store - update - delete
Route::get('/pharmacies' , [PharmacyController::class , 'index']);


//categories
Route::get('/categories' , [CategoryController::class , 'index']);
Route::post('/add-category' , [CategoryController::class , 'store']);

//items
// index - add - update - delete - resolve missing items

//receipts
//index - store - update status - update - delete

//receipt-items
// index - store - update - delete
