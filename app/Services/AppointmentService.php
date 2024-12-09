<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Models\Slot;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Mail\AppointmentBooked;
use App\Services\PaymentService;
use App\Mail\AppointmentCancelled;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\AppointmentCreateRequest;

class AppointmentService
{
    /**
     * Create a new class instance.
     */
    private $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }


    public function searchAppointments($request)
    {
        $query = Appointment::with(['doctor.user', 'patient.user', 'service', 'payment']);

        if (Auth::user()->role == 'patient') {

            $patient_id = Patient::where('user_id', Auth::id())->first()->id;
            $query->where('patient_id', $patient_id);

        } elseif (Auth::user()->role == 'doctor') {

            $doctor_id = Doctor::where('user_id', Auth::id())->first()->id;
            $query->where('doctor_id', $doctor_id);
        }

        if ($request->has('search') && $request->search != '') {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('doctor.user', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('patient.user', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('service', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $appointments = $query->orderBy('id', 'desc')->get();

        return $appointments;
    }

    public function getAllAppointments()
    {
        if (Auth::user()->role == 'admin') {

            $appointments = Appointment::with('patient', 'doctor', 'service')->get();

            return $appointments;

        } elseif (Auth::user()->role == 'doctor') {

            $doctor = Doctor::where('user_id', Auth::user()->id)->first();

            $appointments = Appointment::where('doctor_id', $doctor->id)->with('patient', 'doctor', 'service')->get();

            return $appointments;

        } elseif (Auth::user()->role == 'patient') {

            $patient = Patient::where('user_id', Auth::user()->id)->first();

            $appointments = Appointment::where('patient_id', $patient->id)->with('patient', 'doctor', 'service')->get();

            return $appointments;

        } else {

            return response()->json([
                'success' => false,
                'message' => 'You are not authenticated'
            ], 404);
        }
    }

    public function createAppointment($request)
    {

        $patient_id = Patient::where('user_id', Auth::user()->id)->first()->id;
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

            return([
                'success' => false,
                'message' => 'No schedule found for the doctor.',
            ]);

        } else {

            $appointmentDate = Carbon::parse($appointment->date);
            $currentDate = Carbon::now()->startOfDay();

            if ($appointmentDate < $currentDate) {

                return response()->json([
                    'success' => false,
                    'message' => 'The appointment date must be today or a future date.',
                ], 422);
            }
            
            foreach ($schedules as $schedule) {

                $scheduleDate = Carbon::parse($schedule->date);
                $appointmentDate = Carbon::parse($appointment->date);

                if ($appointmentDate->eq($scheduleDate)) {

                    $scheduleStartTime = Carbon::parse($schedule->start_time);
                    $scheduleEndTime = Carbon::parse($schedule->end_time);

                    $appointmentStartTime = Carbon::parse($appointment->start_time);
                    $appointmentEndTime = Carbon::parse($appointment->end_time);

                    if ($appointmentStartTime < Carbon::now() || $appointmentEndTime < Carbon::now()) {

                        return response()->json([
                            'success' => false,
                            'message' => 'Appointment time must be after the current time.',
                        ], 422);
                    }

                    if ($appointmentStartTime > $appointmentEndTime) {

                        return response()->json([
                            'success' => false,
                            'message' => 'The start time must be before the end time.',
                        ], 422);
                    }

                    if (
                        !$appointmentStartTime->between($scheduleStartTime, $scheduleEndTime) ||
                        !$appointmentEndTime->between($scheduleStartTime, $scheduleEndTime)
                    ) {

                        $count = 0;
                        foreach ($schedules as $schedule) {
                            if ($scheduleDate->eq($schedule->date)) {
                                $count = $count + 1;
                            }
                        }
                        if ($count > 1) {
                            continue;
                        }

                        return response()->json([
                            'success' => false,
                            'message' => 'Appointment time is out of the schedule bounds.',
                        ], 422);
                    }

                    $slots = Slot::where('schedule_id', $schedule->id)->get();

                    if ($slots->isEmpty()) {
                        
                        $appointment->save();

                        Slot::create([
                            'schedule_id' => $schedule->id,
                            'appointment_id' => $appointment->id,
                            'is_booked' => true,
                            'date' => $appointment->date,
                            'start_time' => $appointment->start_time,
                            'end_time' => $appointment->end_time,
                        ]);

                        return response()->json([
                            'success' => true,
                            'message' => 'Appointment created successfully.',
                        ], 200);

                    }

                    $isSlotAvailable = true;

                    foreach ($slots as $slot) {

                        $slotStartTime = Carbon::parse($slot->start_time);
                        $slotEndTime = Carbon::parse($slot->end_time);

                        if (
                            $appointmentStartTime->between($slotStartTime, $slotEndTime) ||
                            $appointmentEndTime->between($slotStartTime, $slotEndTime) ||
                            $slotStartTime->between($appointmentStartTime, $appointmentEndTime) ||
                            $slotEndTime->between($appointmentStartTime, $appointmentEndTime) ||
                            $appointmentStartTime->eq($slotStartTime) ||
                            $appointmentEndTime->eq($slotEndTime)
                        ) {

                            $isSlotAvailable = false;
                            break;
                        }
                    }

                    if (!$isSlotAvailable) {

                        return response()->json([
                            'success' => false,
                            'message' => 'Slot is already booked.',
                        ], 422);
                    }

                    $appointment->save();

                    Slot::create([
                        'schedule_id' => $schedule->id,
                        'appointment_id' => $appointment->id,
                        'is_booked' => true,
                        'date' => $appointment->date,
                        'start_time' => $appointment->start_time,
                        'end_time' => $appointment->end_time,
                    ]);

                    return (true);
                }
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No such schedule found.',
            ], 404);
        }
    }

    public function getAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        $doctor = Doctor::where('id', $appointment->doctor_id)->first();

        $patient = Patient::where('id', $appointment->patient_id)->first();

        if ($doctor->user_id == Auth::user()->id || $patient->user_id == Auth::user()->id || Auth::user()->role == 'admin') {

            return $appointment;

        } else {

            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this appointment.',
            ], 403);
        }
    }


    public function updateAppointment($request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $doctor = Doctor::where('id', $appointment->doctor_id)->first();

        if ($doctor->user_id == Auth::user()->id || Auth::user()->role == 'admin') {

            if ($appointment->status == 'booked' && $request->status == 'pending') {

                return response()->json([
                    'success' => false,
                    'message' => 'You cannot make an already booked appointment pending.',
                ], 422);

            } elseif ($appointment->status == 'completed' && $request->status == 'booked' || $request->status == 'pending' || $request->status == 'cancelled') {
                
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot make status update on a completed appointment.',
                ], 422);
            }

            $appointment->status = $request->status;
            $appointment->description = $request->description;
            $appointment->save();

            if ($appointment->status == 'booked') {

                $result = $this->paymentService->createPayment($appointment->id);

                Mail::to($appointment->patient->user->email)->send(new AppointmentBooked($appointment));

            } elseif ($appointment->status == 'cancelled') {

                Mail::to($appointment->patient->user->email)->send(new AppointmentCancelled($appointment));
            }

            return ('Appointment updated successfully.');

        } else {

            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this appointment.',
            ],403);
        }
    }

    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        $doctor = Doctor::where('id', $appointment->doctor_id)->first();

        if (($doctor->user_id == Auth::user()->id || Auth::user()->role == 'admin') && ($appointment->status == 'pending' || $appointment->status == 'cancelled')) {
           
            $appointment->delete();
            
            return ('Appointment deleted successfully.');

        } else {

            return ('You are not authorized to delete this appointment');
        }
    }
}
