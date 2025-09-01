<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Pharmacy;
use App\Models\Receipt;
use App\Models\ReceiptItem;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class ReportController
{
    public function index(Request $request){
        try{
            $validated = $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date'
            ]);
            $total_items = Item::where('quantity' , '>' , 0)->get();
            $pharmacies = Pharmacy::where('status' , 'opened')->get();
            $receipts = Receipt::whereBetween('created_at', [
                $validated['from_date'] . ' 00:00:00',
                $validated['to_date']   . ' 23:59:59'
            ])->get();

            $receipts_total = $receipts->sum('receipt_total');

            // Collect all receipt items
            $receiptItems = ReceiptItem::whereIn('receipt_id', $receipts->pluck('id'))->get();

            // Collect all items from receipt items
            $items = Item::whereIn('id', $receiptItems->pluck('item_id'))->get();

            // Sum cost of all items
            $total_cost = $items->sum('cost');

            return response()->json([
                'message' => 'report for the specified duration',
                'items' => $total_items,
                'pharmacies' => $pharmacies,
                'receipts_count' => $receipts->count(),
                'receipts_total' => $receipts_total,
                'total_cost' => $total_cost
                ],200);
        }catch(ValidationException $e){
            return response()->json([
                'message' => "validation error",
                'error' => $e
            ],422);
        }
        catch(Exception $e){
            return response()->json([
                'message' => "error occurred",
                'error' => $e
            ],500);
        }
    }
}
