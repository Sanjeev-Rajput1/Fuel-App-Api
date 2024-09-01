<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;

class SupportFeedbackController extends Controller
{
    public function submitFeedback(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 400);
        }

        // Ensure the user is authenticated
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated.',
            ], 401);
        }

        // Create a new feedback record
        $feedback = Feedback::create([
            'user_id' => $userId,
            'message' => $request->input('message'),
            'rating' => $request->input('rating'),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Feedback submitted successfully.',
            'data' => $feedback,
        ]);
    }
   
    public function getContactDetails()
    {
    
        $contactDetails = [
            'email' => 'support@example.com',
            'phone' => '+1-800-555-1234',
            'hours' => 'Mon-Fri, 9am-5pm',
        ];

        return response()->json($contactDetails);
    }
	
}