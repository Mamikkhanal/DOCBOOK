<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Filament\Notifications\Notification;
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

        if ($response) {

            if (isset($response['transaction_uuid'])) {
                $transactionUuid = $response['transaction_uuid'];

                $payment = Payment::where('pid', $transactionUuid)->first();

                if ($payment) {
                    $payment->update([
                        'status' => 'paid',
                    ]);

                    $appointment = Appointment::find($payment->appointment_id);
                    if ($appointment->status == 'pending') {
                        $appointment->update([
                            'status' => 'booked',
                        ]);
                    }

                    Mail::to($payment->appointment->patient->user->email)->send(new \App\Mail\PaymentMail($payment));

                    Notification::make()
                        ->title('Payment Success')
                        ->body('Payment successfully done!')
                        ->success()
                        ->send();
                    return redirect()->back()->with('success', 'Payment successfully done!');
                } else {
                    Notification::make()
                        ->title('Payment Failed')
                        ->body('Payment not found!')
                        ->success()
                        ->send();
                    return redirect()->back()->with('error', 'Payment not found!');
                }
            }
            return redirect()->back()->with('error', 'Payment failed!');
        }
    }


    /**
     * Payment failure
     */
    public function failure(Request $request)
    {
        Notification::make()
            ->title('Payment Failed')
            ->body('Payment failed!')
            ->danger()
            ->send();
        return response()->json(['success' => false, 'message' => 'Payment failed.'], 400);
    }
}
