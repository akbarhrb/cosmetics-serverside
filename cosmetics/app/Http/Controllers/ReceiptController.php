<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ReceiptController
{
    public function index()
    {
        try {
            $receipts = Receipt::with('pharmacy')->get();

            return response()->json([
                'message' => 'All receipts retrieved successfully',
                'data' => $receipts
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve receipts',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'pharmacy_id'   => 'required|string|exists:pharmacies,id',
            ]);

            $receipt = Receipt::create($validated);

            return response()->json([
                'message' => 'Receipt created successfully',
                'data' => $receipt
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $receipt_id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:draft,pending,closed,deleted'
            ]);

            $receipt = Receipt::findOrFail($receipt_id);
            $receipt->update(['status' => $validated['status']]);

            return response()->json([
                'message' => 'Receipt NB ' . $receipt->id . ' status updated successfully',
                'data' => $receipt
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $receipt_id)
    {
        try {
            $validated = $request->validate([
                'pharmacy_id'   => 'required|exists:pharmacies,id',
                'receipt_total' => 'required|numeric|min:0',
                'status'        => 'required|in:draft,pending,closed,deleted'
            ]);

            $receipt = Receipt::findOrFail($receipt_id);
            $receipt->update($validated);

            return response()->json([
                'message' => 'Receipt NB' . $receipt->id . ' updated successfully',
                'data' => $receipt
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($receipt_id)
    {
        try {
            $receipt = Receipt::findOrFail($receipt_id);
            $receipt->delete();

            return response()->json([
                'message' => 'Receipt NB' . $receipt->id . ' deleted successfully',
                'data' => $receipt
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
