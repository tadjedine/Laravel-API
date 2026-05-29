<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class AddressService{

    public function getCustomerAddress(int $id_customer)
    {
        return Address::query()
            ->where('id_customer', $id_customer)
            ->where('active', 1)
            ->where('deleted', 0)
            ->get();
    }

    public function getAddress(int $id_address, int $id_customer): Address
    {
        return Address::query()
            ->where('id_customer', $id_customer)
            ->where('id_address', $id_address)
            ->where('active', 1)
            ->where('deleted', 0)
            ->firstOrFail();
    }

    public function createAddress(int $id_customer, array $data): Address
    {
        Customer::where('id_customer', $id_customer)->firstOrFail();

        $now = Carbon::now();

        $forcedValues = [
            'id_customer' => $id_customer,
            'active'      => 1,
            'deleted'     => 0,
            'date_add'    => $now,
            'date_upd'    => $now,
        ];

        $validData = array_merge($data, $forcedValues);

        return Address::create($validData);
    }

    public function updateAddress(int $addressId, int $customerId, array $data): Address
    {
        $address = Address::where('id_customer', $customerId)
            ->where('id_address', $addressId)
            ->where('active', 1)
            ->where('deleted', 0)
            ->firstOrFail();

        // Strip fields the user must never change via update
        $cleanedData = Arr::except($data, [
            'id_address',
            'id_customer',
            'id_manufacturer',
            'id_supplier',
            'id_warehouse',
            'active',
            'deleted',
            'date_add',
        ]);

        $cleanedData['date_upd'] = Carbon::now();

        $address->update($cleanedData);

        return $address->refresh();
    }

    public function deleteAddress(int $id_address, int $id_customer)
    {
        return Address::where("id_customer", $id_customer)
                    ->where("id_address", $id_address)
                    ->update(["deleted"=> 1]);
    }
}