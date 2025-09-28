<?php

namespace App\Http\Controllers;

use App\Models\Pharmacy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PharmacyController
{
    public function index(){
        try{
            $pharmacies = Pharmacy::all();
            return response()->json([
                'message' => 'this endpoint returns all pharmacies',
                'data' => $pharmacies
            ]);
        }catch(Exception $e){
            return response()->json([
                'message' => 'error oocured',
                'error' => $e->getMessage()
            ]);
        }
    }
    public function store(Request $request){
        try{
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
            ],201);
        }catch(ValidationException $e){
            return response()->json([
                'message' => 'validation error oocured',
                'error' => $e->getMessage()
            ],422);
        }catch(Exception $e){
            return response()->json([
                'message' => 'error oocured',
                'error' => $e->getMessage()
            ],500);
        }
    }
    public function update(Request $request , $pharmacy_id){
        try{
            $validated = $request->validate([
                'pharmacy_name' => 'required|string|max:255|unique:pharmacies,pharmacy_name,' . $pharmacy_id,
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
            ],200);
        }catch(ValidationException $e){
            return response()->json([
                'message' => 'validation error oocured',
                'error' => $e->getMessage()
            ],422);
        }catch(Exception $e){
            return response()->json([
                'message' => 'error oocured',
                'error' => $e->getMessage()
            ],500);
        }
    }
    public function destroy($pharmacy_id){
        $pharmacy = Pharmacy::findOrFail($pharmacy_id);
        $pharmacy->delete();

        return response()->json([
            'message' => $pharmacy->pharmacy_name .' deleted successfully',
            'data' => $pharmacy
        ]);
    }
}
