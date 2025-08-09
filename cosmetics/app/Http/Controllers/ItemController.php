<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Exception;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'category_id'    => 'required|exists:categories,id',
            'item_name'      => 'required|unique|string|max:255',
            'item_color'     => 'nullable|string|max:100',
            'quantity'       => 'required|integer|min:0',
            'price_unit_ind' => 'required|integer|min:0',
            'price_dozen'    => 'required|integer|min:0',
            'price_unit_ph'  => 'required|integer|min:0',
            'cost'           => 'required|integer|min:0',
            'description'    => 'nullable|string',
        ]);

        try {
            $item = Item::create($validated);

            return response()->json([
                'message' => 'Item created successfully',
                'data' => $item
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

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

        try {
            $item->update($validated);

            return response()->json([
                'message' => 'Item updated successfully',
                'data' => $item
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($item_id)
    {
        $item = Item::findOrFail($item_id);
        $item->delete();

        return response()->json([
            'message' => "{$item->item_name} deleted successfully",
            'data' => $item
        ]);
    }
}
