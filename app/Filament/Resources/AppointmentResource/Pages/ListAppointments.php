<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AppointmentResource;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('All')
                ->badge(fn() => $this->getCount())
                ->badgeColor('primary'),

            
                Tab::make('Upcoming')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('date', '>', Carbon::now()->format('Y-m-d'));
                    });
                    $count= count($query->get());
                })
                
                ->badge(function() {
                    if(Auth::user()->role == 'patient') {
                        return Appointment::where('patient_id', Auth::user()->patient->id)->whereHas('schedule', function ($scheduleQuery) {
                            $scheduleQuery->where('date', '>', Carbon::now()->format('Y-m-d')); 
                        })
                        ->count();
                    }
                    elseif(Auth::user()->role == 'doctor') {
                        return Appointment::where('doctor_id', Auth::user()->doctor->id)->whereHas('schedule', function ($scheduleQuery) {
                            $scheduleQuery->where('date', '>', Carbon::now()->format('Y-m-d'));
                        })
                        ->count();
                    }
                    return Appointment::whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('date', '>', Carbon::now()->format('Y-m-d'));
                    })->count();
                })
                ->badgeColor('primary'),

            Tab::make('Today')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('date', '=', Carbon::now()->format('Y-m-d'));
                    });
                })
                ->badge(function() {
                    if(Auth::user()->role == 'patient') {
                        return Appointment::where('patient_id', Auth::user()->patient->id)->whereHas('schedule', function ($scheduleQuery) {
                            $scheduleQuery->where('date', '=', Carbon::now()->format('Y-m-d')); 
                        })
                        ->count();
                    }
                    elseif(Auth::user()->role == 'doctor') {
                        return Appointment::where('doctor_id', Auth::user()->doctor->id)->whereHas('schedule', function ($scheduleQuery) {
                            $scheduleQuery->where('date', '=a', Carbon::now()->format('Y-m-d'));
                        })
                        ->count();
                    }
                    return Appointment::whereHas('schedule', function ($scheduleQuery) {
                        $scheduleQuery->where('date', '=', Carbon::now()->format('Y-m-d'));
                    })->count();
                })
                ->badgeColor('primary'),

            Tab::make('Pending')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'pending');
                })
                ->badge(fn() => $this->getCount('pending'))
                ->badgeColor('warning'),

            Tab::make('Booked')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'booked');
                })
                ->badge(fn() => $this->getCount('booked'))
                ->badgeColor('success'),

            Tab::make('Completed')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'completed');
                })
                ->badge(fn() => $this->getCount('completed'))
                ->badgeColor('success'),

            Tab::make('Cancelled')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('status', 'cancelled');
                })
                ->badge(fn() => $this->getCount('cancelled'))
                ->badgeColor('danger'),


        ];
    }

    protected function getCount(string $status = null): int
    {
        $query = Appointment::query();

        if (Auth::user()->role == 'patient') {
            $query->where('patient_id', Auth::user()->patient->id);
        } elseif (Auth::user()->role == 'doctor') {
            $query->where('doctor_id', Auth::user()->doctor->id);
        } elseif (Auth::user()->role == 'admin') {
            $query = Appointment::query();
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }
}
