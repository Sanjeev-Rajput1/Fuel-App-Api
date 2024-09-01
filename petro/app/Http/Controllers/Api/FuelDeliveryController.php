<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\FuelDelivery;
use App\Models\DeliveryInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FuelDeliveryController extends Controller
{
	 public function Add(Request $request)
		{
			 $validatedData = $request->validate([
            'fuel_type' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'location' => 'required|string|max:255',
            'preferred_time' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

       
        $userId = Auth::id();

        $fuelDelivery = FuelDelivery::create(array_merge($validatedData, ['user_id' => $userId]));

       
        $responseData = [
            'id' => $fuelDelivery->id,
            'fuel_type' => $fuelDelivery->fuel_type,
            'quantity' => $fuelDelivery->quantity,
            'location' => $fuelDelivery->location,
            'preferred_time' => $fuelDelivery->preferred_time,
            'status' => $fuelDelivery->status,
            'user_id' => $fuelDelivery->user_id,
            'created_at' => $fuelDelivery->created_at,
            'updated_at' => $fuelDelivery->updated_at,
        ];

       
        return response()->json([
            'message' => 'Fuel delivery created successfully.',
            'data' => $responseData
        ], 201);
    }
	 public function cancel(Request $request,$id)
    {
       
   
        $userId = Auth::id();

     
        $fuelDelivery = FuelDelivery::where('id',$id)
            ->where('user_id', $userId)
            ->first();

        if (!$fuelDelivery) {
            return response()->json([
                'status' => false,
                'message' => 'Fuel delivery not found or you do not have permission to cancel it.'
            ], 404);
        }

  
        $fuelDelivery->status = 'cancelled';
        $fuelDelivery->save();

 
        return response()->json([
            'status' => True,
            'message' => 'Fuel delivery cancelled successfully.',
            'data' => $fuelDelivery
        ], 200);
    }
    
	   public function track(Request $request,$id)
    {
   
        $userId = Auth::id();
       
        $fuelDelivery = FuelDelivery::where('id', $id)
            ->where('user_id', $userId)
            ->first();
        
        
        if (!$fuelDelivery) {
            return response()->json([
                'status' => false,
                'message' => 'Delivery not found or you do not have permission to track it.'
            ], 404);
        }

        // Return the status of the delivery
        return response()->json([
            'status' => true,
    
            'data' => [
                'status' => $fuelDelivery->status,
            ]
        ], 200);
    }
	  public function history(Request $request)
    {
     
        $userId = Auth::id();

       
        $deliveries = FuelDelivery::where('user_id', $userId)->get();

    
        if ($deliveries->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No data found',
            ], 404);
        }

       
        $deliveries = $deliveries->map(function ($delivery) {
            $delivery = $delivery->toArray(); 
            unset($delivery['user_id']); 
            return $delivery; 
        });

        
        return response()->json([
            'status' => true,
         
            'data' => $deliveries
        ], 200);
    }
	
}
