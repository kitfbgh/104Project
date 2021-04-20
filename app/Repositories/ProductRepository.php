<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    /**
    * @var Product
    */
    private $model;

    /**
    * ProductService constructor.
    * @param Product $model
    */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
    * @param int $id
    * @return Product
    */
    public function getProductById($id): Product
    {
        return $this->model->find($id);
    }

    public function getProducts()
    {
        return $this->model->all();
    }
}
