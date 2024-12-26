<?php

namespace App\Filament\Pages\Auth;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Database\Eloquent\Model;

class RegisterPage extends BaseRegister
{

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPhoneFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getRoleFormComponent(),
                        $this->getSpecializationFormComponent()
                        ->visible(fn (): bool => $this->data['role'] == 'doctor'),
                        $this->getAgeFormComponent()
                        ->visible(fn (): bool => $this->data['role'] == 'patient'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }




    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->numeric()
            ->minLength(10)
            ->required();
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->options([
                'patient' => 'Patient',
                'doctor' => 'Doctor',
            ])
            ->required()
            ->reactive();
    }

    protected function getSpecializationFormComponent(): Component
    {
        return
        Fieldset::make('Specialization')
        ->relationship('doctorcreate')
         ->schema([
            Select::make('specialization')
                ->options(Specialization::all()->pluck('name', 'id'))
                ]);
    }

    protected function getAgeFormComponent(): Component
    {
        return TextInput::make('age')
            ->numeric()
            ->required();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($this->data['specialization']);
        unset($this->data['age']);
        return $data;
    }
    protected function afterSave (array $data): void
    {
        if ($data['role'] == 'patient') {
            Patient::create([
                'user_id' => $data['id'],
                'age' => $data['age'],
            ]);
            unset($data['age']);
            unset($data['specialization']);
        }
        if($data['role'] == 'doctor') {
            Doctor::create([
                'user_id' => $data['id'],
                'specialization' => $data['specialization'],
            ]);
            unset($data['specialization']);
            unset($data['age']);
        }
    }


}
