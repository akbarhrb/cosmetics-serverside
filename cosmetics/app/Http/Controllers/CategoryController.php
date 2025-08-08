<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;

class CategoryController
{
    public function index(){
        $categories = Category::all();
        return response()->json([
            'message' => 'this endpoint returns all categories',
            "data" => $categories
        ]);
    }
    public function store(Request $request){
        $validated = $request->validate([
            "cat_name" => 'required|string|max:255',
        ]);
        
        $category = Category::create([
            'cat_name' => $validated['cat_name']
        ]);
        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }
}
