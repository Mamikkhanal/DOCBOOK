<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\get;

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
            ->where('patient_id',$patient_id )
            ->get();
        } 
        elseif(Auth::user()->role == 'doctor') {
            $doctor_id = Doctor::where('user_id',Auth::user()->id)->first()->id;
            $appointments = Appointment::with(['doctor.user', 'service'])
            ->where('doctor_id', $doctor_id )
            ->get();
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
        return view('appointment.create',compact('doctors','services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $patient_id = Patient::where('user_id',Auth::user()->id)->first()->id;
        $appointment = new Appointment();
        $appointment->patient_id = $patient_id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->service_id = $request->service_id;
        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->description = $request->description;
        $appointment->save();
        return redirect()->route('dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        if (Auth::user()->role == 'patient') {
            $patient_id = Patient::where('user_id',Auth::user()->id)->first()->id;
            $appointments = Appointment::where('patient_id', $patient_id)->with('doctor', 'service')->get();
        } 
        else {
            $doctor_id = Doctor::where('user_id',Auth::user()->id)->first()->id;
            $appointments = Appointment::where('doctor_id', $doctor_id)->with('patient', 'service')->get();
        }
    
        return view('appointment.show', compact('appointments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $doctors = Doctor::with('user')->get();
        $services = Service::all();
        return view('appointment.edit',compact('appointment' ,'doctors','services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        $patient_id = $appointment->patient_id;
        $appointment->patient_id = $patient_id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->service_id = $request->service_id;
        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->status = $request->status;
        $appointment->description = $request->description;
        $appointment->save();
        return redirect()->route('dashboard');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        $appointment->delete();
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

        return view('dashboard',compact('appointments'));
    }
}
