<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tanker;
use App\Models\Driver;
use App\Models\User;
use App\Models\FuelPurchasing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class FuelPurchasingController extends Controller
{
    public function purchase(Request $request)
{

    $validator = Validator::make($request->all(), [
        'fuel_type' => 'required',
        'quantity' => 'required',
        'price' => 'required',
        'supplier_id' => 'required',
        'date' => 'required|date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422);
    }

      
    $userId = Auth::id();
    $fuelPurchase = FuelPurchasing::create([
        'fuel_type' => $request->input('fuel_type'),
        'quantity' => $request->input('quantity'),
        'price' => $request->input('price'),
        'supplier_id' => $request->input('supplier_id'),
        'date' => $request->input('date'),
        'user_id' => $userId,
    ]);


    return response()->json([
        'status' => true,
        'message' => 'Fuel purchased successfully.',
        'data' => $fuelPurchase
    ], 201);
}

    public function getPurchaseHistory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'nullable',
            'supplier_id' => 'nullable',
            'fuel_type' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = FuelPurchasing::query();

        if ($request->has('date') && $request->input('date')) {
            $query->where('date', $request->input('date'));
        }
        

        if ($request->has('supplier_id') && $request->input('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }

        if ($request->has('fuel_type') && $request->input('fuel_type')) {
            $query->where('fuel_type', $request->input('fuel_type'));
        }


        $fuelPurchases = $query->get();

   
        if ($fuelPurchases->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No records found.',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $fuelPurchases
        ], 200);
    }




}

