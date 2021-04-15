<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use Exception;

class OrderService
{
    /**
    * @var OrderRepository
    */
    private $repo;

    /**
    * ProductService constructor.
    * @param OrderRepository $repo
    */
    public function __construct(OrderRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
    * @param int $id
    * @return OrderResource
    */
    public function getOrderById($id): OrderResource
    {
        if (empty($id)) {
            throw new Exception('invalid order id');
        }
        if (! $order = $this->repo->getOrderById($id)) {
            throw new Exception('order not found');
        }

        return new OrderResource($order);
    }

    public function getOrders()
    {
        $orders = $this->repo->getOrders();

        $data = [];
        foreach ($orders as $order) {
            $data[] = new OrderResource($order);
        }

        return $data;
    }
}
