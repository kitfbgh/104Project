<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ProductsOfOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [];
        foreach ($this->resource as $resource) {
            $result = [
                'id' => $resource->id,
                'name' => $resource->name,
                'quantity' => $resource->pivot('quantity'),
                'unit' => $resource->unit,
            ];
        }
        return $result;
    }
}
