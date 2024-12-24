<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {

        // // Users
        // $currentWeekUsers = User::createdCurrentWeek()->count();
        // $pastWeekUsers = User::createdPastWeek()->count();
        // $userGrowth = $pastWeekUsers > 0
        //     ? (($currentWeekUsers - $pastWeekUsers) / $pastWeekUsers) 
        //     : 0;
        // $userGrowth = round($userGrowth, 2);

        // // Doctors
        // $currentWeekDoctors = Doctor::createdCurrentWeek()->count();
        // $pastWeekDoctors = Doctor::createdPastWeek()->count();
        // $doctorGrowth = $pastWeekDoctors > 0
        //     ? (($currentWeekDoctors - $pastWeekDoctors) / $pastWeekDoctors) 
        //     : 0;
        // $doctorGrowth = round($doctorGrowth, 2);

        // // Patients
        // $currentWeekPatients = Patient::createdCurrentWeek()->count();
        // $pastWeekPatients = Patient::createdPastWeek()->count();
        // $patientGrowth = $pastWeekPatients > 0
        //     ? (($currentWeekPatients - $pastWeekPatients) / $pastWeekPatients) 
        //     : 0;
        // $patientGrowth = round($patientGrowth, 2);

        // Users
        $totalUsers = User::count();
        $currentWeekUsers = User::createdCurrentWeek()->count();
        $userGrowth = $totalUsers > 0
            ? ($currentWeekUsers / $totalUsers) * 100
            : 0;
        $userGrowth = round($userGrowth, 2);

        // Doctors
        $totalDoctors = Doctor::count();
        $currentWeekDoctors = Doctor::createdCurrentWeek()->count();
        $doctorGrowth = $totalDoctors > 0
            ? ($currentWeekDoctors / $totalDoctors) * 100
            : 0;
        $doctorGrowth = round($doctorGrowth, 2);

        // Patients
        $totalPatients = Patient::count();
        $currentWeekPatients = Patient::createdCurrentWeek()->count();
        $patientGrowth = $totalPatients > 0
            ? ($currentWeekPatients / $totalPatients) * 100
            : 0;
        $patientGrowth = round($patientGrowth, 2);


        return [

            Stat::make('Users', User::count())
                ->description("Growth {$userGrowth}%")
                ->descriptionIcon($userGrowth > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color($userGrowth > 0 ? 'success' : 'danger'),

            Stat::make('Doctors', Doctor::count())
                ->description("Growth {$doctorGrowth}%")
                ->descriptionIcon($doctorGrowth > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color($doctorGrowth > 0 ? 'success' : 'danger'),

            Stat::make('Patients', Patient::count())
                ->description("Growth {$patientGrowth}%")
                ->descriptionIcon($patientGrowth > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color($patientGrowth > 0 ? 'success' : 'danger'),
        ];
    }
}
