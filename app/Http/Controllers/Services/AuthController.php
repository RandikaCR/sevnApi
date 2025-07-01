<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'data' => $validator->errors()->all(),
            ]);
        }


        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
        ]);

        $response = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->createToken('MyApp')->accessToken,
        ];

        return response()->json([
            'status' => true,
            'message' => 'User Registered Successfully',
            'data' => $response,
        ]);
    }
}
