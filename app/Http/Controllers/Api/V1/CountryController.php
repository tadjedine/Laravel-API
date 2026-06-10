<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    /**
     * List all active countries.
     */
    public function index(): JsonResponse
    {
        // For Prestashop, ps_country joined with ps_country_lang
        $countries = DB::table('ps_country as c')
            ->join('ps_country_lang as cl', 'c.id_country', '=', 'cl.id_country')
            ->where('c.active', 1)
            ->where('cl.id_lang', (int) config('prestashop.default_lang', 1))
            ->select('c.id_country', 'cl.name', 'c.iso_code', 'c.call_prefix')
            ->orderBy('cl.name')
            ->get();

        return response()->json([
            'data' => $countries->map(fn ($country) => [
                'id' => (int) $country->id_country,
                'name' => $country->name,
                'iso_code' => $country->iso_code,
                'call_prefix' => (int) $country->call_prefix,
            ]),
        ]);
    }
}
