<?php

namespace App\Filament\Resources\ReviewResource\Pages;

use Filament\Actions;
use App\Models\Appointment;
use Filament\Notifications\Notification;

use App\Filament\Resources\ReviewResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;

class CreateReview extends CreateRecord
{
    protected static string $resource = ReviewResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if(!isset($data['appointment_id'])) {
            throw ValidationException::withMessages([
                'appointment_id' => 'You must visit through the appointment to create review',
                Notification::make()
                ->danger()
                ->body('You must visit through the appointment to create review')
                 ->send(),
            ]);
        }
       $appointment = Appointment::find($data['appointment_id']);
       if($appointment->status !== 'completed') {
           throw ValidationException::withMessages([
               'review' => 'Appointment is not completed',
               Notification::make()
               ->danger()
               ->body('Appointment is not completed')
                ->send(),
           ]);
       }elseif($appointment->review){
           throw ValidationException::withMessages([
               'review' => 'Review already exists',
               Notification::make()
               ->danger()
               ->body('Review already exists')
                ->send(),
           ]);
       }

       return $data;
    }


}
