<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomesliderSlideResource;
use App\Models\HomesliderSlide;

class SlideController extends Controller
{
    /**
     * Return all active homepage slides, ordered by position.
     *
     * GET /api/v1/slides
     */
    public function index()
    {
        $slides = HomesliderSlide::active()
                    ->ordered()
                    ->with('lang')
                    ->get();

        return HomesliderSlideResource::collection($slides);
    }
}
