<?php

namespace App\Helpers;

use App\Responses\Response;
use Illuminate\Http\JsonResponse;

class ControllerHelper
{
    public function successJsonResponse(int $responseCode = 200, string $message = null, $item = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'item' => $item,
        ], $this->getResponseCode($responseCode));
    }

    public function errorJsonResponse(int $responseCode, string $error, string $message = null, $item = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $error,
            'message' => $message ?? $error,
            'item' => $item,
        ], $this->getResponseCode($responseCode));
    }

    private function getResponseCode(int $responseCode)
    {
        return $responseCode === 0
            ? Response::HTTP_BAD_REQUEST
            : $responseCode;
    }
}