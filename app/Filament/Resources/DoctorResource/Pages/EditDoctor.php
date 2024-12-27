<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use Exception;
use Filament\Actions;
use App\Models\Specialization;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

use App\Filament\Resources\DoctorResource;
use function PHPUnit\Framework\throwException;

class EditDoctor extends EditRecord
{
    protected static string $resource = DoctorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['specialization'])) {
            $specialization = Specialization::where('name', $data['specialization'])->first();
            if ($specialization) {
                $data['specialization'] = $specialization->name;
            }
        }
        else{
            try {
                Notification::make()
                    ->danger()
                    ->title('Specialization not found')
                    ->body('Please select a valid specialization.')
                    ->send();
            
                throw new Exception('Specialization not found. Please select a valid specialization.');
            } catch (Exception $e) {
                throw $e;   // Re-throw the exception to stop further execution
            }
            
        }
        return $data;
        
    }
}
