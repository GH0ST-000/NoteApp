<?php

namespace Tests\Unit\Traits;

use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class ApiResponseTest extends TestCase
{
    use ApiResponse;

    /**
     * Test the successResponse method.
     */
    public function test_success_response_returns_correct_structure(): void
    {
        // Test with basic data
        $data = ['name' => 'Test User', 'email' => 'test@example.com'];
        $response = $this->successResponse($data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($data, $responseData['data']);
        $this->assertArrayNotHasKey('type', $responseData);
    }

    /**
     * Test the successResponse method with type parameter.
     */
    public function test_success_response_with_type_returns_correct_structure(): void
    {
        // Test with type parameter
        $data = ['name' => 'Test User', 'email' => 'test@example.com'];
        $response = $this->successResponse($data, 'user');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($data, $responseData['data']);
        $this->assertEquals('user', $responseData['type']);
    }

    /**
     * Test the successResponse method with custom status code.
     */
    public function test_success_response_with_custom_status_code(): void
    {
        // Test with custom status code
        $data = ['name' => 'Test User', 'email' => 'test@example.com'];
        $response = $this->successResponse($data, null, 201);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($data, $responseData['data']);
    }

    /**
     * Test the successMessageResponse method.
     */
    public function test_success_message_response_returns_correct_structure(): void
    {
        // Test with message only
        $message = 'Operation completed successfully';
        $response = $this->successMessageResponse($message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($message, $responseData['message']);
        $this->assertArrayNotHasKey('data', $responseData);
    }

    /**
     * Test the successMessageResponse method with data.
     */
    public function test_success_message_response_with_data_returns_correct_structure(): void
    {
        // Test with message and data
        $message = 'Operation completed successfully';
        $data = ['id' => 1, 'status' => 'completed'];
        $response = $this->successMessageResponse($message, $data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($message, $responseData['message']);
        $this->assertEquals($data, $responseData['data']);
    }

    /**
     * Test the successMessageResponse method with custom status code.
     */
    public function test_success_message_response_with_custom_status_code(): void
    {
        // Test with custom status code
        $message = 'Resource created successfully';
        $data = ['id' => 1];
        $response = $this->successMessageResponse($message, $data, 201);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($message, $responseData['message']);
        $this->assertEquals($data, $responseData['data']);
    }

    /**
     * Test the errorResponse method.
     */
    public function test_error_response_returns_correct_structure(): void
    {
        // Test with error message
        $message = 'Resource not found';
        $response = $this->errorResponse($message, 404);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
        $this->assertEquals($message, $responseData['message']);
    }

    /**
     * Test the errorResponse method with different status codes.
     */
    public function test_error_response_with_different_status_codes(): void
    {
        // Test with 400 Bad Request
        $response = $this->errorResponse('Bad request', 400);
        $this->assertEquals(400, $response->getStatusCode());

        // Test with 403 Forbidden
        $response = $this->errorResponse('Forbidden', 403);
        $this->assertEquals(403, $response->getStatusCode());

        // Test with 500 Internal Server Error
        $response = $this->errorResponse('Server error', 500);
        $this->assertEquals(500, $response->getStatusCode());
    }
}
