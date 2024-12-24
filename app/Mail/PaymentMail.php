<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $payment;
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build()
    {
        $htmlContent = '<h1>Appointment Paid</h1>' .
            '<p>Hello, ' . $this->payment['user->name'] . ',</p>' .
            '<p>Your appointment <strong>' . $this->payment['appointment_id'] . '</strong> has been Paid.</p>' .
            '<p>Thank you.</p>';

        return $this->subject('Appointment Paid')
                    ->html($htmlContent);
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
