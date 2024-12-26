<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Models\Slot;
use Filament\Actions;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\AppointmentResource;
use App\Models\Patient;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!isset($data['patient_id'])) {
            $data['patient_id'] = Auth::user()->patient->id;
        }

        if(Schedule::find($data['schedule_id']) == null)
        {
            throw ValidationException::withMessages([
                'schedule_id' => 'Schedule not found.',

                Notification::make()
                    ->danger()
                ->body('Schedule not found.')
                    ->send(),
            ]);
        }

        if(Slot::find($data['slot_id'])->is_booked){
            throw ValidationException::withMessages([
                'slot_id' => 'Slot is already booked.',

                Notification::make()
                    ->danger()
                    ->body('Slot is already booked.')
                    ->send(),
            ]);
        }


        $this->slotUpdate($data);
        
        return $data;
    }

    protected function slotUpdate($appointment)
    {
        $slot = Slot::find($appointment['slot_id']);  // Assuming the created appointment has the slot_id
        if ($slot) {
            $slot->is_booked = true;
            $slot->save();
        }
    }

    protected function afterCreate()
    {
        $appointment= $this->record;
        
        Payment::create([
            'amount' => Service::find($appointment['service_id'])->price,
            'appointment_id' => $appointment['id'],
            'service_id' => $appointment['service_id'],
            'user_id' => Patient::find($appointment['patient_id'])->user_id,
            'status' => 'unpaid',
        ]);

        
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
