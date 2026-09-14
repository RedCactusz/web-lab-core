<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Praktikum;
use Illuminate\Http\JsonResponse;

class PraktikumController extends Controller
{
    public function index(): JsonResponse
    {
        $product = Praktikum::all();
        return response()->json($product, 200);
    }
}
