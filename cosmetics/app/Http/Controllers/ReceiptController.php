<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use Exception;
use Illuminate\Http\Request;

class ReceiptController
{
        public function index()
    {
        $receipts = Receipt::with('pharmacy')->get(); 
        return response()->json([
            'message' => 'All receipts retrieved successfully',
            'data' => $receipts
        ], 200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pharmacy_id'   => 'required|exists:pharmacies,id',
        ]);

        try {
            $receipt = Receipt::create($validated);

            return response()->json([
                'message' => 'Receipt created successfully',
                'data' => $receipt
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function updateStatus(Request $request, $receipt_id)
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,pending,closed,deleted'
        ]);

        $receipt = Receipt::findOrFail($receipt_id);
        $receipt->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Receipt NB' . $receipt->id .' status updated successfully',
            'data' => $receipt
        ], 200);
    }
    public function update(Request $request, $receipt_id)
    {
        $validated = $request->validate([
            'pharmacy_id'   => 'required|exists:pharmacies,id',
            'receipt_total' => 'required|numeric|min:0',
            'status'        => 'required|in:draft,pending,closed,deleted'
        ]);

        $receipt = Receipt::findOrFail($receipt_id);
        $receipt->update($validated);

        return response()->json([
            'message' => 'Receipt NB' . $receipt->id .' updated successfully',
            'data' => $receipt
        ], 200);
    }
    public function destroy($receipt_id)
    {
        $receipt = Receipt::findOrFail($receipt_id);
        $receipt->delete();

        return response()->json([
            'message' => "Receipt NB' . $receipt->id .' deleted successfully",
            'data' => $receipt
        ], 200);
    }
}
