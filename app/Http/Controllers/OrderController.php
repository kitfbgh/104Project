<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderService $service)
    {
        $this->service = $service;
        $this->middleware('verified');
    }

    /**
     * Show all Orders.
     *
     * @return view
     */
    public function index()
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        $orders = Order::simplePaginate(10);
        return view(
            'order.index',
            compact('orders'),
            [
                'userId' => Auth::id(),
            ],
        );
    }

    /**
     * Show the OrderDetail.
     *
     * @param $orderId
     * @return view
     */
    public function orderDetail($orderId)
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        if (! $order = Order::find($orderId)) {
            abort(404);
        }
        $products = $order->products;

        return view(
            'order.orderDetail',
            compact('order'),
            compact('products'),
        );
    }

    /**
     * Show this Order.
     *
     * @return view
     */
    public function checkout()
    {
        $cartItems = \Cart::session(auth()->id())->getContent();

        $subTotal = \Cart::session(auth()->id())->getSubTotal();
        $taxCondition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'VAT 5%',
            'type' => 'tax',
            'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => '5%',
            'attributes' => array( // attributes field is optional
                'description' => 'Value added tax',
                'more_data' => 'more data here'
            )
        ));
        $total = $subTotal + $taxCondition->getCalculatedValue($subTotal);

        return view(
            'order.checkout',
            compact('cartItems'),
            [
                'subTotal' => $subTotal,
                'total' => $total,
                'userId' => Auth::id(),
            ],
        );
    }

    /**
    * Store the Order.
    *
    * @param OrderRequest $request
    * @return view
    */
    public function store(OrderRequest $request)
    {
        $cartItems = \Cart::session(auth()->id())->getContent();
        $subTotal = \Cart::session(auth()->id())->getSubTotal();
        $taxCondition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'VAT 5%',
            'type' => 'tax',
            'target' => 'subtotal', // this condition will be applied to cart's subtotal when getSubTotal() is called.
            'value' => '5%',
            'attributes' => array( // attributes field is optional
                'description' => 'Value added tax',
                'more_data' => 'more data here'
            )
        ));
        $total = $subTotal + $taxCondition->getCalculatedValue($subTotal);

        $orderForm = [
            'billing_email' => $request->get('email'),
            'billing_address' => trim($request->get('address')),
            'billing_phone' => $request->get('tel'),
            'billing_name' => $request->get('name'),
            'comment' => $request->get('comment'),
            'billing_subtotal' => (float) $subTotal,
            'billing_tax' => (float) ($total - $subTotal),
            'billing_total' => (float) $total,
            'status' => '訂單已送出',
            'user_id' => Auth::user()->id,
            'payment' => $request->get('payment'),
        ];

        $orderId = Order::create($orderForm)->id;

        foreach ($cartItems as $item) {
            $quantityOfProduct = Product::find($item->id)->quantity;
            Product::find($item->id)->update(['quantity' => $quantityOfProduct - $item->quantity]);
            Order::find($orderId)->products()->attach($item->id, [
                'quantity' => $item->quantity,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
        }
        \Cart::session(auth()->id())->clear();

        if (Auth::user()->role == 'user') {
            return redirect(route('user.orders', Auth::user()->id))->with('success', '訂單新增成功');
        }

        return redirect(route('orders'))->with('success', '訂單新增成功');
    }

    /**
     * Update the Order.
     *
     * @param Request $request, $orderId
     * @return view
     */
    public function update(Request $request, $orderId)
    {
        if (! $order = Order::find($orderId)) {
            abort(404);
        }

        $orderForm = [
            'status' => $request->get('status'),
        ];

        $status = $order->update($orderForm);

        if (Gate::allows('user')) {
            return redirect(route('user.order.detail', $orderId))->with('success', '訂單狀態已更新');
        }

        return redirect(route('orders.detail', $orderId))->with('success', '訂單狀態已更新');
    }

    /**
     * Delete the Order.
     *
     * @param $orderId
     * @return view
     */
    public function destroy($orderId)
    {
        if (! $order = Order::find($orderId)) {
            abort(404);
        }

        if ($order->status == '訂單已送出') {
            foreach ($order->products as $product) {
                $product->update(['quantity' => $product->quantity + $product->pivot->quantity]);
            }
        }
        $order->products()->detach();
        $status = $order->delete();
        return redirect(route('orders'))->with('delete', '訂單已刪除');
    }
}
