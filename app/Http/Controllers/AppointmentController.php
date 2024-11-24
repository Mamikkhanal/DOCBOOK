<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Schedule;

use App\Models\Appointment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->role == "patient") {
            $patient_id = Patient::where('user_id',Auth::user()->id)->first()->id;
            $appointments = Appointment::with(['patient.user', 'service'])
            ->orderBy("created_at", "desc")
            ->where('patient_id',$patient_id )
            ->get();
        } 
        elseif(Auth::user()->role == 'doctor') {
            $doctor_id = Doctor::where('user_id',Auth::user()->id)->first()->id;
            $appointments = Appointment::with(['doctor.user', 'service'])
            ->orderBy("created_at", "desc")
            ->where('doctor_id', $doctor_id )
            ->get();
        }
        else{
            $appointments = Appointment::all();
            $appointments = $appointments->sortByDesc("created_at");
        }

        return view('appointment.index',compact('appointments'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::with('user')->get();
        $services = Service::all();
        $schedules = Schedule::all();
        $slots = Slot::where('is_booked', false)->get();
        return view('appointment.create',compact('doctors','services','slots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'description' => 'required',
        ]);

        $patient_id = Patient::where('user_id',Auth::user()->id)->first()->id;
        $appointment = new Appointment();
        $appointment->patient_id = $patient_id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->service_id = $request->service_id;
        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->description = $request->description;

        $schedules = Schedule::where("doctor_id", $appointment->doctor_id)->get();

        if ($schedules->isEmpty()) {
            return redirect()->route('appointment.create')->withErrors('No schedule found for the doctor.');
            
        }
        else{
            foreach ($schedules as $schedule) {
                
                $scheduleDate = Carbon::parse($schedule->date);
                $appointmentDate = Carbon::parse($appointment->date);

                if ($appointmentDate->eq($scheduleDate)) {
        
                    $scheduleStartTime = Carbon::parse($schedule->start_time);
                    $scheduleEndTime = Carbon::parse($schedule->end_time);
                
                    $appointmentStartTime = Carbon::parse($appointment->start_time);
                    $appointmentEndTime = Carbon::parse($appointment->end_time);
                    // $appointmentStartTime =$appointment->start_time;
                    // $appointmentEndTime = $appointment->end_time;
                
                    if (!$appointmentStartTime->between($scheduleStartTime, $scheduleEndTime) ||
                        !$appointmentEndTime->between($scheduleStartTime, $scheduleEndTime)) {
                        return redirect()->route('appointment.create')->withErrors('Appointment time is out of schedule bounds.');
                    }
                    $slots = Slot::where('schedule_id', $schedule->id)->get();
            
                    if ($slots->isEmpty())
                    {
                        $appointment->save();
                        Slot::create([
                            'schedule_id' => $schedule->id,
                            'appointment_id' => $appointment->id,
                            'is_booked' => true,
                            'date' => $appointment->date,
                            'start_time' => $appointment->start_time,
                            'end_time' => $appointment->end_time,
                        ]);
                        return redirect('dashboard')->with('success', 'Appointment created successfully.');
                    
                    }

                    foreach ($slots as $slot) {
                        $slotStartTime = Carbon::parse($slot->start_time);
                        $slotEndTime = Carbon::parse($slot->end_time);

                        if (
                            $appointmentStartTime->between($slotStartTime, $slotEndTime) ||
                            $appointmentEndTime->between($slotStartTime, $slotEndTime) ||
                            $slotStartTime->between($appointmentStartTime, $appointmentEndTime) ||
                            $slotEndTime->between($appointmentStartTime, $appointmentEndTime) ||
                            $appointmentStartTime->eq($slotStartTime) || $appointmentEndTime->eq($slotEndTime)
                        ) {
                            return redirect()->route('appointment.create',compact("slots"))->withErrors('Slot is already booked.');
                        }
                        else{
    
                            $appointment->save();
                            Slot::create([
                                'schedule_id' => $schedule->id,
                                'appointment_id' => $appointment->id,
                                'is_booked' => true,
                                'date' => $appointment->date,
                                'start_time' => $appointment->start_time,
                                'end_time' => $appointment->end_time,
                            ]);
                            return redirect('dashboard')->with('success', 'Appointment created successfully.');
                        }
                    }
    
                }
                elseif (!$appointmentDate->eq($scheduleDate)) {
                    return redirect()->route('appointment.create')->withErrors('Appointment date does not match schedule date.');
                }
            }
    
            return redirect()->route('appointment.create')->withErrors('Unknown error occurred. without any conditions checked');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return view('appointment.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $appointment = Appointment::find($id);
        $doctor= Doctor::where('id',$appointment->doctor_id)->first();

        if($doctor->user_id == Auth::user()->id || Auth::user()->role == 'admin') {
            
            $doctors = Doctor::with('user')->get();
            $services = Service::all();
            return view('appointment.edit',compact('appointment' ,'doctors','services'));
        }

        return redirect()->route('dashboard');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);

        $doctor= Doctor::where('id',$appointment->doctor_id)->first();
        if($doctor->user_id == Auth::user()->id || Auth::user()->role == 'admin') {
            $appointment->date ;
            $appointment->start_time ;
            $appointment->end_time ;
            $appointment->status = $request->status;
            $appointment->description = $request->description;
            $appointment->save();
        }

        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::find($id);
        $doctor= Doctor::where('id',$appointment->doctor_id)->first();
        if($doctor->user_id == Auth::user()->id || Auth::user()->role == 'admin' && $appointment->status == 'pending' || 'cancelled') {
        $appointment->delete();
        }
        $patient= Patient::where('id',$appointment->patient_id)->first();
        if($patient->user_id == Auth::user()->id && $appointment->status == 'pending') {
            $appointment->delete();
        }
        return redirect()->route('dashboard');
    }

    public function dashboard(){

        if(Auth::user()->role == "patient") {
            $patient_id = Patient::where('user_id',Auth::user()->id)->first()->id;
            $appointments = Appointment::with(['patient.user', 'service'])
            ->where('patient_id',$patient_id )
            ->get();
        } 
        elseif(Auth::user()->role == 'doctor') {
            $doctor_id = Doctor::where('user_id',Auth::user()->id)->first()->id;
            $appointments = Appointment::with(['doctor.user', 'service'])
            ->where('doctor_id', $doctor_id )
            ->get();
        }
        else{
            $appointments = Appointment::all();
        }

        return view('dashboard',compact('appointments'));
    }
}