<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Middleware\IsPatient;
use App\Http\Middleware\IsDoctor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/doc_details', [RegisteredUserController::class,'add_details'])->name('doc_details');
    Route::post('/doctor/store', [DoctorController::class,'store'])->name('doctor.store');
    
    Route::get('/pat_details', [RegisteredUserController::class,'add_details'])->name('pat_details');
    Route::post('/patient/store', [PatientController::class,'store'])->name('patient.store');

});

Route::middleware([IsPatient::class])->group(function () {
    Route::post('/appointment/create', [AppointmentController::class,'create'])->name('appointment.create');    
});

Route::middleware([IsDoctor::class])->group(function () {
    Route::post('/appointment/edit', [AppointmentController::class,'edit'])->name('appointment.edit');    
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get ('/book', [AppointmentController::class,'index'])->name('book');




require __DIR__.'/auth.php';
