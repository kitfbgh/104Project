<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
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
     * Show all Cart.
     *
     * @return view
     */
    public function index()
    {
        $cartItems = \Cart::session(auth()->id())->getContent();

        $productsOfCart = [];
        foreach ($cartItems as $item) {
            $productsOfCart[$item->id] = Product::find($item->id)->quantity;
        }

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
            'user.cart',
            compact('cartItems'),
            [
                'subTotal' => $subTotal,
                'total' => $total,
                'countOfProduct' => $productsOfCart,
            ],
        );
    }

    /**
    * Store the Cart.
    *
    * @param Product $product
    * @return view
    */
    public function add(Request $request, Product $product)
    {
        if ($product->quantity > 0) {
            // add the product to cart
            $data = \Cart::session(auth()->id())->add(array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'attributes' => array(
                    'imageUrl' => $product->imageUrl,
                    'image' => $product->image,
                    'unit' => $product->unit,
                    'size' => $request->get('size'),
                ),
                'associatedModel' => $product,
            ));
        }
        return redirect(route('cart'));
    }

    public function update($productId)
    {
        if (Product::find($productId)->quantity >= request('quantity')) {
            \Cart::session(auth()->id())->update($productId, [
                'quantity' => array(
                    'relative' => false,
                    'value' => request('quantity'),
                )
            ]);
        }
        return redirect(route('cart'));
    }

    /**
     * Delete the Cart item.
     *
     * @param $productId
     * @return view
     */
    public function destroy($productId)
    {
        \Cart::session(auth()->id())->remove($productId);

        return redirect(route('cart'));
    }
}
