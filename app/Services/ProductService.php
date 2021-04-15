<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Exception;

class ProductService
{
    /**
    * @var ProductRepository
    */
    private $repo;

    /**
    * ProductService constructor.
    * @param ProductRepository $repo
    */
    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
    * @param int $id
    * @return ProductResource
    */
    public function getProductById($id): ProductResource
    {
        if (empty($id)) {
            throw new Exception('invalid product id');
        }
        if (! $product = $this->repo->getProductById($id)) {
            throw new Exception('product not found');
        }

        return new ProductResource($product);
    }

    public function getProducts()
    {
        $products = $this->repo->getProducts();

        foreach ($products as $product) {
            $data[] = new ProductResource($product);
        }

        return $data;
    }
}
