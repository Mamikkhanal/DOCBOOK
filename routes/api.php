<?php

use App\Models\Review;

use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\SlotController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\ScheduleController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\SpecializationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {


    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });


    Route::name('api.')->group(function () {

        Route::middleware('auth:sanctum')->group(function () {
            
            Route::get('/services', [ServiceController::class, 'index']);
            Route::get('/specializations', [SpecializationController::class, 'index']);
            Route::get('/schedules', [ScheduleController::class, 'index']);
            Route::get('/slots', [SlotController::class, 'index']);

            Route::get('/appointments', [AppointmentController::class, 'index']);
            Route::get('/appointments/search', [AppointmentController::class, 'search']);
            Route::get('/appointments/{appointment}', [AppointmentController::class, 'show']);

            Route::get('/payments/{id}/pay', [PaymentController::class, 'pay'])->name('payment.pay');
            Route::get('/payments/success', [PaymentController::class, 'success'])->name('payment.success');
            Route::get('/payments/failure', [PaymentController::class, 'failure'])->name('payment.failure');


            Route::middleware('IsAdmin')->group(function () {

                Route::post('/services', [ServiceController::class, 'store']);
                Route::get('/services/{service}', [ServiceController::class, 'show']);
                Route::put('/services/{service}', [ServiceController::class, 'update']);
                Route::delete('/services/{service}', [ServiceController::class, 'destroy']);

                Route::post('/specializations', [SpecializationController::class, 'store']);
                Route::get('/specializations/{specialization}', [SpecializationController::class, 'show']);
                Route::put('/specializations/{specialization}', [SpecializationController::class, 'update']);
                Route::delete('/specializations/{specialization}', [SpecializationController::class, 'destroy']);

            });

            Route::middleware('IsDoctor')->group(function () {

                Route::post('/schedules', [ScheduleController::class, 'store']);
                Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);
                Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
                Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);

            });
            
            Route::middleware('IsDoctorOrAdmin')->group(function () {
                
                Route::put('/appointments/{appointment}', [AppointmentController::class, 'update']);
                Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy']); 
                
                Route::post('/payments', [PaymentController::class, 'store']);
            });
            
            Route::middleware('IsPatient')->group(function () {
                
                Route::post('/appointments', [AppointmentController::class, 'store']);

                Route::apiResource('/reviews', ReviewController::class);
            });
        });
    });
});
