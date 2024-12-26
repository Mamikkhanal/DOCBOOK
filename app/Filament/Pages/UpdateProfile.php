<?php

namespace App\Filament\Pages;

use App\Models\Doctor;
use App\Models\Patient;
use Faker\Provider\ar_EG\Text;
use Filament\Pages\Page;
use Filament\Forms\Components\Split;
use Filament\Pages\Auth\EditProfile;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;

class UpdateProfile extends EditProfile
{
    protected function getForms(): array
    {
        $this->maxWidth = '5xl';
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([

                        Split::make([
                            Section::make('Personal Information')->schema([
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                                $this->getPhoneFormComponent(),
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                            ]),

                            Section::make('Role Information')->schema([
                                $this->getAgeFormComponent()
                                    ->visible(Auth::user()->role == 'patient'),
                                $this->getSpecializationFormComponent()
                                    ->visible(Auth::user()->role == 'doctor'),

                            ])

                        ])


                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();
        if ($user->role == 'patient') {

            $patient = $user->patient;
            $patient->age = $data['age'];
            $patient->save();
            unset($data['age']);
        }
        return $data;
    }

    public function afterSave()
    {
        return redirect()->route('filament.admin.pages.dashboard');
    }


    protected function getAgeFormComponent(): Component
    {
        return TextInput::make('age')
            ->numeric()
            ->required();
    }

    protected function getSpecializationFormComponent(): Component
    {
        $specialization = Doctor::where('user_id', Auth::user()->id)
        ->value('specialization');

        return
            TextInput::make('specialization')
            ->default($specialization)
            ->placeholder($specialization)
            ->disabled(true);
    }


    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->tel()
            ->required();
    }
}
