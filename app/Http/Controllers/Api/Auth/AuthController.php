<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_USER,
        ]);

        return response()->json([
            'success' => true,
            'message' => '使用者註冊成功',
            'data' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($data)) {
                return response()->json([
                    'success' => false,
                    'message' => '登入驗證錯誤',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                    'success' => false,
                    'message' => '無法創建令牌',
                ], 500);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages
            ], 422);
        }

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => '使用者已登出'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => '抱歉，伺服器出現錯誤'
            ], 500);
        }
    }

    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user], 200);
    }
}
