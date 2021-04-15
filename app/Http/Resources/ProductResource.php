<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ProductResource extends JsonResource
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
            'category' => $this->category,
            'origin_price' => $this->origin_price,
            'price' => $this->price,
            'unit' => $this->unit,
            'description' => $this->description,
            'content' => $this->content,
            'quantity' => $this->quantity,
            'image' => $this->imageUrl ?? $this->image,
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
