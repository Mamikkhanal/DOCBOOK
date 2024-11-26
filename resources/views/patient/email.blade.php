@php
    use Carbon\Carbon;
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Appointment Cancelled</title>
</head>
<body>
    <p>Dear {{ $appointment->patient->user->name }},</p>
    <p>We regret to inform you that your appointment scheduled for
         {{ Carbon::parse($appointment->date)->format('d-m-Y') }} 
         at 
         {{ Carbon ::parse($appointment->start_time)->format('H:i A') }}-{{ Carbon::parse($appointment->end_time)->format('H:i A') }} has been cancelled.</p>
    <p>If you have any questions, please contact our support team.</p>
    <p>Thank you,</p>
    <p>The Clinic Team</p>
</body>
</html>
