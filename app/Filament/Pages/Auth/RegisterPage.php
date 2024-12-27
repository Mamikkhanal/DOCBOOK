<?php

namespace App\Filament\Pages\Auth;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use Faker\Provider\ar_EG\Text;
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

                        Select::make('role')
                            ->options([
                                'patient' => 'Patient',
                                'doctor' => 'Doctor',
                            ])
                            ->reactive(),
                        
                        Fieldset::make('Age')
                        ->schema([
                            TextInput::make('age')
                            ])
                        ->visible(
                            fn () => $this->data['role'] === 'patient'),

                        Fieldset::make('Specialization')
                            ->schema([
                                Select::make('specialization')
                                    ->options(Specialization::all()->pluck('name', 'id'))
                                ])
                            ->visible(
                                fn () => $this->data['role'] === 'doctor'),

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
        // ->relationship('doctorcreate')
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Exclude age and specialization when inserting into the 'users' table
        unset($data['age'], $data['specialization']);
        return $data;
    }
    
    protected function afterRegister()
    {
        $user = $this->form->model;
    
        if ($user->role === 'patient') {
            // Save patient-specific data
            Patient::create([
                'user_id' => $user->id,
                'age' => $this->data['age'], // Get the age from the form state
            ]);
        } elseif ($user->role === 'doctor') {
            // Save doctor-specific data
            Doctor::create([
                'user_id' => $user->id,
                'specialization_id' => $this->data['specialization'], // Get specialization from the form state
            ]);
        }
    }
    

}
