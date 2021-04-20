<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        $orderTotal = count(Order::all());
        $orderCompleted = count(Order::all()->where('status', '訂單完成'));
        $productNum = count(Product::all());
        $userNum = count(User::all());
        return view(
            'home',
            [
                'productNum' => $productNum,
                'orderTotal' => $orderTotal,
                'orderCompleted' => $orderCompleted,
                'userNum' => $userNum,
            ],
        );
    }
}
