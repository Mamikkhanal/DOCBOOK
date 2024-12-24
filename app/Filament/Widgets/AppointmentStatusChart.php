<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AppointmentStatusChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Appointment Status';

    protected static ?string $maxHeight = '180px';

//     public static function canView(): bool
// {
//     return Auth::user()->role === 'admin';
// }

    protected function getData(): array
    {
        // Query the database to get counts for each status
        $statuses = ['pending', 'booked', 'completed', 'cancelled'];
        $data = [];

        foreach ($statuses as $status) {
            if(Auth::user()->role == 'patient') {
                $data[] = Appointment::where('status', $status)->where('patient_id', Auth::user()->patient->id)->count();  
            }
            elseif(Auth::user()->role == 'doctor') {
                $data[] = Appointment::where('status', $status)->where('doctor_id', Auth::user()->doctor->id)->count();
            }
            elseif(Auth::user()->role == 'admin') {
                $data[] = Appointment::where('status', $status)->count();
            }
        }

        return [
            'labels' => ['Pending', 'Booked', 'Completed', 'Cancelled'],
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => $data,
                    'backgroundColor' => ['#FFA500', '#36A2EB', '#4CAF50', '#FF6384'], // Colors for each status
                    'borderColor' => ['#FFC107', '#9BD0F5', '#81C784', '#FF9AA2'], // Border colors
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Chart type: pie chart
    }
}
