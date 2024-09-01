<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    
    public function getNotifications(Request $request)
    {
        $userId = Auth::id();

      
        $notifications = Notification::where('user_id', $userId)
            ->get();
			
	   $notifications = $notifications->map(function ($notifications) {
			$notifications = $notifications->toArray(); 
			unset($notifications['user_id']); 
			return $notifications; 
		});

        return response()->json([
            'status' => true,
            'data' => $notifications,
        ]);
    }

   
    public function markAsRead(Request $request)
    {
		$userId = Auth::id();
		$notificationId = $request->query('notification_id');

	
		$validator = \Validator::make($request->all(), [
			'notification_id' => 'required', 
		]);

		
		if ($validator->fails()) {
			return response()->json(['errors' => $validator->errors()], 400);
		}


      
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read.',
        ]);
    }
	
}
