<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class PrestashopModel extends Model
{
    protected $connection= 'prestashop';
    public $timestamps = false;
}
