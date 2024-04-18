<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function createCart(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'user_id' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->first()], 403);
        }
        if ($request->status === 'draft') {
            $existingOrder = Order::where('status', 'draft')->first();
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
                $productDetails = Product::find($request->item_id);
                $price = $productDetails->price;
                //   $productDetails = Product::where('id', $request->item_id)->first();

                // dd($productDetails);
                if ($orderItem) {
                    // If the order item exists, update its quantity and perform calculations
                    $newQuantity = $request->quantity;
                    $orderItem->update([
                        'qty' => $newQuantity,
                        'price' => $price,
                        'subtotal' => $newQuantity * $price

                    ]);

                    //  return response()->json(['message' => 'Order item quantity updated successfully'], 200);
                } else {

                    $newOrderItem = new OrderItems([
                        'order_id' => $order->id,
                        'item_id' => $request->item_id,
                        'qty' => $request->quantity,
                        'price' => $price,
                        'subtotal' => $request->quantity * $price

                    ]);
                    $newOrderItem->save();
                    //  return response()->json(['message' => 'New order item created successfully'], 200);
                }

                $updateSubtotal = OrderItems::where('order_id', $order->id)->get();
                $newSubtotalSum = $updateSubtotal->sum('subtotal');
                $order->totalamount = $newSubtotalSum;
                $order->update();

            } catch (\Exception $e) {
                // Handle any exceptions that may occur
                Log::error('Error creating or updating order item: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to create or update order item'], 500);
            }


        }


    }


    public function getcart(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->first()], 403);
        }
        try {
            $cart = Order::with('orderItemsDetails')->where('status', 'draft')->where('user_id', $request->user_id)->get();
            //    dd($cart); 

            $responseData = ['cart' => $cart];
            if ($cart->isEmpty()) {
                return response()->json(['error' => 'No data found'], 404);
            }
            return response()->json(['data' => $responseData, 'message' => 'Data fetched successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }


    public function remove(Request $request)
    {
// dd($request->all());
        $validator = Validator::make($request->all(), [

            'item_id' => 'required',
            'order_id' => 'required', 
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->first()], 403);
        }
        try {
            $removecart = Order::where('status', 'draft')->where('user_id', $request->user_id)->where('id', $request->order_id)->first();
            $removeCartItem = OrderItems::where('order_id', $removecart->id)->where('item_id', $request->item_id)->first();
         
            if (!$removeCartItem) {
                return response()->json(['error' => 'Item not found in the cart'], 404);
            } 


            $removeCartItem->delete();  
            // dd($removeCartItem);
            $updateSubtotal = OrderItems::where('order_id', $removecart->id)->get();
            $newSubtotalSum = $updateSubtotal->sum('subtotal');
            $removecart->totalamount = $newSubtotalSum;
            $removecart->update();

            $responseData = ['removecart' => $removecart];
        
            return response()->json(['data' => $responseData, 'message' => 'Data fetched successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching data: ' . $e->getMessage()); 
            //  dd($e);
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }  
    
    
    public function newOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'order_id' => 'required', 
            'status' => 'required', 
            
            
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()->first()], 403);
        }
        try {
            $existingOrder = Order::where('id', $request->order_id)->where('user_id',$request->user_id)->where('status', 'draft')->first();
            if ($existingOrder) {
                $existingOrder->update(['status' => $request->status]);   
                
             
    
                return response()->json(['success' => 'Order updated successfully'], 200);
            } else {
                return response()->json(['error' => 'No draft order found with the specified order ID and user ID'], 404);
            }
               
            
        } catch (\Exception $e) {
            Log::error('Error creating/updating order: ' . $e->getMessage()); 
            // dd($e);
            return response()->json(['error' => 'Failed to create/update order'], 500);
        }
    }  


}
