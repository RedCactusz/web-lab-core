<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successResponse($data = null, string $message = 'Success', int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
