<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function create(Appointment $appointment)
    {
        // $schedules = Schedule::where("doctor_id", $appointment->doctor_id)->get();

        // if ($schedules->isEmpty()) {
        //     return redirect()->route('appointment.create')->withErrors('No schedule found for the doctor.');
            
        // }
        
        // foreach ($schedules as $schedule) {
            
        //     $scheduleDate = Carbon::parse($schedule->date);
        //     $appointmentDate = Carbon::parse($appointment->date);

        //     if ($appointmentDate->eq($scheduleDate)) {
            
        //         $scheduleStartTime = Carbon::parse($schedule->start_time);
        //         $scheduleEndTime = Carbon::parse($schedule->end_time);
            
        //         $appointmentStartTime = Carbon::parse($appointment->start_time);
        //         $appointmentEndTime = Carbon::parse($appointment->end_time);
            
        //         if (!$appointmentStartTime->between($scheduleStartTime, $scheduleEndTime) ||
        //             !$appointmentEndTime->between($scheduleStartTime, $scheduleEndTime)) {
        //             return redirect()->route('appointment.create')->withErrors('Appointment time is out of schedule bounds.');
        //         }

        //         $slots = Slot::where('schedule_id', $schedule->id)->get();
        //         foreach ($slots as $slot) {
        //             $slotStartTime = Carbon::parse($slot->start_time);
        //             $slotEndTime = Carbon::parse($slot->end_time);
            
        //             // Check for overlap
        //             if (
        //                 $appointmentStartTime->between($slotStartTime, $slotEndTime, false) ||
        //                 $appointmentEndTime->between($slotStartTime, $slotEndTime, false) ||
        //                 $slotStartTime->between($appointmentStartTime, $appointmentEndTime, false) ||
        //                 $slotEndTime->between($appointmentStartTime, $appointmentEndTime, false) ||
        //                 $appointmentStartTime->eq($slotStartTime) || $appointmentEndTime->eq($slotEndTime)
        //             ) {
        //                 return redirect()->route('appointment.create')->withErrors('Appointment time conflicts with an existing slot.');
        //             }
        //             else{

        //                 Slot::create([
        //                     'schedule_id' => $schedule->id,
        //                     'appointment_id' => $appointment->id,
        //                     'is_booked' => true,
        //                     'date' => $appointment->date,
        //                     'start_time' => $appointment->start_time,
        //                     'end_time' => $appointment->end_time,
        //                 ]);
        //                 return redirect('dashboard')->with('success', 'Appointment created successfully.');
        //             }
        //         }

        //     }
        //     elseif (!$appointmentDate->eq($scheduleDate)) {
        //         return redirect()->route('appointment.create')->withErrors('Appointment date does not match schedule date.');
        //     }
        // }

    }


    /**
     * Show the form for creating a new resource.
     */
    // public function create(Appointment $appointment)
    // {
    //     $schedule= Schedule::where("doctor_id", $appointment->doctor_id)->first();
    //     $slots = Slot::where('schedule_id', $schedule->id)->get();

    //     $scheduleStartTime = Carbon::createFromFormat('H:i', $schedule->start_time);
    //     $scheduleEndTime = Carbon::createFromFormat('H:i', $schedule->end_time);

    //     $appointmentStartTime = Carbon::createFromFormat('H:i', $appointment->start_time);
    //     $appointmentEndTime = Carbon::createFromFormat('H:i', $appointment->end_time);


    //     if (Carbon::parse($appointment->date)->eq(Carbon::parse($schedule->date))) {

    //         $scheduleStartTime = Carbon::parse($schedule->start_time);
    //         $scheduleEndTime = Carbon::parse($schedule->end_time);

    //         // if(
    //         //         Carbon::parse($appointment->start_time)->between($scheduleStartTime, $scheduleEndTime) &&
    //         //         Carbon::parse($appointment->end_time)->between($scheduleStartTime, $scheduleEndTime)
    //         //         )
    //         //     {
    //         //         foreach($slots as $slot){
    //         //         if ($appointment->start_time == $slot->start_time && $appointment->end_time == $slot->end_time) {
    //         //             return redirect()->route("appointment.create");
    //         //         }
    //         //         elseif(
    //         //             Carbon::parse($appointment->start_time)->between($slot->start_time , $slot->end_time  &&
    //         //             Carbon::parse($appointment->end_time)->between($slot->start_time , $slot->end_time ))
    //         //         )
    //         //         {
    //         //             return redirect()->route("appointment.create");
    //         //         }
    //         //         elseif(
    //         //             Carbon::parse($appointment->start_time)->between($slot->start_time , $slot->end_time  ||
    //         //             Carbon::parse($appointment->end_time)->between($slot->start_time , $slot->end_time ))
    //         //         )
    //         //         {
    //         //             return redirect()->route("appointment.create");
    //         //         }
    //         //         elseif(
    //         //             Carbon::parse($slot->start_time)->between($appointment->start_time , $appointment->end_time  &&
    //         //             Carbon::parse($slot->end_time)->between($appointment->start_time , $appointment->end_time ))
    //         //         )
    //         //         {
    //         //             return redirect()->route("appointment.create");
    //         //         }
    //         //         else{
    //         //             $slot = new Slot();
    //         //             $slot->schedule_id = $schedule->id;
    //         //             $slot->appointment_id = $appointment->id;
    //         //             $slot->is_booked = true;
    //         //             $slot->date = $appointment->date;
    //         //             $slot->start_time = $appointment->start_time;
    //         //             $slot->end_time = $appointment->end_time;
    //         //             $slot->save();
    //         //             return redirect("dashboard");
    //         //         }

    //         //     }
    //         // }
    //         // else
    //         // {
    //         //         return redirect()->route("appointment.create");
    //         // }
            
    //     }

    //     return redirect()->route('appointment.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Slot $slot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slot $slot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slot $slot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slot $slot)
    {
        //
    }

    public function getSlots(Request $request)
    {
        $date = $request->input('date');
        dd($date);
        // Fetch available slots based on the selected date
        $slots = Slot::where('date', $date)->get();
    
        return response()->json([
            'success' => true,
            'slots' => $slots,
        ]);
    }
}
