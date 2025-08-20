<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\ReceiptItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//check health
Route::get('/health', function () {
    return response()->json(['message' => 'API is working']);
});

//pharmacies
Route::get('/pharmacies' , [PharmacyController::class, 'index']);
Route::post('/add-pharmacy' , [PharmacyController::class, 'store']);
Route::put('/update-pharmacy/{pharmacy_id}' , [PharmacyController::class, 'update']);
Route::delete('/delete-pharmacy/{pharmacy_id}' , [PharmacyController::class, 'destroy']);

//categories
Route::get('/categories' , [CategoryController::class, 'index']);
Route::post('/add-category' , [CategoryController::class, 'store']);
Route::post('/update-category/{cat_id}', [CategoryController::class, 'update']);
Route::delete('/delete-category/{cat_id}', [CategoryController::class, 'delete']);

//items
Route::get('/items' , [ItemController::class, 'index']);
Route::post('/add-item' , [ItemController::class, 'store']);
Route::put('/update-item/{item_id}' , [ItemController::class, 'update']);
Route::delete('/delete-item/{item_id}' , [ItemController::class, 'destroy']);

//receipts
//index - store - update status - update - delete
Route::get('/receipts' , [ReceiptController::class, 'index']);
Route::post('/receipt' , [ReceiptController::class, 'show']);
Route::get('/receipts/{status}' , [ReceiptController::class, 'receipts']);
Route::post('/add-receipt' , [ReceiptController::class, 'store']);
Route::patch('/update-r-status/{receipt_id}' , [ReceiptController::class, 'updateStatus']);
Route::put('/update-receipt/{receipt_id}' , [ReceiptController::class, 'update']);
Route::delete('/delete-receipt/{receipt_id}' , [ReceiptController::class, 'destroy']);

//receipt-items
Route::get('/receipt/{receipt_id}/items', [ReceiptItemController::class, 'index']);
Route::post('/add-receipt-items', [ReceiptItemController::class, 'store']);
Route::put('/receipt-item/{id}', [ReceiptItemController::class, 'update']);
Route::delete('/receipt-item/{id}', [ReceiptItemController::class, 'destroy']);
