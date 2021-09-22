<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtUserController extends Controller
{
    public $token = true;
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), 
            [ 
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',  
        ]);  

        if ($validator->fails()) {  
            return response()->json(
                [
                    'status' => 0,
                    'message' => $validator->errors(),
                ], 400);
        }   
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        if ($this->token) {
            return $this->login($request);
        }
        return response()->json([
            'success' => true,
            'data' => $user
        ], Response::HTTP_OK);
    }
         
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }
}
