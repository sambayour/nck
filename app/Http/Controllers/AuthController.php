<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
    	//validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email:dns,rfc|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //send error message if validation fails
        if ($validator->fails()) {
            return response()->json([
                "status" => 'failed',
                "success" => false,
                'message' => $validator->errors()->all()], 400);
        }


        //Request is valid, create new user
        $user = User::create([
        	'first_name' => $request->first_name,
        	'last_name' => $request->last_name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User Created Successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    public function login(Request $request)
    {

        //validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:dns,rfc',
            'password' => 'required|string'
        ]);

        //send error message if validation fails
        if ($validator->fails()) {
            return response()->json([
                "status" => 'failed',
                "success" => false,
                'message' => $validator->messages()], 200);
        }

        $credentials = request(['email', 'password']);
        //try to authenticate
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	"status" => 'failed',
                    'success' => false,
                	'message' => 'User credentials are invalid.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                    "status" => 'failed',
                	'success' => false,
                	'message' => 'Something went wrong, please contact system administrator',
                ], 500);
        }
 	
 		//return token and user information
        return response()->json([
            'status' => 'ok',
            'success' => true,
            'data' => User::where('email', $request->email)->first(),
            'token' => $token,
        ]);
    }
 
    public function logout(Request $request)
    {
        //validation
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //send error message if validation fails
        if ($validator->fails()) {
            return response()->json([
                "status" => 'failed',
                "success" => false,
                'message' => $validator->errors()->all()], 400);
        }

		//destroy authentication token       
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'status' => 'ok',
                'success' => true,
                'message' => 'Logged out Successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'status' => 'failed',
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
 
    public function get_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        //send error message if validation fails
        if ($validator->fails()) {
            return response()->json([
            "status" => 'failed',
            "success" => false,
            'message' => $validator->errors()->all()], 400);
        }

 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json([
            'status' => 'ok',
            'success' => true,
            'data' => $user], Response::HTTP_OK);
    }

}
