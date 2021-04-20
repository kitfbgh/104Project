<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $this->middleware('auth');
    }

    public function index()
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        $users = User::all();
        return view(
            'users',
            compact('users'),
        );
    }

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

    public function orderDetail($orderId)
    {
        if (! $order = Order::find($orderId)) {
            abort(404, '查無訂單');
        }
        if (! $products = $order->products) {
            abort(404, '訂單無商品');
        }

        return view(
            'user.orderDetail',
            compact('order'),
            compact('products'),
        );
    }

    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return view(
            'user.profile',
            compact('user'),
        );
    }

    public function productDetail($productId)
    {
        if (! $product = Product::find($productId)) {
            abort(404, '查無產品');
        }
        return view(
            'user.productDetail',
            compact('product'),
        );
    }

    public function update(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
        ]);

        if (! $user = User::find($userId)) {
            abort(404, '查無使用者');
        }

        $userForm = [
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
        ];

        $status = $user->update($userForm);

        return redirect(route('user.profile'));
    }

    public function destroy($userId)
    {
        if (! $user = User::find($userId)) {
            abort(404, '查無使用者');
        }

        $status = $user->delete();

        return redirect(route('users'));
    }
}
