<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'parent_id'=>$this->parent_id,
            'name'=>$this->name,
            'description'=>$this->description,
            'children'=>CategoryResource::collection($this->whenLoaded('children')),
            'parent'=> new CategoryResource($this->whenLoaded('parent')),
            'products' => ProductResource::collection($this->whenLoaded('products', function () {
                return $this->products->load('images');
            }))
        ];
    }
}
