<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'status' => $this->status,
            'email' => $this->billing_email,
            'purchase_time' => $this->created_at,
            'price' => $this->price,
            'comment' => $this->comment,
            'products' => new ProductsOfOrderResource($this->products),
        ];
    }

    /**
    * @param \Illuminate\Http\Request $request
    * @return array
    */
    public function with($request)
    {
        return [
            'metadata' => Carbon::now(),
        ];
    }
}
