<?php

use App\Models\Patient;
use App\Models\Specialization;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getRoleFormComponent(),
                        $this->getSpecializationFormComponent(),
                        $this->getAgeFormComponent(),
                        Select::make('role')
            ->options([
                'patient' => 'Patient',
                'doctor' => 'Doctor',
            ])
            ->required(),

                    ])
                    ->statePath('data'),
            ),
        ];
    }
 
    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->options([
                'patient' => 'Patient',
                'doctor' => 'Doctor',
            ])
            ->required();
    }

    protected function getSpecializationFormComponent(): Component
    {
        return Select::make('specialization')
            ->options(Specialization::all()->pluck('name', 'id'))
            ->required();
    }

    protected function getAgeFormComponent(): Component
    {
        return TextInput::make('age')
            ->required()
            ->numeric();
    }
}
