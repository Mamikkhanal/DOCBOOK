<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AppointmentsChart extends ChartWidget
{
    protected static ?string $heading = 'Appointments';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Initialize an array to hold counts for each month
        $monthlyCounts = array_fill(1, 12, 0);

        $user = Auth::user();

        // Build the query dynamically based on the user's role
        $query = Appointment::query();

        if ($user->role === 'doctor') {
         
            $query = $query->where('doctor_id', $user->doctor->id);

        } elseif ($user->role === 'patient') {
            
           $query = $query->where('patient_id', $user->patient->id);

        } elseif ($user->role === 'admin') {
            $query;
        }

        // Query the database for appointments grouped by month
        $appointments = $query->selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Populate the monthlyCounts array with actual counts
        foreach ($appointments as $appointment) {
            $month = (int) $appointment->month; // Convert month to integer
            $monthlyCounts[$month] = $appointment->count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Appointments Created',
                    'data' => array_values($monthlyCounts),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

}
