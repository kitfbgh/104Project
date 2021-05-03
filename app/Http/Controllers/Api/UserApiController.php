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
    * @var 
    */
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( $service)
    {
        $this->service = $service;
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     * 
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
        //是管理者，回傳所有會員資料
        if (Auth::user()->role === User::ROLE_ADMIN || Auth::user()->role === User::ROLE_MANAGER) {
            return $this->sendResponse($admins->toArray(), 'Users retrieved successfully.');
        } else {
            //不是管理者，回傳該會員自己的資料
            return $this->sendResponse($users, '獲取成功');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $input = $request->all();
        //修改會員資料一樣要驗證是否符合格式
        $validator = Validator::make($input, [
            'name' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'unique:users'],
            'password' => ['string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
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
            return $this->sendError('You have no authority to delete', null, 403);
        }
    }
}
