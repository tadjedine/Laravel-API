<?php

namespace App\Services\Auth;

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class PrestashopPasswordVerifierService
{
    public function __construct(){}
    public function verify(Customer $customer, string $plain):bool
    {
        $stored = $customer->getAuthPassword();

        //  Modern bcrypt
        if (str_starts_with($stored, '$2y$') || str_starts_with($stored, '$argon')) {
            return Hash::check($plain, $stored);
        }

        return false;
    }
}
