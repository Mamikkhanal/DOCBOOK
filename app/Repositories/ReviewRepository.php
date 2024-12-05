<?php

namespace App\Repositories;

use App\Models\Review;
use App\Models\Appointment;

class ReviewRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function getAllReviewsByRole($user, $role)
    {
        if ($role === 'admin') {
            return Review::with('appointment')->orderByDesc('created_at')->get();
        } elseif ($role === 'doctor') {
            $appointments = Appointment::where('doctor_id', $user->doctor->id)->get();
        } elseif ($role === 'patient') {
            $appointments = Appointment::where('patient_id', $user->patient->id)->get();
        }

        return $appointments->flatMap(function ($appointment) {
            return Review::with('appointment')
                ->where('appointment_id', $appointment->id)
                ->get();
        })->sortByDesc('created_at');
    }

    public function findByAppointmentId($appointmentId)
    {
        return Review::where('appointment_id', $appointmentId)->first();
    }

    public function createReview(array $data)
    {
        return Review::create($data);
    }

    public function updateReview(Review $review, array $data)
    {
        return tap($review)->update($data);
    }

    public function deleteReview(Review $review)
    {
        $review->delete();
    }
}
