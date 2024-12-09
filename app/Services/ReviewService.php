<?php

namespace App\Services;

use App\Models\Review;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Repositories\ReviewRepository;

class ReviewService
{
    /**
     * Create a new class instance.
     */
    protected $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getReviewsForCurrentUser()
    {
        $user = Auth::user();
        return $this->reviewRepository->getAllReviewsByRole($user, $user->role);
    }

    public function createReview($data)
    {
        $review = new Review();
        $review->appointment_id = $data['appointment_id'];
        $review->review = $data['review'];

        if (Gate::denies('create', $review)) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'You are not authorized to create a review for this appointment.'
                ],
                403
            );
        }

        $existingReview = $this->reviewRepository->findByAppointmentId($data['appointment_id']);

        if ($existingReview) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Review already exists for this appointment.'
                ],
                400
            );
        }

        $this->reviewRepository->createReview($data);
        return response ()->json(
            [
                'success' => true,
                'message' => 'Review created successfully.'
            ],201
        );
    }

    public function getReviewById($review)
    {
        if (Gate::denies('show', $review)) {

            return response ()->json(
                [
                    'success' => false,
                    'message' => 'You are not authorized to view this review.'
                ],
                403
            );
        }

        return $this->reviewRepository->findById($review->id);
    }

    public function updateReview($review, $data)
    {
        if (Gate::denies('update', $review)) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'You are not authorized to update this review.'
                ],
                403
            );
        }

        $this->reviewRepository->updateReview($review, $data);

        return response()->json(
            [
                'success' => true,
                'message' => 'Review updated successfully.'
            ],
            200
        );
    }

    public function deleteReview($review)
    {
        if (Gate::denies('delete', $review)) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'You are not authorized to delete this review.'
                ],
                403 
            );
        }
        
        $this->reviewRepository->deleteReview($review);

        return response()->json(
            [
                'success' => true,
                'message' => 'Review deleted successfully.'
            ],
            200
        );
    }
}
