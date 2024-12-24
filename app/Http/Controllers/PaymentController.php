<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Xentixar\EsewaSdk\Esewa;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function pay(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        $esewa = new Esewa();

        $pid = "TXN-" . uniqid();
        
        $payment->update([
            'pid' => $pid,
        ]);
        
        $esewa->config(
            route('payment.success'),
            route('payment.failure'),
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

                    Mail::to($payment->appointment->patient->user->email)->send(new \App\Mail\PaymentMail($payment));

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
