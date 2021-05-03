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
        $this->middleware('auth:api');
    }

    /**
     * Show all Cart.
     *
     * @return
     */
    public function index()
    {
        return $this->service->getOrders();
    }

    public function orderDetail($orderId)
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        if (! $order = $this->service->getOrderById($orderId)) {
            abort(403, '查無訂單');
        }
        return response([
            'success' => true,
            'order' => $order,
        ], 200);
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
            $order = $this->service->getOrderById($orderId);
        } catch (Exception $e) {
            throw new APIException('找不到對應訂單', 404);
        }
        return response()->json([
            'succedd' => true,
            'order' => $order,
        ], 200);
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

        $order = Order::create($orderForm);

        $cartItems = \Cart::session(auth()->id())->getContent();
        foreach ($cartItems as $item) {
            $quantityOfProduct = Product::find($item->id)->quantity;
            Product::find($item->id)->update(['quantity' => $quantityOfProduct - $item->quantity]);
            $order->products()->attach($item->id, [
                'quantity' => $item->quantity,
                'created_at' => now(),
                'updated_at' => now(),
                ]);
        }
        \Cart::session(auth()->id())->clear();

        return response([
            'success' => true,
            'message' => '訂單已建立',
        ], 200);
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

        return response([
            'success' => true,
            'message' => '訂單狀態更新',
        ], 200);
    }

    public function destroy($orderId)
    {
        try {
            $order = $this->service->getOrderById($orderId);
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
        return response([
            'success' => true,
            'message' => '訂單已刪除',
        ], 200);
    }
}
