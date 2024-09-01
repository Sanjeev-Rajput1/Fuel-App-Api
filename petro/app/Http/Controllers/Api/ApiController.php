<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\DeliveryInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;


class ApiController extends Controller
{
    public function signUp(Request $request)
    {
            $validateUser = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'position_in_company' => 'required',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required',
                'password' => 'required',

            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors()->all()
                ], 422);
            }

            $user = new User();

                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->position_in_company = $request->position_in_company;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $user->agent_code = $request->agent_code;
                $user->password = Hash::make($request->password);
                $user->role = 'user';

                $user->save();
                $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User created successfully.',
                'token' => $token ,
                'user' => $user,
            ], 200);
    }

    public function login(Request $request)
    {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors()->all()
                ], 422);
            }
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $authUser = Auth::user();
                return response()->json([
                    'status' => true,
                    'message' => 'User logged in successfully',
                    'token' =>  $authUser->createToken("API Token")->plainTextToken,
                    'token_type' => 'bearer'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Email and Password do not match.',
                ], 422);
            }
    }
    public function profile()
    {
            if (!Auth::check()) {

                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired token.',
                    'data' => []
                ], 401);
            } else {
                $user = auth()->user();	
                $user->load('company', 'deliveryInfos'); 	
                $responseData = [
                    'user' => $user,
                ];

                return response()->json([
                    'status' => true,
                    'message' => 'Profile Information.',
                    'data' => $responseData
                ], 200);
            }
    }

    public function logout(){
            $userData = auth()->user()->tokens()->delete();

                return response()->json([
                    "status" => true,
                    "message" => "User has been logged out.",
                    "data" => []
                ]);
    }


    public function updateProfile(Request $request){

            $user = $request->user();

            $validateUser = Validator::make($request->all(), [
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
                'mobile' => 'sometimes|required',
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'position_in_company' => 'nullable',
                'agent_code' => 'nullable',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors()->all()
                ], 422);
            }

            if ($request->has('first_name')) {
                $user->first_name = $request->first_name;
            }
            if ($request->has('last_name')) {
                $user->last_name = $request->last_name;
            }
            if ($request->has('position_in_company')) {
                $user->position_in_company = $request->position_in_company;
            }
            if ($request->has('agent_code')) {
                $user->agent_code = $request->agent_code;
            }

            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully.',
                'user' => $user,
            ], 200);
    }



    public function addCompany(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string',
                'tin_number' => 'required|string',
                'company_address' => 'required|string',
                'company_email' => 'required|email|unique:company_info,company_email',
                'company_contact_number' => 'required|string',
                'upload_bir.*' => 'required',
                'upload_sec.*' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $existingCompany = Company::where('user_id', auth()->id())->first();

            if ($existingCompany) {
                return response()->json([
                    'status' => false,
                    'message' => 'The company already exists. Please update the existing company record instead.'
                ], 400);
            }

            $company = new Company();
            $company->company_name = $request->input('company_name');
            $company->tin_number = $request->input('tin_number');
            $company->company_address = $request->input('company_address');
            $company->company_email = $request->input('company_email');
            $company->company_contact_number = $request->input('company_contact_number');
            $company->user_id = auth()->id();
            
            $upload_bir = $request->input('upload_bir');
            $json_upload_bir =json_encode($upload_bir, JSON_UNESCAPED_SLASHES);
            $upload_sec = $request->input('upload_sec');
            $json_upload_sec =json_encode($upload_sec, JSON_UNESCAPED_SLASHES);
            $company->upload_bir = $json_upload_bir;
            $company->upload_sec = $json_upload_sec;
            $company->save();
            
                return response()->json([
                'status' => true,
                'message' => 'Company added successfully.',
                'company' => $company,
                ], 200);
    }


    // public function updateCompany(Request $request)
    // {
            // $company = Company::where('user_id', auth()->id())->first();

            // $validator = Validator::make($request->all(), [
                // 'company_name' => 'nullable|string',
                // 'tin_number' => 'nullable|string',
                // 'company_address' => 'nullable|string',
                // 'company_email' => [
                // 'nullable',
                // 'email',
                // Rule::unique('company_info', 'company_email')->ignore(Company::where('user_id', auth()->id())->first()->id ?? null),
                // ],
                // 'company_contact_number' => 'nullable|string',
                // 'upload_bir.*'=> 'nullable',
                // 'upload_sec.*' => 'nullable',
                // ]);


            // if ($validator->fails()) {
                // return response()->json([
                    // 'status' => false,
                    // 'message' => 'Validation Error',
                    // 'errors' => $validator->errors()->all()
                    // ], 422);
            // }

            // $company = Company::where('user_id', auth()->id())->first();

            // if (!$company) {
                // return response()->json([
                    // 'status' => false,
                    // 'message' => 'Company not found'
                    // ], 400);
            // }

            // if ($request->has('company_name')) {
                // $company->company_name = $request->input('company_name');
            // }
            // if ($request->has('tin_number')) {
                // $company->tin_number = $request->input('tin_number');
            // }
            // if ($request->has('company_address')) {
                // $company->company_address = $request->input('company_address');
            // }
            // if ($request->has('company_email')) {
                // $company->company_email = $request->input('company_email');
            // }
            // if ($request->has('company_contact_number')) {
                // $company->company_contact_number = $request->input('company_contact_number');
            // }
            // if ($request->has('upload_bir')) {
                // $upload_bir = $request->input('upload_bir');
                // $json_upload_bir =json_encode($upload_bir, JSON_UNESCAPED_SLASHES);
                // $company->upload_bir = $json_upload_bir;
            // }
            // if ($request->has('upload_sec')) {
                // $upload_sec = $request->input('upload_sec');
                // $json_upload_sec =json_encode($upload_sec, JSON_UNESCAPED_SLASHES);
                // $company->upload_sec = $json_upload_sec;
            // }

        
            // $company->save();
            
            // return response()->json([
                // 'status' => true,
                // 'message' => 'Company updated successfully.',
                // 'company' => $company ,
                // ], 200);
    // }



    public function getCompany(Request $request)
{
    // Authenticate the user
    $user = $request->user();
    
    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not authenticated.'
        ], 401);
    }
    
    // Find the company associated with the authenticated user
    $company = Company::where('user_id', $user->id)->first();
    
    if (!$company) {
        return response()->json([
            'status' => false,
            'message' => 'No company information found for this user.'
        ], 404);
    }
    
    return response()->json([
        'status' => true,
        'message' => 'Company information retrieved successfully.',
        'company' => $company
    ], 200);
}

public function add_delivery_address(Request $request)
{

    $user = auth()->user();
    
    // Validate the request data
    $validator = Validator::make($request->all(), [
        'delivery_address' => 'required|string',
        'company_name' => 'required|string',
        'contact_number' => 'required|string',
        'type_of_industry' => 'required|string',
        'storage_type' => 'required|string',
        'other_specify' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([   
            'errors' => $validator->errors()
        ], 422);
    }


    $user_id = $user->id;


    $existingDeliveryInfo = DeliveryInfo::where('user_id', $user_id)->first();

    if ($existingDeliveryInfo) {
    
        $deliveryInfo = DeliveryInfo::create(array_merge($request->all(), [
            'user_id' => $user_id,
            'type' => 'secondary'
        ]));
        return response()->json([
        'status' => true,
            'message' => 'New secondary delivery address created successfully',
            'data' => $deliveryInfo
        ], 201);
    } else {
    
        $deliveryInfo = DeliveryInfo::create(array_merge($request->all(), [
            'user_id' => $user_id,
            'type' => 'primary'
        ]));
        return response()->json([
            'status' => true,
            'message' => 'New primary delivery address  created successfully',
            'data' => $deliveryInfo
        ], 201);
    }
}
    public function update_delivery_address(Request $request, $id)
    {
        $user = auth()->user();
            
            // Validate the request data
        $validator = Validator::make($request->all(), [
            'delivery_address' => 'required|string',
            'company_name' => 'required|string',
            'contact_number' => 'required|string',
            'type_of_industry' => 'required|string',
            'storage_type' => 'required|string',
            'other_specify' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
            
        $deliveryInfo = DeliveryInfo::find($id);

        if (!$deliveryInfo) {
            return response()->json([
                'status' => false,
                'message' => 'Delivery Address record not found'
            ], 400);
        }else{
                
        if ($deliveryInfo->type == 'primary') {
                        
            $deliveryInfo->update(array_merge($request->all(), [
                'type' => 'primary'
        ]));

            return response()->json([
                'status' => true,
                'message' => 'primary delivery address  updated  successfully',
                'data' => $deliveryInfo
            ], 200);
        } else {
                        
            $deliveryInfo->update(array_merge($request->all(), [
                'type' => 'secondary'
            ]));
                
            return response()->json([
                'status' => true,
                'message' => 'secondary delivery address updated successfully',
                'data' => $deliveryInfo
            ], 200);
        }
                
        }
        
    }

    public function set_as_primary(Request $request, $id)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'delivery_address' => 'required|string',
            'company_name' => 'required|string',
            'contact_number' => 'required|string',
            'type_of_industry' => 'required|string',
            'storage_type' => 'required|string',
            'other_specify' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $deliveryInfo = DeliveryInfo::find($id);

        if (!$deliveryInfo) {
            return response()->json([
                'status' => false,
                'message' => 'Delivery Address record not found'
            ], 404);
        }


        if ($deliveryInfo->type === 'primary') {
            return response()->json([
                'status' => false,
                'message' => 'Delivery Address is already the primary address'
            ], 400);
        }

        DeliveryInfo::where('user_id', $user->id)
            ->where('type', 'primary')
            ->update(['type' => 'secondary']);

        $deliveryInfo->update(array_merge($request->only([
            'delivery_address',
            'company_name',
            'contact_number',
            'type_of_industry',
            'storage_type',
            'other_specify'
        ]), ['type' => 'primary']));

        return response()->json([
            'status' => true,
            'message' => 'Primary delivery address updated successfully',
            'data' => $deliveryInfo
        ], 200);
    }

    public function Get_List_delivery_addresses(Request $request)
    {

     
        $user = $request->user(); 
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
    
        $deliveryInfos = DeliveryInfo::where('user_id', $user->id)->get();
    
        if ($deliveryInfos->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No delivery addresses found for this user.'
            ], 404);
        }

            return response()->json([
                'status' => true,
                'message' => 'Delivery addresses retrieved successfully.',
                'data' => $deliveryInfos
            ], 200);
    }

    public function get_delivery_addresses(Request $request,$id)
    {
		
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }
        
        $deliveryInfos = DeliveryInfo::where('id', $id)->get();
        
        if ($deliveryInfos->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No delivery addresses found for this user.'
            ], 404);
        }

            return response()->json([
                'status' => true,
                'message' => 'Delivery addresses retrieved successfully.',
                'data' => $deliveryInfos
            ], 200);
    }
    
    public function delete_delivery_address(Request $request, $id)
    { 
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        $deliveryInfo = DeliveryInfo::where('user_id', $user->id)->where('id', $id)->first();
    
        if (!$deliveryInfo) {
            return response()->json([
                'status' => false,
                'message' => 'Delivery address not found or does not belong to this user.'
            ], 404);
        }

        if ($deliveryInfo->type === 'primary') {
            $secondaryAddress = DeliveryInfo::where('user_id', $user->id)->where('type', 'secondary')->first();
    
        if (!$secondaryAddress) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot delete primary address because no secondary addresses exist.'
            ], 400);
        }
        
        $secondaryAddress->update(['type' => 'primary']);
        }
        $deliveryInfo->delete();
        
            return response()->json([
                'status' => true,
                'message' => 'Delivery address deleted successfully.'
            ], 200);
        }
  
    


}





