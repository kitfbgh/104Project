<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    /**
    * @var Order
    */
    private $model;

    /**
    * OrderService constructor.
    * @param Order $model
    */
    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    /**
    * @param int $id
    * @return Order
    */
    public function getOrderById($id): Order
    {
        return $this->model->find($id);
    }

    public function getOrders()
    {
        return $this->model->all();
    }

    
}
