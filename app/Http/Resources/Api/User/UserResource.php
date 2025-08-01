<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
        [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'company_name' => $this->company_name,
            'job_name' => $this->job_name,
            'value_added_certificate' => $this->value_added_certificate,
            'value_added_certificate_file' => $this->value_added_certificate_file,
            'city_id' => $this->city_id,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'points' => $this->points,
            'customer_type' => $this->customer_type,
            'commercial_register' => $this->commercial_register,
            'commercial_register_image' => $this->commercial_register_image ,
            'tax_number_image' => $this->   tax_number_image ,
            'tax_number' => $this->tax_number,
            'owner_name' => $this->owner_name,

        ];
    }
}
