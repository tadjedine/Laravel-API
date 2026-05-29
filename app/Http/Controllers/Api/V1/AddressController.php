<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(private AddressService $addressService) {}

    /**
     * List all addresses for the authenticated customer.
     */
    public function index(Request $request)
    {
        $addresses = $this->addressService->getCustomerAddress(
            (int) $request->user()->id_customer
        );

        return AddressResource::collection($addresses);
    }

    /**
     * Get a specific address.
     */
    public function show(Request $request, int $id)
    {
        $address = $this->addressService->getAddress(
            $id,
            (int) $request->user()->id_customer
        );

        return new AddressResource($address);
    }

    /**
     * Create a new address for the authenticated customer.
     */
    public function store(StoreAddressRequest $request)
    {
        $address = $this->addressService->createAddress(
            $request->customerId(),
            $request->validated()
        );

        return (new AddressResource($address))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update an existing address.
     */
    public function update(StoreAddressRequest $request, int $id)
    {
        $address = $this->addressService->updateAddress(
            $id,
            $request->customerId(),
            $request->validated()
        );

        return new AddressResource($address);
    }

    /**
     * Soft-delete an address.
     */
    public function destroy(Request $request, int $id)
    {
        $this->addressService->deleteAddress(
            $id,
            (int) $request->user()->id_customer
        );

        return response()->json([
            'message' => 'Address deleted successfully.',
        ]);
    }
}
