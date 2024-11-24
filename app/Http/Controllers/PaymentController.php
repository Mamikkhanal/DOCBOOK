<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use RemoteMerge\Esewa\Client;

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
     * Show the form for creating a new resource.
     */
    public function create(Appointment $appointment)
    {
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
        return redirect()->route('dashboard');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function esewaPay(Request $request, Appointment $appointment) {
        
        $payment = Payment::where('appointment_id', $appointment->id)->first();
        $pid = uniqid();
        $amount = $payment->amount;

        $payment->pid = $pid;
        $payment->save();

        $successUrl = route('payment.success', );
        $failureUrl = route('payment.failure', );

        $esewa = new Client([
            'merchant_code' => 'EPAYTEST',
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
        ]);

        $esewa->payment($pid, $amount, 0, 0, 0);
    }

    public function esewaPaySuccess() {
        echo  "success";
        $pid = $_GET['oid'];
        $refid = $_GET['refId'];
        $amount = $_GET['amt'];

        $payment = Payment::where('pid', $pid)->first();

        $payment->status = 'paid';
        $payment->save();

        if($payment->status == 'paid') {
            return redirect()->route('dashboard')->with( 'payment','Payment Successful');
        }

    }

    public function esewaPayFailure() {
        $pid = $_GET['pid'];
        
        $payment = Payment::where('pid', $pid)->first();
        $payment->status = 'unpaid';
        $payment->save();

        if($payment->status == 'unpaid') {
            return redirect()->route('dashboard')->with( 'payment','Payment Failed');
        }
        
    }

}
