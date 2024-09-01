<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;


class DriverController extends Controller
{
    public function signUp(Request $request)
    {
    
        $request->validate([
            'driver_category' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'expiration_date' => 'required',
            'email' => 'required|email|unique:drivers,email',
            'mobile' => 'required',
            'address' => 'required',
            'password' => 'required',
            'license_number' => 'required',
            'vehicle_details' => 'required',
            'upload_valid_id.*' => 'required',
            

        ]);
 

        $driver = new Driver();
        $driver->driver_category = $request->driver_category;
        $driver->first_name = $request->first_name;
        $driver->last_name = $request->last_name;
        $driver->expiration_date = $request->expiration_date;
        $driver->email = $request->email;
        $driver->mobile = $request->mobile;
        $driver->address = $request->address;
        $driver->password = Hash::make($request->password);
        $driver->vehicle_details = $request->vehicle_details;
        $driver->license_number = $request->license_number;
       


        $upload_valid_id = $request->input('upload_valid_id');
        $json_upload_valid_id =json_encode($upload_valid_id, JSON_UNESCAPED_SLASHES);

        $driver->upload_valid_id = $json_upload_valid_id;

       
        $driver->save();    
        return response()->json([
            'status' => true,
            'message' => 'Driver created successfully.',
            'data' => $driver,
        ], 201);
    }

    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()->all()
            ], 422);
        }
    
     
        $credentials = $request->only('email', 'password');
    
       
        $driver = Driver::where('email', $request->email)->first();
    
        if ($driver && Hash::check($request->password, $driver->password)) {
            $token = $driver->createToken('API Token')->plainTextToken;
    
            return response()->json([
                'status' => true,
                'message' => 'Driver logged in successfully',
                'token' => $token,
                'token_type' => 'bearer'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email and Password do not match.',
            ], 401);
        }
    }
    
    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); 

        return response()->json([
            'status' => true,
            'message' => 'Logout successful.'
        ], 200);
    }


    public function show($id)
    {
        $driver = Driver::find($id);

        
        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found.'
            ], 404);
        }
    
        $responseData = [
            'status' => true,
            'data' => [
                'id' => $driver->id,
                'driver_category' => $driver->driver_category,
                'first_name' => $driver->first_name,
                'last_name' => $driver->last_name,
                'expiration_date' => $driver->expiration_date,
                'email' => $driver->email,
                'mobile' => $driver->mobile,
                'address' => $driver->address,
                'license_number' => $driver->license_number,
                'vehicle_details' => $driver->vehicle_details,
                'upload_valid_id' => $driver->upload_valid_id,
                'status' => $driver->status,
                'created_at' => $driver->created_at,
                'updated_at' => $driver->updated_at,
            ],
        ];
    
        $locationData = [];
        if (!empty($driver->latitude)) {
            $locationData['latitude'] = $driver->latitude;
        }
        if (!empty($driver->longitude)) {
            $locationData['longitude'] = $driver->longitude;
        }
        
        if (!empty($locationData)) {
            $responseData['driver']['location'] = $locationData;
        }
        
        return response()->json([
            'status' => true,
            'driver' => $responseData
        ], 200);
    }

    // Update Method
    public function update(Request $request, $id)
    {
        $driverId = Auth::id();
        // Define validation rules
        $request->validate([
            'driver_category' => 'nullable|string',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'email' => [
                'nullable',
                'email',
                Rule::unique('drivers')->ignore($id)
            ],
            'mobile' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'status'  => "required",
            'upload_valid_id.*' => 'nullable',
            'license_number' => 'nullable' ,
            'vehicle_details' => 'nullable',

        ]);
    
  

        $driver = Driver::find($id);
    
    
        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found.'
            ], 404);
        }
    

        if ($request->has('driver_category')) {
            $driver->driver_category = $request->input('driver_category');
        }
        if ($request->has('first_name')) {
            $driver->first_name = $request->input('first_name');
        }
        if ($request->has('last_name')) {
            $driver->last_name = $request->input('last_name');
        }
        if ($request->has('expiration_date')) {
            $driver->expiration_date = $request->input('expiration_date');
        }
        if ($request->has('email')) {
            $driver->email = $request->input('email');
        }
        if ($request->has('mobile')) {
            $driver->mobile = $request->input('mobile');
        }
        if ($request->has('address')) {
            $driver->address = $request->input('address');
        }
        if ($request->has('license_number')) {
            $driver->license_number = $request->input('license_number');
        }

        if ($request->has('vehicle_details')) {
            $driver->vehicle_details = $request->input('vehicle_details');
        }
        if ($request->has('upload_valid_id')) {
            $upload_bir = $request->input('upload_valid_id');
            $json_upload_bir =json_encode($upload_valid_id, JSON_UNESCAPED_SLASHES);
            $driver->upload_valid_id = $json_upload_valid_id;
        }
        if ($request->has('status')) {
            $driver->status = $request->input('status');
        }
       
       

        $driver->save();
    
        // Return success response
        return response()->json([
            'status' => true,
            'message' => 'Driver updated successfully.',
            'data' => $driver,
        ], 200);
    }
    
    public function delete($id)
    {
        $user = Auth::user(); // Check if user is authenticated
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found.'
            ], 404);
        }

        $driver->delete(); // If SoftDeletes is used

        return response()->json([
            'status' => true,
            'message' => 'Driver deleted successfully.'
        ], 200);
    }


    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $driver = Driver::find($id);
        
        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => 'Driver not found.'
            ], 404);
        }
        $driver->latitude = $request->latitude;
        $driver->longitude = $request->longitude;
        $driver->save();

        return response()->json([
            'status' => true,
            'message' => 'Update location successfully.'
        ], 200);
    }


}

