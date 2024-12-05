<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\ReviewService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

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
            return response()->json(['data' => $reviews], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Create a new review.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'review' => 'required|string',
        ]);

        try {
            $review = $this->reviewService->createReview($validated);
            return response()->json(['data' => $review], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Display a specified review.
     */
    public function show(Review $review)
    {
        if (Gate::denies('show', $review)) {
            throw new \Exception('You are not authorized to create a review for this appointment.');
        }
        return response()->json(['data' => $review], 200);
    }

    /**
     * Update a specified review.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'review' => 'required|string',
        ]);

        try {
            $updatedReview = $this->reviewService->updateReview($review, $validated);
            return response()->json(['data' => $updatedReview], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Delete a specified review.
     */
    public function destroy(Review $review)
    {
        try {
            $this->reviewService->deleteReview($review);
            return response()->json(['message' => 'Review deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
