<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return view(
            'user.profile',
            compact('user'),
        );
    }
}
