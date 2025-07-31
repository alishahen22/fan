<?php

namespace App\Http\Resources\Api\User;

use App\Models\Service;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */


    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'product' => new ProductResources($this->product),
            'quantity_id' => $this->quantity,
            'quantity' => $this->quantityRelation?->quantity ?? null,
            'count' => $this->count,
            'price' => (int) $this->quantityRelation?->price ?? 0,
            'total_price' => (int) $this->price

        ];
    }


}
