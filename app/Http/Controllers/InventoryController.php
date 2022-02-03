<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Inventory::latest()->paginate();
        return response([
            "data" => $data,
            "status" => 'ok',
            "success" => true,
            "message" => "success"
        ],Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //request validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric'
        ]);

        //send error message if validation fails
        if ($validator->fails()) {
            return response()->json([
                "status" => 'failed',
                "success" => false,
                'message' => $validator->errors()->all()], 400);
        }

        //store inventory
        $inventory = Inventory::create($request->all());

        return response()->json([
            'status' => 'ok',
            'success' => true,
            'message' => 'Inventory added successfully',
            'data' => $inventory
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    
        return response([
            "data" => Inventory::find($id),
            "status" => 'ok',
            "success" => true
        ],Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $inventory)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'price' => 'numeric',
            'quantity' => 'numeric'
        ]);

        //send error message if validation fails
        if ($validator->fails()) {
            return response()->json([
                "status" => 'failed',
                "success" => false,
                'message' => $validator->errors()->all()], 400);
        }

        //update fields that are provided
        $data = Inventory::where('id', $inventory)->update($request->except('id'));

        return response()->json([
            'status' => 'ok',
            'success' => true,
            'message' => 'Inventory updated successfully',
            'data' => Inventory::find($inventory)
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        
        return response()->json([
            'status' => 'ok',
            'success' => true,
            'message' => 'Inventory deleted successfully',
            'data' => []
        ], Response::HTTP_OK);
    }

}
