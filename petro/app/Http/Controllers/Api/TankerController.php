<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tanker;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TankerController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'capacity' => 'required|integer',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all()
            ], 422);
        }
        $driverId = Auth::id();


        $tanker = Tanker::create([

            'capacity' => $request->capacity,
            'driver_id' => $driverId, 
            'status' => $request->status,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Tanker registered successfully.',
            'tanker' => $tanker
        ], 201);
    }


    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
    
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $tanker = Tanker::where('tanker_id', $request->tanker_id)->first();

        if (!$tanker) {
            return response()->json([
                'status' => false,
                'message' => 'Tanker not found'
            ], 404);
        }

        $tanker->status = $request->status;
        $tanker->save();

        return response()->json([
            'status' => true,
            'message' => 'Tanker status updated successfully.',
            'data' => $tanker
        ], 200);
    }


    public function getDetails($tanker_id)
    {
        $tanker = Tanker::where('tanker_id', $tanker_id)->first();

        if (!$tanker) {
            return response()->json([
                'status' => false,
                'message' => 'Tanker not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Tanker details retrieved successfully.',
            'data' => $tanker
        ], 200);
    }
}