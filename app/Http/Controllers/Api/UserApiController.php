<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserApiController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admins = User::all();
        $users = [
            'name' => Auth::user()->name,
            'email' =>  Auth::user()->email,
            'password' =>  Auth::user()->password,
        ];
        if (Auth::user()->role === User::ROLE_ADMIN || Auth::user()->role === User::ROLE_MANAGER) {//是管理者，回傳所有會員資料
            return $this->sendResponse($admins->toArray(), 'Users retrieved successfully.');
        } else {//不是管理者，回傳該會員自己的資料
            return response([
                'user' => $users,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    //管理者註冊的function
    public function adminStore(Request $request)
    {
        try {
            $request->validate([ //這邊會驗證註冊的資料是否符合格式
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $apiToken = Str::random(10);
            $create = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'role' => User::ROLE_ADMIN,
                'api_token' => $apiToken,
            ]);

            if ($create) {
                return "Register as an admin. Your Token is $apiToken.";
            }
        } catch (Exception $e) {
            $this->sendError($e, 'Registered failed.', 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $apiToken = Str::random(10);
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => User::ROLE_USER,
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        if ($user) {
            return response([
                'user' => $user,
                'token' => $token,
            ], 201);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [ //修改會員資料一樣要驗證是否符合格式
            'name' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'unique:users'],
            'password' => ['string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = Auth::user();

        try {
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->save();
            return $this->sendResponse($user->toArray(), 'User updated successfully.');
        } catch (Exception $e) {
            return $this->sendError($e, 'Updated failed.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $users
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $users)
    {
        if (Auth::user()->role === User::ROLE_ADMIN) { //驗證是否為管理者
            if ($users->delete()) {
                return $this->sendResponse($users->toArray(), 'User deleted successfully.');
            }
        } else {
            return "You have no authority to delete";
        }
    }
}
