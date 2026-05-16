<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id_customer,
            'email'      => $this->email,
            'firstname'  => $this->firstname,
            'lastname'   => $this->lastname,
            'id_gender'  => $this->id_gender,
            'birthday'   => $this->birthday,
            'newsletter' => (bool) $this->newsletter,
            'is_guest'   => (bool) $this->is_guest,
            'created_at' => $this->date_add,
        ];
    }


}
