<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [];
        if($this->page_type  == 'home'){
            $data['image'] = $this->image;
        }
        $data['title'] = $this->title;
        $data['description'] = $this->description;
        $data['keywords'] = $this->keywords;
        $data['site_name'] = $this->site_name;
        return $data;
    }
}