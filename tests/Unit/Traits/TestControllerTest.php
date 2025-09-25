<?php

namespace Tests\Unit\Traits;

use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class TestControllerTest extends TestCase
{
    /**
     * @var TestController
     */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new TestController;
    }

    /**
     * Test the getSuccessResponse method.
     */
    public function test_get_success_response_returns_correct_structure(): void
    {
        // Test with basic data
        $data = ['name' => 'Test User', 'email' => 'test@example.com'];
        $response = $this->controller->getSuccessResponse($data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($data, $responseData['data']);
        $this->assertArrayNotHasKey('type', $responseData);
    }

    /**
     * Test the getSuccessResponse method with type parameter.
     */
    public function test_get_success_response_with_type_returns_correct_structure(): void
    {
        // Test with type parameter
        $data = ['name' => 'Test User', 'email' => 'test@example.com'];
        $response = $this->controller->getSuccessResponse($data, 'user');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($data, $responseData['data']);
        $this->assertEquals('user', $responseData['type']);
    }

    /**
     * Test the getSuccessMessageResponse method.
     */
    public function test_get_success_message_response_returns_correct_structure(): void
    {
        // Test with message only
        $message = 'Operation completed successfully';
        $response = $this->controller->getSuccessMessageResponse($message);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($message, $responseData['message']);
        $this->assertArrayNotHasKey('data', $responseData);
    }

    /**
     * Test the getSuccessMessageResponse method with data.
     */
    public function test_get_success_message_response_with_data_returns_correct_structure(): void
    {
        // Test with message and data
        $message = 'Operation completed successfully';
        $data = ['id' => 1, 'status' => 'completed'];
        $response = $this->controller->getSuccessMessageResponse($message, $data);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertTrue($responseData['success']);
        $this->assertEquals($message, $responseData['message']);
        $this->assertEquals($data, $responseData['data']);
    }

    /**
     * Test the getErrorResponse method.
     */
    public function test_get_error_response_returns_correct_structure(): void
    {
        // Test with error message
        $message = 'Resource not found';
        $response = $this->controller->getErrorResponse($message, 404);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertFalse($responseData['success']);
        $this->assertEquals($message, $responseData['message']);
    }
}
