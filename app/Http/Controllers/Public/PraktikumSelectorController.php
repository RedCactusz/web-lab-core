<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Public\PraktikumSelectorService;
use Illuminate\Http\JsonResponse;

class PraktikumSelectorController extends Controller
{
    public function __construct(
        private PraktikumSelectorService $service
    ) {}

    public function listPraktikum(): JsonResponse
    {
        return $this->successResponse($this->service->listPraktikum(), 'Praktikum list retrieved');
    }
}
