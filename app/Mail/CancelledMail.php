<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $appointment;
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Appointment Booked',
        );
    }


    /**
     * Get the message content definition.
     */
    public function build()
    {
        $htmlContent = '<h1>Appointment Booked</h1>' .
            '<p>Hello, ' . $this->appointment['user->name'] . ',</p>' .
            '<p>Your appointment <strong>' . $this->appointment['id'] . '</strong> has been Booked.</p>' .
            '<p>Thank you.</p>';

        return $this->subject('Appointment Booked')
                    ->html($htmlContent);
    }
    public function attachments(): array
    {
        return [];
    }
}
