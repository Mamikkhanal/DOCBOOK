<?php

namespace App\Filament\Resources\PatientResource\Pages;

use Filament\Actions;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PatientResource;
use Illuminate\Support\Facades\DB;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    protected function afterCreate($record)
{
    // Check the role selected in the form
    $role = $record->role;

    if ($role === 'patient') {
        // Assign Patient details
        $record->patient()->create([
            'age' => request()->input('age'), // Retrieve 'age' from the form
        ]);
    } elseif ($role === 'doctor') {
        // Assign Doctor details
        $record->doctor()->create([
            'specialization' => request()->input('specialization'), // Retrieve 'specialization' from the form
        ]);
    }
}

}

