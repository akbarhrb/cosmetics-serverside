<?php

namespace App\Http\Controllers;

use App\Models\ReceiptItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class ReceiptItemController extends Controller
{
    public function index($receipt_id)
    {
        try {
            $receiptItems = ReceiptItem::where('receipt_id', $receipt_id)
                ->with(['receipt', 'item'])
                ->get();

            return response()->json([
                'message' => 'All receipt items retrieved successfully',
                'data' => $receiptItems
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'receipt_id' => 'required|exists:receipts,id',
                'price'      => 'required|decimal:1,2|min:0',
                'item_id'    => 'required|exists:items,id',
                'quantity'   => 'required|integer|min:1',
                'total'      => 'required|numeric|min:0',
            ]);

            $receiptItem = ReceiptItem::create($validated);

            return response()->json([
                'message' => 'Receipt item created successfully',
                'data' => $receiptItem
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'receipt_id' => 'required|exists:receipts,id',
                'price'      => 'required|decimal:1,2|min:0',
                'item_id'    => 'required|exists:items,id',
                'quantity'   => 'required|integer|min:1',
                'total'      => 'required|numeric|min:0',
            ]);

            $receiptItem = ReceiptItem::findOrFail($id);
            $receiptItem->update($validated);

            return response()->json([
                'message' => 'Receipt item updated successfully',
                'data' => $receiptItem
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $receiptItem = ReceiptItem::findOrFail($id);
            $receiptItem->delete();

            return response()->json([
                'message' => 'Receipt item deleted successfully',
                'data' => $receiptItem
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
