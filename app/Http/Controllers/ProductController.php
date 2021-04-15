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
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
    * @var ProductService
    */
    private $service;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
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
     * Show all products.
     *
     * @return
     */
    public function index()
    {
        $products = $this->service->getProducts();
        return $products;
    }

    /**
    * Display the specified resource.
    *
    * @param int $productId
    * @return \Illuminate\Http\JsonResponse
    * @throws APIException
    * @throws \Exception
    */
    public function show($productId)
    {
        try {
            $product = Product::find($productId);
        } catch (Exception $e) {
            throw new APIException('找不到對應產品', 404);
        }
        return response()->json([
            'name' => $product['name'],
            'details' => $product['details'],
            'price' => $product['price'],
            'description' => $product['description'],
            'quantity' => $product['quantity'],
            'imgUrl' => $product['imgUrl'],
        ]);
    }

    /**
    * Store the Product.
    *
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    * @throws APIException
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
            throw new APIException('驗證錯誤', 422);
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
            'imageUrl' => $request->get('imageUrl') ?? null,
        ];

        if ($request->has('image')) {
            $fileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->image->extension();
            $imageName = time() . '.' . $extension;
            $path = 'img/';
            $request->image->move($path, $imageName);

            $productForm['image'] = $path . '/' . $imageName;
        }

        Product::create($productForm);

        return redirect(route('products'));
    }

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
            throw new APIException($e->getMessage(), 422);
        }

        if (! $product = Product::find($productId)) {
            throw new APIException('產品找不到', 404);
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
            'imageUrl' => $request->get('imageUrl') ?? null,
        ];

        if ($request->has('image')) {
            if ($product['image'] !== 'img/noimage.jpeg') {
                File::delete($product['image']);
            }
            $fileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->image->extension();
            $imageName = time() . '.' . $extension;
            $path = 'img/';
            $request->image->move($path, $imageName);

            $productForm['image'] = $path . '/' . $imageName;
        }

        $status = $product->update($productForm);
        return redirect(route('products'));
    }

    public function destroy($productId)
    {
        try {
            $product = Product::find($productId);
        } catch (Exception $e) {
            throw new APIException('找不到對應產品', 404);
        }

        $status = $product->delete();
        return redirect(route('products'));
    }
}
