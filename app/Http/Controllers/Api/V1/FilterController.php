<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\FilterService;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function __construct(private FilterService $filterService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'category_slug']);

        $data = $this->filterService->getAvailableFilters($filters);

        return response()->json(['data' => $data]);
    }
}
