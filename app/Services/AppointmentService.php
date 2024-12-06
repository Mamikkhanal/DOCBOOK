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

        return response()->json($appointments);
    }
    
    public function getAllAppointments()
    {
        if (Auth::user()->role == 'admin') {
            $apppointments = Appointment::with('patient', 'doctor', 'service')->get();
            return response()->json([
                'success' => true, 
                'data' => $apppointments
            ],200);
        }
        elseif(Auth::user()->role == 'doctor')
        {
            $doctor = Doctor::where('user_id', Auth::user()->id)->first();
            $apppointments = Appointment::where('doctor_id', $doctor->id)->with('patient', 'doctor', 'service')->get();
            return response()->json([
                'success' => true, 
                'data' => $apppointments
            ],200);
        }
        elseif(Auth::user()->role == 'patient')
        {
            $patient = Patient::where('user_id', Auth::user()->id)->first();
            $apppointments = Appointment::where('patient_id', $patient->id)->with('patient', 'doctor', 'service')->get();
            return response()->json([
                'success' => true, 
                'data' => $apppointments
            ],200);
        }
        else
        {
            return response()->json([
                'success' => false, 
                'message' => 'No appointments found'
            ],404);
        }
    }

    // public function createAppointment($request)
    // {
    //     // DB::beginTransaction();

    //     // try {

    //     $patient_id = Patient::where('user_id', Auth::id())->first()->id;
    //     $appointment = new Appointment();
    //     $appointment->patient_id = $patient_id;
    //     $appointment->doctor_id = $request->doctor_id;
    //     $appointment->service_id = $request->service_id;
    //     $appointment->date = $request->date;
    //     $appointment->start_time = $request->start_time;
    //     $appointment->end_time = $request->end_time;
    //     $appointment->description = $request->description;
    //     // $appointment->save();


    //     $schedules = Schedule::where("doctor_id", $appointment->doctor_id)->get();

    //     if ($schedules->isEmpty()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No schedule found for the doctor.',
    //         ], 404);
    //     }
    //     else{
    //         $appointmentDate = Carbon::parse($appointment->date);
    //         $currentDate = Carbon::now()->startOfDay();

    //         if ($appointmentDate < $currentDate) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'The appointment date must be today or a future date.',
    //             ], 422); // 422 Unprocessable Entity
    //         }

    //         foreach ($schedules as $schedule) {

    //             $scheduleDate = Carbon::parse($schedule->date);
    //             $appointmentDate = Carbon::parse($appointment->date);

    //             if ($appointmentDate->eq($scheduleDate)) {

    //                 $scheduleStartTime = Carbon::parse($schedule->start_time);
    //                 $scheduleEndTime = Carbon::parse($schedule->end_time);

    //                 $appointmentStartTime = Carbon::parse($appointment->start_time);
    //                 $appointmentEndTime = Carbon::parse($appointment->end_time);

    //                 if ($appointmentStartTime < Carbon::now() || $appointmentEndTime < Carbon::now()) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Appointment time must be after the current time.',
    //                     ], 422);
    //                 }

    //                 if ($appointmentStartTime > $appointmentEndTime) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'The start time must be before the end time.',
    //                     ], 422); 
    //                 }

    //                 if (
    //                     !$appointmentStartTime->between($scheduleStartTime, $scheduleEndTime) || 
    //                     !$appointmentEndTime->between($scheduleStartTime, $scheduleEndTime)
    //                 ) {
    //                     return response()->json([
    //                         'success' => false,
    //                         'message' => 'Appointment time is out of the schedule bounds.',
    //                     ], 422);
    //                 }

    //                 $slots = Slot::where('schedule_id', $schedule->id)->get();

    //                 if ($slots->isEmpty())
    //                 {
    //                     // $appointment->save();
    //                     // $appointment_id = $appointment->id;
    //                     Slot::create([
    //                         'schedule_id' => $schedule->id,
    //                         'appointment_id' => $appointment->id,
    //                         'is_booked' => true,
    //                         'date' => $appointment->date,
    //                         'start_time' => $appointment->start_time,
    //                         'end_time' => $appointment->end_time,
    //                     ]);
    //                     // DB::commit();

    //                     return response()->json([
    //                         'success' => true,
    //                         'message' => 'Appointment created successfully.',
    //                     ],201);

    //                 }

    //                 foreach ($slots as $slot) {
    //                     $slotStartTime = Carbon::parse($slot->start_time);
    //                     $slotEndTime = Carbon::parse($slot->end_time);

    //                     if (
    //                         $appointmentStartTime->between($slotStartTime, $slotEndTime) ||
    //                         $appointmentEndTime->between($slotStartTime, $slotEndTime) ||
    //                         $slotStartTime->between($appointmentStartTime, $appointmentEndTime) ||
    //                         $slotEndTime->between($appointmentStartTime, $appointmentEndTime) ||
    //                         $appointmentStartTime->eq($slotStartTime) || $appointmentEndTime->eq($slotEndTime)
    //                     ) {
    //                         return response()->json([
    //                             'success' => false,
    //                             'message' => 'Slot is already booked.',
    //                             'data' => [
    //                                 'slots' => $slots,
    //                             ],
    //                         ], 422);

    //                     }
    //                     else{
    //                         $appointment->save();
    //                         Slot::create([
    //                             'schedule_id' => $schedule->id,
    //                             'appointment_id' => $appointment->id,
    //                             'is_booked' => true,
    //                             'date' => $appointment->date,
    //                             'start_time' => $appointment->start_time,
    //                             'end_time' => $appointment->end_time,
    //                         ]);
    //                         // DB::commit();
    //                         return response()->json([
    //                             'success' => true,
    //                             'message' => 'Appointment created successfully.',
    //                         ],201);
    //                     }
    //                 }

    //             }
    //         }

    //         // DB::rollBack();
    //         // return response()->json([
    //         //     'success' => false,
    //         //     'message' => 'An unknown error occurred 1.',
    //         // ], 500);

    //     }
    //     //}
    //     // catch (Exception $e) {
    //     //     DB::rollBack();
    //     //     return [
    //     //         'success' => false,
    //     //         'message' => 'An unknown error occurred 2.',
    //     //         'error' => $e->getMessage(),
    //     //         'status' => 500
    //     //     ];
    //     // }
    // }

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
            return response()->json([
                'success' => false,
                'message' => 'No schedule found for the doctor.',
            ],404);
        } else {
            $appointmentDate = Carbon::parse($appointment->date);
            $currentDate = Carbon::now()->startOfDay();
            if ($appointmentDate < $currentDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'The appointment date must be today or a future date.',
                ],422);
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
                        ],422);
                    }
                    if ($appointmentStartTime > $appointmentEndTime) {
                        return response()->json([
                            'success' => false,
                            'message' => 'The start time must be before the end time.',
                        ],422);
                    }

                    if (
                        !$appointmentStartTime->between($scheduleStartTime, $scheduleEndTime) ||
                        !$appointmentEndTime->between($scheduleStartTime, $scheduleEndTime)
                    ) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Appointment time is out of the schedule bounds.',
                        ],422);
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
                        ],200);
                    }

                    // foreach ($slots as $slot) {
                    //     $slotStartTime = Carbon::parse($slot->start_time);
                    //     $slotEndTime = Carbon::parse($slot->end_time);

                    //     dd($slotStartTime, $slotEndTime, $appointmentStartTime, $appointmentEndTime);

                    //     if (
                    //         $appointmentStartTime->between($slotStartTime, $slotEndTime) ||
                    //         $appointmentEndTime->between($slotStartTime, $slotEndTime) ||
                    //         $slotStartTime->between($appointmentStartTime, $appointmentEndTime) ||
                    //         $slotEndTime->between($appointmentStartTime, $appointmentEndTime) ||
                    //         $appointmentStartTime->eq($slotStartTime) || $appointmentEndTime->eq($slotEndTime)
                    //         ) {
                    //         return response()->json([
                    //             'success' => false,
                    //             'message' => 'Slot is already booked.',
                    //             'data' => [
                    //                 'slots' => $slots,
                    //             ],
                    //         ]);
                    //     }
                    //     else{

                    //         $appointment->save();
                    //         Slot::create([
                    //             'schedule_id' => $schedule->id,
                    //             'appointment_id' => $appointment->id,
                    //             'is_booked' => true,
                    //             'date' => $appointment->date,
                    //             'start_time' => $appointment->start_time,
                    //             'end_time' => $appointment->end_time,
                    //         ]);
                    //         return response()->json([
                    //             'success' => true,
                    //             'message' => 'Appointment created successfully.',
                    //         ]);
                    //     }
                    // }


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
                            'data' => [
                                'slots' => $slots,
                            ],
                        ],422);
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

                    return response()->json([
                        'success' => true,
                        'message' => 'Appointment created successfully.',
                    ],200);
                }
            }
            return response()->json([
                'success' => false,
                'message' => 'No such schedule found.',
            ],404);
        }
    }

    public function getAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctor = Doctor::where('doctor_id', $appointment->doctor_id)->first();
        $patient = Patient::where('patient_id', $appointment->patient_id)->first();

        if ($doctor->user_id == Auth::user()->id || $patient->user_id == Auth::user()->id || Auth::user()->role == 'admin') {
            return response()->json([
                'success' => true,
                'data' => $appointment,
            ],200);
        }
        else {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this appointment.',
            ],403);
        }

    }


    public function updateAppointment($request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctor = Doctor::where('id', $appointment->doctor_id)->first();
        if ($doctor->user_id ==Auth::user()->id || Auth::user()->role == 'admin') {

            if($appointment->status == 'booked' && $request->status == 'pending')
            {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot make an already booked appointment pending.',
                ],422);
            }
            elseif($appointment->status == 'completed' && $request->status == 'booked'|| $request->status == 'pending'|| $request->status == 'cancelled')
            {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot make status update on a completed appointment.',
                ],422);
            }

            $appointment->status = $request->status;
            $appointment->description = $request->description;
            $appointment->save();

            if ($appointment->status == 'booked') {

                $result = $this->paymentService->createPayment($appointment->id);
                Mail::to($appointment->patient->user->email)->send(new AppointmentBooked($appointment));
            }
            elseif ($appointment->status == 'cancelled') {
                
                Mail::to($appointment->patient->user->email)->send(new AppointmentCancelled($appointment));
            }
            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully.',
            ],200);
        }
        else
        {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this appointment.',
            ],403);
        }

    }

    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctor = Doctor::where('doctor_id', $appointment->doctor_id)->first();

        if ($doctor->user_id == Auth::user()->id || Auth::user()->role == 'admin') {
            
            $appointment->delete();
            return response()->json([
                'success' => true,
                'message' => 'Appointment deleted successfully.',
            ],200);
        }
    }
}