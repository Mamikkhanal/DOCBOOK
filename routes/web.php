<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegistrationController;
use App\Filament\Resources\PaymentResource\Pages\StripePayment;
use App\Http\Controllers\SpecializationSuggestionController;



Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/register', [RegistrationController::class, 'showRegisterForm'])->name('register.form')->name('register.form');
Route::post('/register', [RegistrationController::class, 'register'])->name('register.submit');

Route::get('/payments/{id}/pay', [PaymentController::class, 'pay'])->name('payment.pay');
Route::get('/payments/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payments/failure', [PaymentController::class, 'failure'])->name('payment.failure');

Route::post('stripe/create-charge/{payment}', [StripePayment::class, 'createCharge'])->name('stripe.create-charge');


Route::post('/specialization-suggestion', [SpecializationSuggestionController::class, 'suggestSpecialization']);
