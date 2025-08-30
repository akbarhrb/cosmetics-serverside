<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Pharmacy;
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
            $items = Item::where('quantity' , '>' , 0)->get();
            $pharmacies = Pharmacy::where('status' , 'opened')->get();
            return response()->json([
                'message' => 'report for the specified duration',
                'items' => $items,
                'pharmacies' => $pharmacies
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
