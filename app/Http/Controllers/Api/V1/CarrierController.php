<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CarrierController extends Controller
{
    /**
     * List all active carriers.
     */
    public function index(): JsonResponse
    {
        $carriers = DB::table('ps_carrier as c')
            ->join('ps_carrier_lang as cl', 'c.id_carrier', '=', 'cl.id_carrier')
            ->where('c.active', 1)
            ->where('c.deleted', 0)
            ->where('cl.id_shop', 1)
            ->where('cl.id_lang', (int) config('app.prestashop_lang', 1))
            ->select('c.id_carrier', 'c.name', 'c.is_free', 'cl.delay')
            ->get();

        return response()->json([
            'data' => $carriers->map(fn ($carrier) => [
                'id' => (int) $carrier->id_carrier,
                'name' => $carrier->name,
                'is_free' => (bool) $carrier->is_free,
                'delay' => $carrier->delay,
            ]),
        ]);
    }
}
