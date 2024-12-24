<?php

namespace App\Filament\Resources\SpecializationResource\Pages;

use App\Filament\Resources\SpecializationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSpecialization extends CreateRecord
{
    protected static string $resource = SpecializationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = strtolower($data['name']);
        return $data;
    }
}
