<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\FuelDelivery;
use App\Models\DeliveryInfo;
use App\Models\Inventory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class InventoryController extends Controller
{
	 public function addFuelStock(Request $request)
		{
			 try {
	
				$validatedData = $request->validate([
					'fuel_type' => 'required|string',
					'quantity' => 'required|integer',
					'date' => 'required|date_format:Y-m-d',
					'source' => 'nullable|string',
				]);
				
	
			
		
				$userId = Auth::id();
				if (!$userId) {
					return response()->json(['status'=>false,'error' => 'User not authenticated'], 401);
				}

				
				$inventory = Inventory::create(array_merge($validatedData, ['user_id' => $userId]));
			
				return response()->json(['status'=>true,'success' => 'Fuel stock added successfully', 'inventory' => $inventory]);
			} catch (ValidationException $e) {
				
				return response()->json(['error' => $e->errors()], 422);
			} catch (\Exception $e) {
				
				if (strpos($e->getMessage(), 'Invalid datetime format') !== false) {
					return response()->json(['status'=>false,'error' => 'Invalid date format. Please use the format YYYY-MM-DD.'], 400);
				}
				
				return response()->json(['error' => $e->getMessage()], 500);
			}
		}
		
		
		public function updateFuelStock(Request $request, $id)
		{
			try {
				
				$validatedData = $request->validate([
					'quantity' => 'required|integer|min:0', 
				]);

			
				$userId = Auth::id();
				if (!$userId) {
					return response()->json(['status'=>false,'error' => 'User not authenticated'], 401);
				}

				$inventory = Inventory::where('id', $id)
									  ->where('user_id', $userId)
									  ->first();

				if (!$inventory) {
					return response()->json(['status'=>false,'error' => 'Fuel type not found'], 404);
				}

				
				$inventory->quantity = $validatedData['quantity'];
				$inventory->save();

				return response()->json(['status'=>true,'success' => 'Fuel stock updated successfully', 'inventory' => $inventory]);

			} catch (ValidationException $e) {
		
				\Log::error('Validation failed', ['errors' => $e->errors()]);
				return response()->json(['error' => $e->errors()], 422);

			} catch (\Exception $e) {
				
				\Log::error('An error occurred', ['message' => $e->getMessage()]);
				return response()->json(['error' => $e->getMessage()], 500);
			}
		}
		
		
	public function getFuelStockLevels(Request $request,$id)
	{
		try {
		
			$userId = Auth::id();
			if (!$userId) {
				return response()->json(['status'=>false,'error' => 'User not authenticated'], 401);
			}

		
			$inventory = Inventory::where('id', $id)
								  ->where('user_id', $userId)
								  ->first();

			if (!$inventory) {
				return response()->json(['status'=>false,'error' => 'Fuel type not found'], 404);
			}

			return response()->json(['status'=>true,'quantity' => $inventory->quantity]);

		} catch (\Exception $e) {
			
			\Log::error('An error occurred', ['message' => $e->getMessage()]);
			return response()->json(['error' => $e->getMessage()], 500);
		}
	}
	
 public function getInventoryHistory(Request $request)
    {
      
        $dateRange = $request->query('date_range');
        $fuelType = $request->query('fuel_type');

       
        $query = Inventory::query();

        if ($dateRange) {
          
            $dates = explode(',', $dateRange);
            if (count($dates) === 1) {
              
                $date = $dates[0];
                $validatedDate = \DateTime::createFromFormat('Y-m-d', $date);
                if ($validatedDate && $validatedDate->format('Y-m-d') === $date) {
                    $query->whereDate('date', $date);
                } else {
                    return response()->json(['status'=>false,'error' => 'Invalid date format. Use YYYY-MM-DD.'], 400);
                }
            } elseif (count($dates) === 2) {
                
                [$startDate, $endDate] = $dates;
                $start = \DateTime::createFromFormat('Y-m-d', $startDate);
                $end = \DateTime::createFromFormat('Y-m-d', $endDate);
                if ($start && $start->format('Y-m-d') === $startDate && $end && $end->format('Y-m-d') === $endDate) {
                    $query->whereBetween('date', [$startDate, $endDate]);
                } else {
                    return response()->json(['status'=>false,'error' => 'Invalid date range format. Use YYYY-MM-DD,YYYY-MM-DD.'], 400);
                }
            } else {
                return response()->json(['error' => 'Invalid date range format.'], 400);
            }
        }

        if ($fuelType) {
            $query->where('fuel_type', $fuelType);
        }

        $history = $query->get();
		     if ($history->isEmpty()) {
           
            return response()->json(['status'=>false,'message' => 'No inventory history found.'], 404);
        }
        $history = $history->map(function ($history) {
            $history = $history->toArray(); 
            unset($history['user_id']); 
            return $history; 
        });
        return response()->json($history);
    }
}
