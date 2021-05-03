<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Register a user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError('註冊驗證錯誤', null, 422);
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => User::ROLE_USER,
        ]);

        return $this->sendResponse($user, '使用者註冊成功!', 200);
    }

    /**
     * Login a user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->sendError('登入驗證錯誤', null, 422);
        }

        $data = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($data)) {
                return $this->sendError('登入驗證錯誤', null, 400);
            }
        } catch (JWTException $e) {
            return $this->sendError('無法創建token', null, 500);
        }

        return $this->sendResponse($token, '登入成功！', 201);
    }

    /**
     * Logout a user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError('登出驗證錯誤', null, 422);
        }

        //Request is validated, do logout
        try {
            auth()->logout();

            return $this->sendResponse([], '使用者已登出', 200);
        } catch (JWTException $exception) {
            return $this->sendError('抱歉，伺服器出現錯誤', null, 500);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return $this->sendError('刷新驗證錯誤', null, 422);
        }

        //Request is validated, do logout
        try {
            $refreshToken = auth()->refresh();

            return $this->sendResponse($refreshToken, 'token已刷新', 200);
        } catch (JWTException $exception) {
            return $this->sendError('抱歉，伺服器出現錯誤', null, 500);
        }
    }
}
