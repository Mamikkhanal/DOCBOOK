<?php
namespace App\Filament\Resources\PaymentResource\Pages;

use Stripe\Charge;
use Stripe\Stripe;

use App\Models\Payment;
use Illuminate\Http\Request;
use Filament\Resources\Pages\Page;

use Filament\Notifications\Notification;
use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class StripePayment extends Page
{
    use InteractsWithRecord;

    public $payment;

    protected static string $resource = AppointmentResource::class;

    protected static string $view = 'filament.resources.payment-resource.pages.stripe-payment';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // Override getRecord() to ensure it always returns an Eloquent model
    public function getRecord(): Payment
    {
        if (!isset($this->record)) {
            throw new \Exception('Record not initialized');
        }
    
        $appointment= Appointment::find($this->record);
        $payment = $appointment->payment;
    
        if (!$payment) {
            throw new \Exception('Payment record not found');
        }
    
        return $payment;
    }
    
    public function mount($record): void
    {
        // Ensure the record is available and set the payment model
        $payment = $this->getRecord();

        if (!$payment) {
            Notification::make()
                ->title('Payment not found')
                ->body('Payment not found!')
                ->danger()
                ->send();

            // Perform the redirection without returning it from mount
            $this->redirectRoute('filament.admin.resources.payments.index');
            return; // Ensure no further processing happens
        }

        if ($payment->payment_status == 'completed') {
            Notification::make()
                ->title('Payment Status')
                ->body('Payment already done!')
                ->danger()
                ->send();

            // Perform the redirection without returning it from mount
            $this->redirectRoute('filament.admin.resources.payments.index');
            return; // Ensure no further processing happens
        }

        // Set the payment to be used in the view
        $this->payment = $payment;
    }

    public function createCharge(Request $request ,$payment_id)
    {
        $payment = Payment::find($payment_id);
        // dd($request);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        Charge::create([
            "amount" => $payment->amount,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Binaryboxtuts Payment Test"
        ]);

        $payment->update([
            'status' => 'paid',
            'pid' => $request->stripeToken,
        ]);

        $appointment = Appointment::find($payment->appointment_id);
        if($appointment->status == 'pending') {
            $appointment->update([
                'status' => 'booked',
            ]);
        }

        // Return with success notification
        return redirect()->route('filament.admin.resources.payments.index')->with('success', 'Payment successfully done!');
    }
}