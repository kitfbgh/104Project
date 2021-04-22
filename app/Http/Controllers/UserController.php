<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verified');
    }

    /**
     * Show all the Product.
     *
     * @return view
     */
    public function index()
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        $users = User::simplePaginate(10);
        return view(
            'users',
            compact('users'),
        );
    }

    /**
     * Show User's Order.
     *
     * @param $userId
     * @return view
     */
    public function order($userId)
    {
        if (Auth::user()->id != $userId) {
            return redirect(route('welcome'));
        }
        $orders = User::find($userId)->orders;
        return view(
            'user.order',
            compact('orders'),
            [
                'userId' => Auth::id(),
            ],
        );
    }

    /**
     * Show the orderDetail.
     *
     * @param $orderId
     * @return view
     */
    public function orderDetail($orderId)
    {
        if (! $order = Order::find($orderId)) {
            abort(404);
        }
        if (! $products = $order->products) {
            abort(404);
        }

        return view(
            'user.orderDetail',
            compact('order'),
            compact('products'),
        );
    }

    /**
     * Show User Profile.
     *
     * @return view
     */
    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return view(
            'user.profile',
            compact('user'),
        );
    }

    /**
     * Show the ProductDetail.
     *
     * @param $productId
     * @return view
     */
    public function productDetail($productId)
    {
        if (! $product = Product::find($productId)) {
            abort(404);
        }
        return view(
            'user.productDetail',
            compact('product'),
        );
    }

    /**
     * Update the User.
     *
     * @param Request $request, $userId
     * @return view
     */
    public function update(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255|required',
            'new_password' => 'string|min:8|confirmed|nullable',
        ]);

        if ($validator->fails()) {
            abort(422, '驗證錯誤');
        }

        if (! $user = User::find($userId)) {
            abort(404);
        }

        $userForm = [
            'name' => $request->name,
            'password' => Hash::make($request->new_password) ?? $user->password,
        ];

        $status = $user->update($userForm);

        return redirect(route('user.profile'))->with('success', '使用者資訊成功更新');
    }

    /**
     * Delete the Product.
     *
     * @param $userId
     * @return view
     */
    public function destroy($userId)
    {
        if (! $user = User::find($userId)) {
            abort(404);
        }

        $status = $user->delete();

        return redirect(route('users'))->with('delete', '使用者已刪除');
    }
}
