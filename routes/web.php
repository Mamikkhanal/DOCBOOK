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

Route::middleware('IsPatient')->group(function () {
    Route::get('/appointment/create', [AppointmentController::class,'create'])->name('appointment.create');
    Route::post('/appointment/store', [AppointmentController::class,'store'])->name('appointment.store');     
});

Route::middleware([IsDoctor::class])->group(function () {
    Route::get('/appointment/edit/{appointment}', [AppointmentController::class,'edit'])->name('appointment.edit');  
    Route::put('/appointment/update/{id}', [AppointmentController::class,'update'])->name('appointment.update');      
});

Route::middleware(['auth','verified'])->group(function () {

    Route::get('/doc_details', [RegisteredUserController::class,'add_details'])->name('doc_details');
    Route::post('/doctor/store', [DoctorController::class,'store'])->name('doctor.store');
    
    Route::get('/pat_details', [RegisteredUserController::class,'add_details'])->name('pat_details');
    Route::post('/patient/store', [PatientController::class,'store'])->name('patient.store');

    Route::get('/appointment', [AppointmentController::class,'index'])->name('appointment.index');
    Route::get('/appointment/{appointment}', [AppointmentController::class,'show'])->name('appointment.show');
    Route::delete ('/appointment/delete/{appointment}', [AppointmentController::class,'destroy'])->name('appointment.destroy');

    Route::get('/dashboard', [AppointmentController::class ,'dashboard'])->name('dashboard');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get ('/book', [AppointmentController::class,'index'])->name('book');




require __DIR__.'/auth.php';
