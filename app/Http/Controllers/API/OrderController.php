<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {



        
        $validator = Validator::make($request->all(), [
            
            'user_id' => 'required',
            'status' => 'required',
        ]);

       // dd($validator) ;

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->first()], 403);
        }


        if ($request->status === 'draft') {
            // Search for an existing order with the status "draft"
            $existingOrder = Order::where('status', 'draft')->first();

            // If an existing order is found, update its details
            if ($existingOrder) {
                $order = $existingOrder;

            } else {
                $order = Order::create([
                    'user_id' => $request->user_id,
                    'status' => $request->status,
                  
                ]);
            }

            try {

                // Check if the order item already exists for the given item ID
                $orderItem = $order->orderItemsDetails()->where('item_id', $request->item_id)->first();

                if ($orderItem) {
                    // If the order item exists, update its quantity and perform calculations
                    $newQuantity =  $request->quantity; // Example calculation, adjust as needed
                    $orderItem->update([
                        'qty' => $newQuantity,
                        // Perform other calculations and updates as needed
                    ]);
                    // Return success response
                    return response()->json(['message' => 'Order item quantity updated successfully'], 200);
                } else {
                    // If the order item doesn't exist, create a new order item and perform calculations
                    $newOrderItem = new OrderItems([
                        'order_id' => $order->id,
                        'item_id' => $request->item_id,
                        'qty' => $request->quantity,
                        // Add other fields and calculations as needed
                    ]);
                    $newOrderItem->save();
                    // Return success response
                    return response()->json(['message' => 'New order item created successfully'], 200);
                }
            } catch (\Exception $e) {
                // Handle any exceptions that may occur
                Log::error('Error creating or updating order item: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to create or update order item'], 500);
            }


        }


    }
}
