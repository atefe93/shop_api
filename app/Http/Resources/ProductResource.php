<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'category_id' => $this->category_id,
            'primary_image' => url(env('PRODUCT_IMAGES_UPLOAD_PATH'). $this->primary_image),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'delivery_amount' => $this->delivery_amount,
            'images'=>ProductImageResource::collection($this->whenLoaded('images'))
        ];
    }
}
