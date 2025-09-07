<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Receipt;
use App\Models\ReceiptItem;
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
        try{
            $validated = $request->validate([
                'category_id'    => 'required|exists:categories,id',
                'item_name'      => 'required|string|max:255|unique:items,item_name',
                'item_color'     => 'nullable|string|max:100',
                'quantity'       => 'required|integer|min:0',
                'price_unit_ind' => 'required|numeric|min:0',
                'price_dozen'    => 'required|numeric|min:0',
                'price_unit_ph'  => 'required|numeric|min:0',
                'cost'           => 'required|numeric|min:0',
                'description'    => 'nullable|string',

            ]);
            $item = Item::create($validated);

            return response()->json([
                'message' => 'Item created successfully',
                'data' => $item
            ], 201);
        }catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
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
                'price_unit_ind' => 'required|numeric|min:0',
                'price_dozen'    => 'required|numeric|min:0',
                'price_unit_ph'  => 'required|numeric|min:0',
                'cost'           => 'required|numeric|min:0',
                'description'    => 'nullable|string',
            ]);
            $item->update($validated);

            return response()->json([
                'message' => 'Item updated successfully',
                'data' => $item
            ],201);
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
    public function resolveMissingItems(Request $request)
    {
        try {
            $validated = $request->validate([
                'missing' => 'required|array'
            ]);

            foreach ($validated['missing'] as $missing) {
                if($missing['missing'] == 0){
                    continue;
                }
                $item = Item::find($missing['item_id']);

                if ($item) {
                    $item->quantity += $missing['missing'];
                    $item->save();
                }
            }

            return response()->json([
                'message' => 'Missing quantities resolved by setting item quantities to zero.'
            ], 201);

        } catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation error occurred while resolving missing quantities.',
                'error' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while resolving missing quantities.',
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
            ], 201);
        }catch(Exception $e){
            return response()->json([
                'message' => "error occured",
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
