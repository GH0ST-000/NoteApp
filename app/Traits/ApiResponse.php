<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Return a success response with data.
     *
     * @param  mixed  $data
     */
    protected function successResponse($data, ?string $type = null, int $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
        ];

        if ($type !== null) {
            $response['type'] = $type;
        }

        $response['data'] = $data;

        return response()->json($response, $statusCode);
    }

    /**
     * Return a success response with a message and optional data.
     *
     * @param  mixed|null  $data
     */
    protected function successMessageResponse(string $message, $data = null, int $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error response.
     */
    protected function errorResponse(string $message, int $statusCode): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
