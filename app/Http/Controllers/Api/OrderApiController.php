<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\APIException;
use App\Http\Controllers\Controller;
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
     * @var OrderService
     */
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     * Show all Cart.
     *
     * @return
     */
    public function index()
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        $orders = Order::all();
        return view(
            'order.index',
            compact('orders'),
            [
                'userId' => Auth::id(),
            ],
        );
    }

    public function orderDetail($orderId)
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        if (! $order = Order::find($orderId)) {
            abort(403, '查無訂單');
        }
        $products = $order->products;
        
        return view(
            'order.orderDetail',
            compact('order'),
            compact('products'),
        );
    }

    /**
     * Show all Cart.
     *
     * @return
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
    * Display the specified resource.
    *
    * @param int $orderId
    * @return \Illuminate\Http\JsonResponse
    * @throws APIException
    * @throws \Exception
    */
    public function show($orderId)
    {
        try {
            $order = Order::find($orderId);
        } catch (Exception $e) {
            throw new APIException('找不到對應訂單', 404);
        }
        return response()->json([
            'delivery_status' => $order['delivery_status'],
            'billing_email' => $order['billing_email'],
            'billing_address' => $order['billing_address'],
            'billing_city' => $order['billing_city'],
            'billing_province' => $order['billing_province'],
            'billing_country' => $order['billing_country'],
            'billing_postcode' => $order['billing_postcode'],
            'billing_phone' => $order['billing_phone'],
            'billing_subtotal' => $order['billing_subtotal'],
            'billing_tax' => $order['billing_tax'],
            'billing_total' => $order['billing_total'],
        ]);
    }

    /**
    * Store the Order.
    *
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    * @throws APIException
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:50',
            'name' => 'required|string|max:30',
            'tel' => 'required|string|min:10',
            'address' => 'required|string|max:60',
        ]);

        if ($validator->fails()) {
            //$messages = $validator->errors()->getMessages();
            throw new APIException('驗證錯誤', 422);
        }

        $orderForm = [
            'billing_email' => $request->get('email'),
            'billing_address' => trim($request->get('address')),
            'billing_phone' => $request->get('tel'),
            'billing_name' => $request->get('name'),
            'comment' => $request->get('comment'),
            'billing_subtotal' => (float) $request->get('subTotal'),
            'billing_tax' => (float) $request->get('tax'),
            'billing_total' => (float) $request->get('total'),
            'status' => $request->get('status'),
            'user_id' => $request->get('userId'),
        ];

        $orderId = Order::create($orderForm)->id;

        $cartItems = \Cart::session(auth()->id())->getContent();
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
            return redirect(route('user.orders', Auth::user()->id));
        }

        return redirect(route('orders'));
    }

    public function update(Request $request, $orderId)
    {
        if (! $order = Order::find($orderId)) {
            throw new APIException('訂單找不到', 404);
        }

        $orderForm = [
            'status' => $request->get('status'),
        ];

        $status = $order->update($orderForm);
        return redirect(route('orders'));
    }

    public function destroy($orderId)
    {
        try {
            $order = Order::find($orderId);
        } catch (Exception $e) {
            throw new APIException('找不到對應訂單', 404);
        }
        if ($order->status == '訂單已送出') {
            foreach ($order->products as $product) {
                $product->update(['quantity' => $product->quantity + $product->pivot->quantity]);
            }
        }
        $order->products()->detach();
        $status = $order->delete();
        return redirect(route('orders'));
    }
}
