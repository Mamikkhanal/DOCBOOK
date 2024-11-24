<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctor_id = Doctor::where("user_id", Auth::user()->id)->first()->id;

        $schedules = Schedule::where("doctor_id", $doctor_id)->get();

        return view("schedule.index", compact("schedules"));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $doctor_id = Doctor::where("user_id", Auth::user()->id)->first()->doctor_id;

        return view("schedule.create");
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $schedule = new Schedule();
        $schedule->doctor_id = Doctor::where("user_id", Auth::user()->id)->first()->id;
        $schedule->date = $request->date;
        if($request->start_time > $request->end_time || $request->start_time == $request->end_time){
            return redirect()->route("schedule.create")->withErrors('Start time must be before end time.');
        }
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->save();
        return redirect()->route("schedule.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::find($id);
        $doctor= Doctor::where('id',$schedule->doctor_id)->first();
        
        if($doctor->user_id == Auth::user()->id) {
        return view("schedule.show", compact("schedule"));
        }
        return redirect()->route("schedule.index");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = Schedule::find($id);
        $doctor= Doctor::where('id',$schedule->doctor_id)->first();

        if($doctor->user_id == Auth::user()->id) {
        return view("schedule.edit", compact("schedule"));
        }
        return redirect()->route("schedule.index");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $schedule = Schedule::find($id);
        $doctor= Doctor::where('id',$schedule->doctor_id)->first();

        if($doctor->user_id == Auth::user()->id) {
        $schedule->date = $request->date;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->update();
        }
        return redirect()->route("schedule.index");
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::find($id);
        $doctor= Doctor::where('id',$schedule->doctor_id)->first();

        if($doctor->user_id == Auth::user()->id) {
            $slots = Slot::where('schedule_id', $schedule->id)->get();
            foreach ($slots as $slot) {
                $appointment_id = $slot->appointment_id;
                $appointment = Appointment::find($appointment_id);
                $appointment->status = "cancelled";
                $appointment->save();
                $slot->delete();
            }
            $schedule->delete();
        }
        
        return redirect()->route("schedule.index");
    }

    // public function getschedules(string $id)
    // {
    //     $schedules = Schedule::with('slots')->where('doctor_id', $id)->get();

    //     foreach ($schedules as $schedule) {
    //         // Format the date field
    //         $schedule->date = Carbon::parse($schedule->date)->format('l, F j, Y');
            
    //         // Format the start time
    //         $schedule->start_time = Carbon::parse($schedule->start_time)->format('h:i A');
            
    //         // Format the end time
    //         $schedule->end_time = Carbon::parse($schedule->end_time)->format('h:i A');
    //     }
    

    //     return response()->json([
    //         'success' => true,
    //         'schedules' => $schedules,
    //     ]);
    // }

    public function getschedules(string $id)
{
    $schedules = Schedule::with('slots')->where('doctor_id', $id)->get();

    foreach ($schedules as $schedule) {
        // Format the schedule date and times
        $schedule->date = Carbon::parse($schedule->date)->format('l, F j, Y');
        $schedule->start_time = Carbon::parse($schedule->start_time)->format('h:i A');
        $schedule->end_time = Carbon::parse($schedule->end_time)->format('h:i A');

        // Format the slots' start and end times
        foreach ($schedule->slots as $slot) {
            $slot->start_time = Carbon::parse($slot->start_time)->format('h:i A');
            $slot->end_time = Carbon::parse($slot->end_time)->format('h:i A');
        }
    }

    return response()->json([
        'success' => true,
        'schedules' => $schedules,
    ]);
}

}