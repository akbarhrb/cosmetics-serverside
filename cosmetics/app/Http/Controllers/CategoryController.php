<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class CategoryController
{
    public function index()
    {
        try {
            $categories = Category::all();
            return response()->json([
                'message' => 'This endpoint returns all categories',
                'data' => $categories
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "cat_name" => 'required|string|max:255',
            ]);

            $category = Category::create($validated);

            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error occurred',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $cat_id)
    {
        try {
            $validated = $request->validate([
                "cat_name" => 'required|string|max:255|unique:categories,cat_name,' . $cat_id,
            ]);

            $category = Category::findOrFail($cat_id);
            $category->update($validated);

            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error occurred',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($cat_id)
    {
        try {
            $category = Category::findOrFail($cat_id);
            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the category',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
