<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\APIException;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

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
    }

    public function index()
    {
        return $this->service->getProducts();
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
            'size' => $request->get('size'),
        ];

        if ($request->has('image')) {
            $fileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->image->extension();
            $imageName = time() . '.' . $extension;
            $path = 'images';
            $request->image->storeAs($path, $imageName, 'public');

            $productForm['image'] = $path . '/' . $imageName;
        }

        $product = Product::create($productForm);

        return response([
            'product' => $product,
            'success' => true,
        ], 200);
    }

    public function update(Request $request, $productId)
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
            throw new APIException('驗證錯誤', 422);
        }

        if (! $product = Product::find($productId)) {
            throw new APIException('查無產品', 404);
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
            'size' => $request->get('size'),
        ];

        if ($request->has('image')) {
            if ($product['image'] !== 'img/noimage.jpeg') {
                File::delete('storage/' . $product['image']);
            }
            $fileName = pathinfo($request->file('image')->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->image->extension();
            $imageName = time() . '.' . $extension;
            $path = 'images';
            $request->image->storeAs($path, $imageName, 'public');

            $productForm['image'] = $path . '/' . $imageName;
        }

        $status = $product->update($productForm);
        return ['message' => $status];
    }

    public function destroy($productId)
    {
        try {
            $product = Product::find($productId);
        } catch (\Exception $e) {
            throw new APIException('查無對應產品', 404);
        }

        \Cart::session(auth()->id())->remove($productId);

        $status = $product->delete();
        return ['message' => $status];
    }
}
