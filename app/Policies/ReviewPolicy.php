<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Review;
use Auth;

class ReviewPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user, Review $review)
    {
        return $review->appointment->patient->user_id === Auth::user()->id;
    }

    public function show(User $user, Review $review)
    {
        return $review->appointment->patient->user_id === Auth::user()->id;
    }

    public function update(User $user, Review $review)
    {
        return $review->appointment->patient->user_id === Auth::user()->id;
    }

    public function delete(User $user, Review $review)
    {
        return $review->appointment->patient->user_id === Auth::user()->id;
    }

    
}
