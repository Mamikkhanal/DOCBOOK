<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PaymentResource;
use App\Models\Appointment;
use App\Models\Service;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;
}
