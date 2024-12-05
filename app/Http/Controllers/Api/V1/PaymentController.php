<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use Xentixar\EsewaSdk\Esewa;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request)
    {
        $appointment = Appointment::findOrFail($request->appointment);
        $price = Service:: where('id', $appointment->service_id)->first()->price;
        $length = Carbon::parse($appointment->start_time)->diffInMinutes(Carbon::parse($appointment->end_time));
        $price = $price * ($length/10);

        Payment::create([
            'user_id' => Patient::where('id', $appointment->patient_id)->first()->user_id,
            'appointment_id' => $appointment->id,
            'service_id' => $appointment->service_id,
            'amount' => $price,
            'status' => 'unpaid',
        ]);

        return response()->json(['success' => true, 'message' => 'Payment created.']);

    }

    /**
     * Display the specified resource
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Pay for an appointment
     */
    public function pay(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        $esewa = new Esewa();

        $pid = "TXN-" . uniqid();
        
        $payment->update([
            'pid' => $pid,
        ]);
        
        $esewa->config(
            route('api.payment.success'),
            route('api.payment.failure'),
            $payment->amount,
            $pid,
        );

        return $esewa->init();
    }

    /**
     * Payment success
     */
    public function success(Request $request)
    {
        $esewa = new Esewa();
        $response = $esewa->decode();

        if ($response){

            if(isset($response['transaction_uuid'])){
                $transactionUuid = $response['transaction_uuid'];

                $payment = Payment::where('pid', $transactionUuid)->first();

                if($payment){
                    $payment->update([
                        'status' => 'paid',
                    ]);

                    Mail::to($payment->user->email)->send(new \App\Mail\PaymentMail($payment));

                    return response()->json(['success' => true, 'message' => 'Payment successful.'],200);
                }else{
                    return response()->json(['success' => false, 'message' => 'Payment not found.'],404);
                }

            }
        return response ()->json(['success' => false, 'message' => 'Invalid response from Esewa.'],400);
        }
    }


    /**
     * Payment failure
     */
    public function failure(Request $request)
    {
        return response()->json(['success' => false, 'message' => 'Payment failed.'],400);
    }
}

