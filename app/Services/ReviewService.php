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
            throw new \Exception('Review already exists for this appointment.');
        }
        
        $review = new Review($data);

        if (Gate::denies('create', $review)) {
            throw new \Exception('You are not authorized to create a review for this appointment.');
        }
    
        return $this->reviewRepository->createReview($data);

    }

    public function updateReview($review, $data)
    {
        if (Gate::denies('update', $review)) {
            throw new \Exception('You are not authorized to create a review for this appointment.');
        }
        return $this->reviewRepository->updateReview($review, $data);
    }

    public function deleteReview($review)
    {
        if (Gate::denies('delete', $review)) {
            throw new \Exception('You are not authorized to create a review for this appointment.');
        }
        $this->reviewRepository->deleteReview($review);
    }
}
