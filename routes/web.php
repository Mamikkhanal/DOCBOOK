<?php

use App\Models\Review;
use App\Models\Specialization;
use App\Http\Middleware\IsDoctor;
use App\Http\Middleware\IsPatient;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('IsAdmin')->group(function () {
    Route::resource('/specialization', SpecializationController::class);
    Route::resource('/service', ServiceController::class);
    Route::get('/admins', [RegisteredUserController::class,'admins'])->name('admins');
    Route::delete('/admins/delete/{id}', [RegisteredUserController::class,'adminsDelete'])->name('adminsDelete');
    Route::get('/doctors', [DoctorController::class,'index'])->name('doctors');
    Route::get('/patients', [PatientController::class,'index'])->name('patients');
    
});

Route::middleware('IsPatient')->group(function () {
    
    Route::get('/pat_details', [RegisteredUserController::class,'add_details'])->name('pat_details');
    Route::post('/patient/store', [PatientController::class,'store'])->name('patient.store');
    
    Route::get('/appointment/create', [AppointmentController::class,'create'])->name('appointment.create');
    Route::post('/appointment/store', [AppointmentController::class,'store'])->name('appointment.store');
    
    Route::post('/review/create', [ReviewController::class,'create'])->name('review.create');
    Route::post('/review/store',[ReviewController::class,'store'])->name('review.store');
    Route::get('/review/edit/{review}',[ReviewController::class,'edit'])->name('review.edit');
    Route::put('/review/update/{review}',[ReviewController::class,'update'])->name('review.update');
    Route::delete('/review/delete/{review}',[ReviewController::class,'destroy'])->name('review.destroy');
    
    Route::get('/get-schedules/{id}', [ScheduleController::class, 'getschedules']);
    Route::get('/get-slots', [SlotController::class, 'getSlots']);
    Route::get('/slot/create/{appointment}',[SlotController::class,'create'])->name('slot.create');
});

Route::middleware('IsDoctor')->group(function () {
    
    Route::get('/doc_details', [RegisteredUserController::class,'add_details'])->name('doc_details');
    Route::post('/doctor/store', [DoctorController::class,'store'])->name('doctor.store');
    
    Route::get ('/schedule',[ScheduleController::class,'index'])->name('schedule.index');
    Route::get ('schedule/create', [ScheduleController::class,'create'])->name('schedule.create');
    Route::post ('schedule/store', [ScheduleController::class,'store'])->name('schedule.store');
    Route::get('schedule/show/{id}', [ScheduleController::class, 'show'])->name('schedule.show');
    Route::get ('/schedule/edit/{id}', [ScheduleController::class,'edit'])->name('schedule.edit');
    Route::put ('/schedule/update/{id}', [ScheduleController::class,'update'])->name('schedule.update');
    Route::delete ('/schedule/delete/{id}', [ScheduleController::class,'destroy'])->name('schedule.destroy');
});

Route::middleware(['auth','verified'])->group(function () {
    
    Route::get('/appointment', [AppointmentController::class,'index'])->name('appointment.index');
    Route::get('/appointment/{id}', [AppointmentController::class,'show'])->name('appointment.show');
    Route::delete ('/appointment/delete/{id}', [AppointmentController::class,'destroy'])->name('appointment.destroy');
    
    Route::get('/dashboard', [AppointmentController::class ,'dashboard'])->name('dashboard');
    
    Route::get('/review',[ReviewController::class,'index'])->name('review.index');

    Route::post('/esewaPay{appointment}', [PaymentController::class,'esewaPay'])->name('esewaPay');
    Route::get ('/success',[PaymentController::class,'esewaPaySuccess'])->name('payment.success');
    Route::get ('/failure',[PaymentController::class,'esewaPayFailure'])->name('payment.failure');
}); 

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['IsDoctorOrAdmin'])->group(function () {

    Route::get('/appointment/edit/{id}', [AppointmentController::class,'edit'])->name('appointment.edit');  
    Route::put('/appointment/update/{id}', [AppointmentController::class,'update'])->name('appointment.update'); 
    
    Route::get('/payment/create{appointment}', [PaymentController::class,'create'])->name('payment.create');
});


require __DIR__.'/auth.php';
