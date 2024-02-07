<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtController extends Controller
{
    // User Register (POST, formdata)
    public function register(Request $request){
        
        // data validation
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);
        
        if($validator->fails()){
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "errors" => $validator->errors()
            ]);
        }
        
        // User Model
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        // Response
        return response()->json([
            "status" => true,
            "message" => "User registered successfully"
        ]);
    }

    // User Login (POST, formdata)
    public function login(Request $request){
        
        // data validation
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ]);

        if($validator->fails()){
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "errors" => $validator->errors()
            ]);
        }

        // JWTAuth
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if(empty($token)){

            return response()->json([
                "status" => false,
                "message" => "Invalid details"
            ]);
        }

        $cookie = cookie('token', $token);
        return response("Successfully loggedin")->withCookie($cookie); 
    }

    // User Profile (GET)
    public function profile(){

        $userdata = auth()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userdata
        ]);
    } 

    // To generate refresh token value
    public function refreshToken(){
        
        $token = auth()->refresh();

        $cookie = cookie('token', $token);
        return response("Successfully loggedin")->withCookie($cookie); 

    }

    // User Logout (GET)
    public function logout(){
        
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "User logged out successfully"
        ]);
    }
}