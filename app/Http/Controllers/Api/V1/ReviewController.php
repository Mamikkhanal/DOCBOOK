<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ReviewEditRequest;
use App\Http\Requests\ReviewCreateRequest;

class ReviewController extends Controller
{
    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    /**
     * Get all reviews associated.
     */
    public function index()
    {
        try {
            $reviews = $this->reviewService->getReviewsForCurrentUser();
            if($reviews->isEmpty()){
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'No reviews found'
                    ],404
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Reviews fetched successfully',
                    'data' => $reviews
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'error' => $e->getMessage(),
                ],
                500
            );
        }
    }


    /**
     * Create a new review.
     */
    public function store(ReviewCreateRequest $request)
    {
        $data = $request->validated();
        try {
            $result = $this->reviewService->createReview($data);

            if (!$result) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Review creation failed'
                    ],
                    404
                );
            }
            return $result;
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'error' => $e->getMessage()
                ],
                400
            );
        }
    }

    /**
     * Display a specified review.
     */
    public function show(Review $review)
    {

        $result = $this->reviewService->getReviewById($review);

        if (!$result) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Review not found'
                ],
                404
            );
        }

        return $result;
        
    }

    /**
     * Update a specified review.
     */
    public function update(ReviewEditRequest $request, Review $review)
    {
        $data = $request->validated();
        try {
            $result = $this->reviewService->updateReview($review, $data);

            if (!$result) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Review update failed'
                    ],
                    404
                );
            }

            return $result;
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'error' => $e->getMessage()
                ],
                400
            );
        }
    }

    /**
     * Delete a specified review.
     */
    public function destroy(Review $review)
    {
        try {
            $result = $this->reviewService->deleteReview($review);

            if (!$result) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Review deletion failed'
                    ],
                    404
                );
            }

            return $result;
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'error' => $e->getMessage()
                ],
                400
            );
        }
    }
}
