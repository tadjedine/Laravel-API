<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => (int) $this->id_address,
            'alias'         => $this->alias,
            'firstname'     => $this->firstname,
            'lastname'      => $this->lastname,
            'company'       => $this->company,
            'address1'      => $this->address1,
            'address2'      => $this->address2,
            'postcode'      => $this->postcode,
            'city'          => $this->city,
            'id_country'    => (int) $this->id_country,
            'id_state'      => $this->id_state ? (int) $this->id_state : null,
            'phone'         => $this->phone,
            'phone_mobile'  => $this->phone_mobile,
            'vat_number'    => $this->vat_number,
            'dni'           => $this->dni,
            'other'         => $this->other,
            'created_at'    => $this->date_add?->toDateTimeString(),
            'updated_at'    => $this->date_upd?->toDateTimeString(),
        ];
    }
}
