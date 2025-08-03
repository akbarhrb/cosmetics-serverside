<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//check health
Route::get('/health', function () {
    return response()->json(['message' => 'API is working']);
});
