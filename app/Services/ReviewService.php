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

        $review = new Review($data);

        if (Gate::denies('create', $review)) {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'You are not authorized to create a review for this appointment.'
                ],
                403
            );
        }
        return $this->reviewRepository->createReview($data);
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

        return  $this->reviewRepository->updateReview($review, $data);
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
        
        return $this->reviewRepository->deleteReview($review);
    }
}
