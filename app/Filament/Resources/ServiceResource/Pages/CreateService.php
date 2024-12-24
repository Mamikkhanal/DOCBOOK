<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use Carbon\Carbon;
use Filament\Actions;
use App\Models\Schedule;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ServiceResource;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = strtolower($data['name']);
        return $data;
    }
}
