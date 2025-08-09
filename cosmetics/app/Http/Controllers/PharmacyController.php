<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PharmacyController
{
    public function index(){
        $pharmacies = Pharmacy::all();
        return response()->json([
            'message' => 'this endpoint returns all pharmacies',
            'data' => $pharmacies
        ]);
    }
    public function store(Request $request){
        $validated = $request->validate([
            'pharmacy_name' => 'required|string|max:255|unique:pharmacies,pharmacy_name',
            'pharmacy_owner' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{7,15}$/',
            'address' => 'required|string|max:500',
        ]);
        $pharmacy = Pharmacy::create($validated);

        return response()->json([
            'message' => 'new pharmacy created successfully',
            'data' => $pharmacy
        ]);
    }
    public function update(Request $request , $pharmacy_id){
        $validated = $request->validate([
            'pharmacy_name' => 'required|string|max:255|unique:pharmacies,pharmacy_name' .$pharmacy_id,
            'pharmacy_owner' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^\+?[0-9]{7,15}$/',
            'address' => 'required|string|max:500',
            'status' => 'required|in:opened,closed'
        ]);

        $pharmacy = Pharmacy::findOrFail($pharmacy_id);
        $pharmacy->update($validated);

        return response()->json([
            'message' => 'pharmacy updated successfully',
            'data' => $pharmacy
        ]);
    }
    public function destroy($pharmacy_id){
        $pharmacy = Pharmacy::findOrFail($pharmacy_id);
        $pharmacy->delete();
        
        return response()->json([
            'message' => $pharmacy->pharmacy_name .'deleted successfully',
            'data' => $pharmacy
        ]);
    }
}
