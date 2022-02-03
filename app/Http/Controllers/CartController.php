<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Inventory;
use Illuminate\Support\Str;
use App\Http\Resources\CartsResource;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
    $data = CartsResource::collection(Cart::all());
        return response([
            "data" => $data,
            "status" => 'ok',
            "success" => true,
            "message" => "success"
        ],Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "inventory" => ['array','required'],
	    	"quantity" => ['array','required'],
            "total_amount" => ['numeric'],
            "payment_status" => ['string'],
            "payment_method" => ['string'],
            "payment_ref" => ['string'],
        ]);

        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => $validator->errors()->all(),
                "data" => []
            ],Response::HTTP_OK);
        }

        foreach(array_combine($request->inventory, $request->quantity) as $inventory => $quantity){
        //check if the quantity of items instock can fufil order
            $soldOut = Inventory::where('quantity', '<', $quantity)->where('id',$inventory)->first(); 

            if($soldOut){
                return response([
                    "status" => 'failed',
                    "success" => false,
                    "message" => $soldOut->name.' current stock cannot fufil your order.',
                    "data" => []
                ],Response::HTTP_OK);
            }                             

        }

        //generate random string and save or order table 
        $order = Cart::create([
            "total_amount" => $request->total_amount,
            "payment_status" => $request->payment_status,
            "payment_method" => $request->payment_method,
            "payment_ref" => $request->payment_ref,
            'user_id'=> Auth::user()->id,
            'order_ref' => Str::random(20)
        ]);
        //get the order id from Carts table and save to order_items table for reference
        foreach(array_combine($request->inventory, $request->quantity) as $inventory => $quantity){
            //check if the quantity of items instock can fufil order
            $record = [
                'inventory' => $inventory,
                'quantity' => $quantity,
                'cart_id' => $order->id               
            ];
            //save item singularly in the cart_items table
            $data = CartItem::create($record);
            
            //update the count from the current item quantity
            Inventory::where('id', $inventory)->decrement('quantity', $quantity);
        }

        return response([
            "status" => 'ok',
            "success" => true,
            "message" => "Cart successfully added",
            "data" => $order
        ],Response::HTTP_CREATED);

    }


    public function update(Request $request, $order)
    {
        $validator = Validator::make($request->all(), [
	    	"status_id" => ['numeric'],
            "total_amount" => ['numeric'],
            "payment_status" => ['string'],
            "payment_method" => ['string'],
            "payment_ref" => ['string'],
        ]);

        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => $validator->errors()->all(),
                "data" => []
            ],Response::HTTP_OK);
        }

        $order_data = Cart::find($order);
        $order_data->fill($request->except('id'))->save(); 
                         
        return response([
            "data" => new CartsResource($order_data),
            "status" => 'ok',
            "success" => true,
            "message" => "Order successfully Updated"
        ],Response::HTTP_CREATED);
    }

    /**
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response([
            "data" => [],
            "status" => 'ok',
            "success" => true,
            "message" => "Order successfully deleted"
        ],Response::HTTP_OK);
    
    }
}
