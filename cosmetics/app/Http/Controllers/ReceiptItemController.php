<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use App\Models\Receipt;
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
            $pharmacy = Pharmacy::find($receiptItems[0]->receipt);
            return response()->json([
                'message' => 'All receipt items retrieved successfully',
                'data' => $receiptItems,
                'pharmacy'=> $pharmacy
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
            $createdItems = [];
            $validated = $request->validate([
                'receipt_items' => 'required|array',
                'receipt_id' => 'required|exists:receipts,id',
                'receipt_items.*.price' => 'required|numeric|min:0',
                'receipt_items.*.item_id' => 'required|exists:items,id',
                'receipt_items.*.notes' => 'nullable',
                'receipt_items.*.quantity' => 'required|integer|min:1',
                'receipt_items.*.total' => 'required|numeric|min:0',
            ]);

            $receipt_total = 0;

            foreach($validated['receipt_items'] as $receipt_item){
                $receipt_item['receipt_id'] = $validated['receipt_id'];
                $receiptItem = ReceiptItem::create($receipt_item);
                $createdItems[] = $receiptItem;
                $receipt_total += $receipt_item['total'];
            }
            $receipt = Receipt::find($validated['receipt_id']);
            $receipt->receipt_total = $receipt->receipt_total + $receipt_total;
            $receipt->save();

            return response()->json([
                'message' => 'Receipt items created successfully',
                'data' => $createdItems
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
                'price'      => 'required',
                'item_id'    => 'required|exists:items,id',
                'quantity'   => 'required|integer|min:1',
                'total'      => 'required|numeric|min:0',
            ]);

            $receiptItem = ReceiptItem::findOrFail($id);
            $receiptItem->update($validated);

            $receipt = Receipt::findOrFail($validated['receipt_id']);
            $receipt->receipt_total = $receipt->receiptItems->sum('total');
            $receipt->save();

            return response()->json([
                'message' => 'Receipt item updated successfully',
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

    public function destroy($id)
    {
        try {
            $receiptItem = ReceiptItem::findOrFail($id);
            $receiptItem->delete();

            return response()->json([
                'message' => 'Receipt item deleted successfully',
                'data' => $receiptItem
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
