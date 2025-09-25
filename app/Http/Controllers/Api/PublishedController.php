<?php

namespace App\Http\Controllers\Api;

use App\Actions\Published\FindPublishedContentAction;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;

class PublishedController extends Controller
{
    use ApiResponse;

    /**
     * @var FindPublishedContentAction
     */
    protected $findPublishedContentAction;

    /**
     * PublishedController constructor.
     */
    public function __construct(FindPublishedContentAction $findPublishedContentAction)
    {
        $this->findPublishedContentAction = $findPublishedContentAction;
    }

    /**
     * Show published content by slug.
     */
    public function show(string $slug): \Illuminate\Http\JsonResponse
    {
        $result = $this->findPublishedContentAction->execute($slug);

        if (! $result) {
            return $this->errorResponse('Published content not found', 404);
        }

        return $this->successResponse($result['data'], $result['type']);
    }
}
