<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = Auth::guard('user')->user();
        return
            [
                'id' => $this->id,
                'image' => $this->image,
                'title' => $this->title,
                'price' => $this->price,
                'price_original' => $this->price_original,
                'is_discount' => $this->discount > 0 ? true : false,
                'discount' => $this->discount,
                'description' => $this->description,
                'in_home' =>(bool) $this->in_home,
//                'is_favorite' => $user ? $user->favourites()->where('product_id', $this->id)->exists() : false,
            ];
    }
}
