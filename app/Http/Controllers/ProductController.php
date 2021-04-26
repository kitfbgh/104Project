<?php

namespace App\Http\Controllers;

use App\Exceptions\APIException;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $service)
    {
        $this->middleware('verified');
    }

    /**
     * Show all products.
     *
     * @return view
     */
    public function page(Request $request)
    {
        if (Gate::allows('user')) {
            return redirect(route('welcome'));
        }

        $products = Product::simplePaginate(6);
        return view(
            'product.product',
            [
                'products' => $products,
                'userId' => Auth::id(),
            ]
        );
    }

    /**
     * Store the Product.
     *
     * @param Request $request
     * @return view
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'price' => 'required|string|max:10',
            'quantity' => 'required|string|min:1',
            'image' => 'mimes:jpeg,jpg,png,gif,svg|max:10000',
            'category' => 'required|string|max:30',
            'origin_price' => 'required|string|max:10',
            'unit' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            //$messages = $validator->errors()->getMessages();
            abort(422, '驗證錯誤');
        }

        $productForm = [
            'name' => $request->get('name'),
            'category' => $request->get('category') ?? '',
            'origin_price' => (float) $request->get('origin_price'),
            'price' => (float) $request->get('price'),
            'unit' => trim($request->get('unit')) ?? '',
            'description' => $request->get('description') ?? null,
            'content' => trim($request->get('content')) ?? '',
            'quantity' => intval($request->get('quantity')),
            'size' => $request->get('size'),
        ];

        if ($request->has('image')) {
            $fileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->image->extension();
            $imageName = time() . '.' . $extension;
            $path = 'natz/images/';
            Storage::disk('s3')->put($path . $imageName, file_get_contents($request->image));
            $s3_path = Storage::disk('s3')->url('natz/images/' . $imageName);

            $productForm['imageUrl'] = $s3_path;
            $productForm['image'] = parse_url($s3_path, PHP_URL_PATH);
        } elseif ($request->has('imageUrl')) {
            $productForm['imageUrl'] = $request->get('imageUrl');
            $productForm['image'] = null;
        }

        Product::create($productForm);

        return redirect(route('products'))->with('success', '產品新增成功');
    }

    /**
     * Update the Product.
     *
     * @param Request $request, $productId
     * @return view
     */
    public function update(Request $request, $productId)
    {
        try {
                $request->validate([
                    'name' => 'required|string|max:100',
                    'price' => 'required|string|max:10',
                    'quantity' => 'required|string|min:1',
                    'image' => 'mimes:jpeg,jpg,png,gif,svg|max:10000',
                    'category' => 'required|string|max:30',
                    'origin_price' => 'required|string|max:10',
                    'unit' => 'required|string|max:15',
                ]);
        } catch (\Exception $e) {
            abort(422, '驗證錯誤');
        }

        if (! $product = Product::find($productId)) {
            abort(404);
        }

        $productForm = [
            'name' => $request->get('name'),
            'category' => $request->get('category') ?? '',
            'origin_price' => (float) $request->get('origin_price'),
            'price' => (float) $request->get('price'),
            'unit' => trim($request->get('unit')) ?? '',
            'description' => $request->get('description') ?? null,
            'content' => trim($request->get('content')) ?? '',
            'quantity' => intval($request->get('quantity')),
            'size' => $request->get('size'),
        ];

        if ($request->has('image')) {
            if ($product['image'] !== '/natz/images/noimage.jpeg') {
                Storage::disk('s3')->delete($product['image']);
            }
            $fileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->image->extension();
            $imageName = time() . '.' . $extension;
            $path = 'natz/images/';
            Storage::disk('s3')->put($path . $imageName, file_get_contents($request->image));
            $s3_path = Storage::disk('s3')->url('natz/images/' . $imageName);

            $productForm['imageUrl'] = $s3_path;
            $productForm['image'] = parse_url($s3_path, PHP_URL_PATH);
        } elseif ($request->has('imageUrl')) {
            if (
                $product['image'] !== '/natz/images/noimage.jpeg'
                && $request->get('imageUrl') !== $product['imageUrl']
            ) {
                Storage::disk('s3')->delete($product['image']);
            }

            $productForm['imageUrl'] = $request->get('imageUrl');
            $productForm['image'] = null;
        }

        $status = $product->update($productForm);

        if ($item = \Cart::session(auth()->id())->get($productId)) {
            \Cart::session(auth()->id())->update($productId, array(
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'attributes' => array(
                    'imageUrl' => $product->imageUrl,
                    'image' => $product->image,
                    'unit' => $product->unit,
                    'size' => $request->get('size'),
                ),
            ));
        }

        return redirect(route('products'))->with('success', '產品更新成功');
    }

    /**
     * Delete the Product.
     *
     * @param $productId
     * @return view
     */
    public function destroy($productId)
    {
        if (! $product = Product::find($productId)) {
            abort(404);
        }

        if ($product['image'] !== '/natz/images/noimage.jpeg' && $product['image'] != null) {
            Storage::disk('s3')->delete($product['image']);
        }

        \Cart::session(auth()->id())->remove($productId);

        $status = $product->delete();
        return redirect(route('products'))->with('delete', '產品已刪除');
    }
}
