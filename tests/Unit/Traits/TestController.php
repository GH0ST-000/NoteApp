<?php

namespace Tests\Unit\Traits;

use App\Traits\ApiResponse;

class TestController
{
    use ApiResponse;

    /**
     * Return a success response.
     *
     * @param  mixed  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuccessResponse($data, ?string $type = null, int $statusCode = 200)
    {
        return $this->successResponse($data, $type, $statusCode);
    }

    /**
     * Return a success message response.
     *
     * @param  mixed|null  $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSuccessMessageResponse(string $message, $data = null, int $statusCode = 200)
    {
        return $this->successMessageResponse($message, $data, $statusCode);
    }

    /**
     * Return an error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getErrorResponse(string $message, int $statusCode)
    {
        return $this->errorResponse($message, $statusCode);
    }
}
