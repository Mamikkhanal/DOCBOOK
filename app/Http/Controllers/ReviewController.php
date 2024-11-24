<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Review;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = collect();
        if (Auth::user()->role==("admin")) 
        {
            $reviews = Review::all();
            $reviews = $reviews->sortByDesc("created_at");
        }
        elseif (Auth::user()->role=="doctor") 
        {
        $current_user = Doctor::where("user_id", Auth::user()->id)->first();
        $appointments = Appointment::where("doctor_id", $current_user->id)->get();
        
        $reviews = collect();

            foreach ($appointments as $appointment) {
                $appointmentReviews = Review::where("appointment_id", $appointment->id)->get();
                $reviews = $reviews->merge($appointmentReviews); // Merge the reviews
            }
            $reviews = $reviews->sortByDesc("created_at");
        }
        else{
        $current_user = Patient::where("user_id", Auth::user()->id)->first();
        $appointments = Appointment::where("patient_id", $current_user->id)->get();
        
        $reviews = collect();

            foreach ($appointments as $appointment) {
                $appointmentReviews = Review::where("appointment_id", $appointment->id)->get();
                $reviews = $reviews->merge($appointmentReviews); // Merge the reviews
            }
            $reviews = $reviews->sortByDesc("created_at");
    
        }
        return view('review.index', ['reviews' => $reviews]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create( Request $request)
    {
        $appointment = Appointment::findOrFail($request->appointment_id);
        $review = Review::where("appointment_id", $request->appointment_id)->first();
        if ($review) {
            return redirect()->route("dashboard")->withErrors('Review already exists for this appointment.');
        }
        return view("review.create", compact("appointment"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $review = new Review();
        $review->appointment_id = $request->appointment_id;
        $review->review = $request->review;
        $review->save();
        return redirect()->route("review.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        $appointment = Appointment::where('id', $review->appointment_id)->first();
        $patient = Patient::where('id', $appointment->patient_id)->first();
        if($patient->user_id == Auth::user()->id) {
        return view("review.edit", compact("review"));
        }
        return redirect()->route("review.index");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $patient_id = Appointment::where('appointment_id', $review->appointment_id)->first()->patient_id;
        $patient = Patient::where('id', $patient_id)->first();
        if($patient->user_id == Auth::user()->id) {
            $review->review = $request->review;
            $review->save();
        }
        return redirect()->route("review.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $patient_id = Appointment::where('appointment_id', $review->appointment_id)->first()->patient_id;
        $patient = Patient::where('id', $patient_id)->first();
        if($patient->user_id == Auth::user()->id) {
        $review->delete();
        }
        return redirect()->route("review.index");
    }
}
