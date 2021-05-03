<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\APIException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductApiController extends Controller
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
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        return $this->service->getProducts();
    }

    public function show($productId)
    {
        return $this->service->getProductById($productId);
    }

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

        if (Gate::allows('user')) {
            abort(403, '權限錯誤');
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

        $product = Product::create($productForm);

        return response([
            'success' => true,
            'product' => $product,
        ], 200);
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
            abort(422, '驗證錯誤');
        }

        if (Gate::allows('user')) {
            abort(403, '權限錯誤');
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

        return response([
            'success' => $status,
            'message' => '產品已更新',
            'product' => $product,
        ], 200);
    }

    public function destroy($productId)
    {
        if (Gate::allows('user')) {
            abort(403, '權限錯誤');
        }

        if (! $product = Product::find($productId)) {
            abort(404);
        }

        if ($product['image'] !== '/natz/images/noimage.jpeg' && $product['image'] != null) {
            Storage::disk('s3')->delete($product['image']);
        }

        \Cart::session(auth()->id())->remove($productId);

        $status = $product->delete();
        return response([
            'success' => $status,
            'message' => '產品已刪除',
        ], 200);
    }
}
