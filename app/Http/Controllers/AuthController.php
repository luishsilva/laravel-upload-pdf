<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except'=>['login','register']]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        if($user){
            return response()->json([
                'message' => 'We are happy to register your account.',
                'user' => $user
            ], 201);
        }else{
            return response()->json([
                'message' => 'Sorry, something went wrong, it was not possible register your account.',
            ], 201);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        if(! $token = auth('api')->attempt($validator->validate())){
            return response()->json(['error'=>'Invalid credentials.'], 422);
        }

        return $this->createNewToken($token);
    }

    public function createNewToken($token){
        return response()->json([
            'token'  => $token,
            'token_type'    => 'bearer',
            'expires_in'    => auth('api')->factory()->getTTL() * 60,
            'name'          => auth('api')->user()->name,
        ]);
    }
}
