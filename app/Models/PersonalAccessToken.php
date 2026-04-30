<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * Force Sanctum tokens to always be stored in the main app database,
     * not the prestashop database (where the Customer model lives).
     */
    protected $connection = 'mysql';
}
