<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\TankerController;
use App\Http\Controllers\Api\FuelDeliveryController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\SupportFeedbackController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\FuelPurchasingController;

//users
// Route::post("/signup", [ApiController::class, "signup"]);
// Route::post("/login", [ApiController::class, "login"])->name('login');

//drivers
// Route::post("/driver/signup", [DriverController::class, "signup"]);
// Route::post("/driver/login", [DriverController::class, "login"])->name('login');

// Route::get('/support/contact', [SupportFeedbackController::class, 'getContactDetails']);

// Route::group([
    // "middleware" => ["auth:sanctum"]
// ], function(){
    // Route::get("/user/logout", [ApiController::class, "logout"]);
    // Route::get("/user/profile", [ApiController::class, "profile"]);
    // Route::put("/user/profile/update", [ApiController::class, "updateProfile"]);
    // Route::post("/user/company/add", [ApiController::class, "addCompany"]);
    // Route::get("/user/company/get", [ApiController::class, "getCompany"]);
    // Route::put("/user/company/update", [ApiController::class, "updateCompany"]);
	
	
	
	// Route for Delivery Info
	// Route::get('/user/address', [ApiController::class, 'Get_List_delivery_addresses']);
	// Route::post('/user/address/add', [ApiController::class, 'add_delivery_address']);
	// Route::put('/user/address/update/{id}', [ApiController::class, 'update_delivery_address']);
	// Route::put('/user/address/set-as-primary/{id}', [ApiController::class, 'set_as_primary']);
    // Route::get('/user/address/get/{id}', [ApiController::class, 'get_delivery_addresses']);
    // Route::get('/user/address/delete/{id}', [ApiController::class, 'delete_delivery_address']);

	// Route for Media
    // Route::apiResource('media', MediaController::class);
    // Route::get('/user/media/url', [MediaController::class, 'showByUrl']);
	
	// Route for FuelDelivery
	// Route::post('/user/delivery/request', [FuelDeliveryController::class, 'Add']);
	// Route::post('/user/delivery/cancel/{id}', [FuelDeliveryController::class, 'cancel']);
	// Route::get('/user/delivery/track/{id}', [FuelDeliveryController::class, 'track']);
	// Route::get('/user/delivery/history', [FuelDeliveryController::class, 'history']);

    // Route for Driver

    // Route::get("/driver/logout", [DriverController::class, "logout"]);
    // Route::get("/driver/show/{id}", [DriverController::class, "show"]);
    // Route::put("/driver/update/{id}", [DriverController::class, "update"]);
    // Route::get("/driver/delete/{id}", [DriverController::class, "delete"]);
    // Route::post("/driver/location/{id}/update", [DriverController::class, "updateLocation"]);

    // Routes for Tanker
    // Route::post("/driver/tanker/register", [TankerController::class, "register"]);
    // Route::put("/driver/tanker/{tanker_id}/updatestatus", [TankerController::class, "updateStatus"]);
    // Route::get("/driver/tanker/get/{tanker_id}", [TankerController::class, "getDetails"]);
    
	
    // Routes for inventory
	// Route::post('/user/inventory/add', [InventoryController::class, 'addFuelStock']);
	// Route::post('/user/inventory/{id}/update', [InventoryController::class, 'updateFuelStock']);
	// Route::get('/user/inventory/{id}/levels', [InventoryController::class, 'getFuelStockLevels']);
	// Route::get('/user/inventory/history/', [InventoryController::class, 'getInventoryHistory']);
	
	
    // Routes for Support
	// Route::post('/user/feedback', [SupportFeedbackController::class, 'submitFeedback']);
	

    // Fuelpurchasing
    // Route::post("/user/fuel/purchase", [FuelPurchasingController::class, "purchase"]);
    // Route::get("/user/fuel/purchasehistory", [FuelPurchasingController::class, "getPurchaseHistory"]);
	



	 // Retrieve notifications
    // Route::get('/user/notifications', [NotificationController::class, 'getNotifications']);
    
    // Mark notification as read
    // Route::post('/user/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
// });
    	






