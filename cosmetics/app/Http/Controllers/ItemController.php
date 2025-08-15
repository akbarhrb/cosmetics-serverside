<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ItemController
{
    public function index()
    {
        $items = Item::with('category')->get();
        return response()->json([
            'message' => 'List of all items',
            'data' => $items
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'category_id'    => 'required|exists:categories,id',
                'item_name'      => 'required|string|max:255|unique:items,item_name',
                'item_color'     => 'nullable|string|max:100',
                'quantity'       => 'required|integer|min:0',
                'price_unit_ind' => 'required|integer|min:0',
                'price_dozen'    => 'required|integer|min:0',
                'price_unit_ph'  => 'required|integer|min:0',
                'cost'           => 'required|integer|min:0',
                'description'    => 'nullable|string',
            ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $item = Item::create($validator->validated());

            return response()->json([
                'message' => 'Item created successfully',
                'data' => $item
            ], 201);

        }catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        try {
            $validated = $request->validate([
                'category_id'    => 'required|exists:categories,id',
                'item_name'      => 'required|string|max:255',
                'item_color'     => 'nullable|string|max:100',
                'quantity'       => 'required|integer|min:0',
                'price_unit_ind' => 'required|integer|min:0',
                'price_dozen'    => 'required|integer|min:0',
                'price_unit_ph'  => 'required|integer|min:0',
                'cost'           => 'required|integer|min:0',
                'description'    => 'nullable|string',
            ]);
            $item->update($validated);

            return response()->json([
                'message' => 'Item updated successfully',
                'data' => $item
            ]);
        }catch(ValidationException $e){
            return response()->json([
                'message' => 'validation error occured',
                'error' => $e->getMessage()
            ], 422);
        }catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($item_id)
    {
        try{
            $item = Item::findOrFail($item_id);
            $item->delete();

            return response()->json([
                'message' => "{$item->item_name} deleted successfully",
                'data' => $item
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => "error occured",
                'error' => $e->getMessage()
            ]);
        }
    }
}
