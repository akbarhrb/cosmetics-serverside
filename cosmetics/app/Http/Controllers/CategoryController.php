<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController
{
    public function store(Request $request){
        $validated = $request->validate([
            "cat_name" => 'required|string|max:255',
        ]);
        try{
            $category = Category::create([
                'cat_name' => $validated['cat_name']
            ]);
            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category,
            ], 201);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
