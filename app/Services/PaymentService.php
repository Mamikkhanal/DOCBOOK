<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Payment;

class PaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public  function createPayment(string $id)
    {
        $appointment = Appointment::findOrFail($id);

        $price = Service::where('id', $appointment->service_id)->first()->price;

        $length = Carbon::parse($appointment->start_time)->diffInMinutes(Carbon::parse($appointment->end_time));

        $price = $price * ($length / 10);

        $payment = Payment::where('appointment_id', $appointment->id)->first();

        if ($payment) {

            return response()->json([
                'success' => false,
                'message' => 'Payment already created.'
            ]);
            
        } else {
            Payment::create([
                'user_id' => Patient::where('id', $appointment->patient_id)->first()->user_id,
                'appointment_id' => $appointment->id,
                'service_id' => $appointment->service_id,
                'amount' => $price,
                'status' => 'unpaid',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment created.'
            ]);
        }
    }
}
