<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use Carbon\Carbon;
use App\Models\Slot;
use Filament\Actions;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Schedule;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\AppointmentResource;

class ViewAppointment extends ViewRecord
{
    protected static string $resource = AppointmentResource::class;


    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('patient_id')
                    ->label('Patient Name')
                    ->getStateUsing(function ($record) {
                        $patient = Patient::find($record->patient_id);
                        return $patient ? $patient->user->name : 'Unknown'; // Return name or fallback
                    }),

                TextEntry::make('doctor_id')
                    ->label('Doctor Name')
                    ->getStateUsing(function ($record) {
                        $doctor = Doctor::find($record->doctor_id);
                        return $doctor ? $doctor->user->name : 'Unknown'; // Return name or fallback
                    }),

                TextEntry::make('service_id')
                    ->label('Service')
                    ->getStateUsing(function ($record) {
                        $service = Service::find($record->service_id);
                        return $service ? $service->name : 'Unknown'; // Return name or fallback
                    }),

                TextEntry::make('schedule_id')
                    ->label('Date')
                    ->getStateUsing(function ($record) {
                        $schedule = Schedule::find($record->schedule_id);
                        return $schedule ? Carbon::parse($schedule->date)->format('d-m-Y') : 'Unknown';
                    }),

                TextEntry::make('slot_id')
                    ->label('Start Time')
                    ->getStateUsing(function ($record) {
                        $slot = Slot::find($record->slot_id);
                        return $slot ? Carbon::parse($slot->start_time)->format('' . 'H:i') : 'Unknown';
                    }),

                TextEntry::make('slot_id')
                    ->label('End Time')
                    ->getStateUsing(function ($record) {
                        $slot = Slot::find($record->slot_id);
                        return $slot ? Carbon::parse($slot->end_time)->format('' . 'H:i') : 'Unknown';
                    }),

                TextEntry::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        return $record->status;
                    }),

                TextEntry::make('description')
                    ->label('Description')
                    ->getStateUsing(function ($record) {
                        return $record->description;
                    }),

                ImageEntry::make('prescription')
                    ->label('Prescription')
                    ->getStateUsing(function ($record) {
                        return $record->prescription
                            ? asset('storage/' . $record->prescription)
                            : asset('images/logo.png');
                    })
                    ->height('auto')
                    ->width(700),
            ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }


}
