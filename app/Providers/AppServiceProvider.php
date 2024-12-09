<?php

namespace App\Providers;

use Auth;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Review;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Repositories\DoctorRepository;
use App\Repositories\PatientRepository;
use App\Repositories\ReviewRepository;
use Dedoc\Scramble\Scramble;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\DoctorService;
use App\Services\ReviewService;
use App\Services\PatientService;

use App\Services\PaymentService;
use App\Services\ServiceService;
use App\Services\RegisterService;
use App\Services\ScheduleService;
use App\Repositories\UserRepository;
use App\Services\AppointmentService;
use App\Repositories\ServiceRepository;
use App\Services\SpecializationService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ScheduleRepository;
use Dedoc\Scramble\Support\Generator\OpenApi;
use App\Repositories\SpecializationRepository;
use Dedoc\Scramble\Support\Generator\SecurityScheme;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepository::class, function ($app) {
            return new UserRepository();
        });

        $this->app->singleton(Auth::class, function ($app) {
            return new AuthService();
        });

        $this->app->singleton(AppointmentService::class, function ($app) {
            return new AppointmentService($app->make(PaymentService::class));
        });

        $this->app->singleton(DoctorService::class, function ($app) {
            return new DoctorService($app->make(DoctorRepository::class));
        });

        $this->app->singleton(PatientService::class, function ($app) {
            return new PatientService($app->make(PatientRepository::class));
        });

        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService();
        });

        $this->app->singleton(RegisterService::class, function ($app) {
            return new RegisterService($app->make(UserRepository::class));
        });

        $this->app->singleton(ReviewService::class, function ($app) {
            return new ReviewService($app->make(ReviewRepository::class));
        });

        $this->app->singleton(ScheduleService::class, function ($app) {
            return new ScheduleService($app->make(ScheduleRepository::class));
        });

        $this->app->singleton(ServiceService::class, function ($app) {
            return new ServiceService($app->make(ServiceRepository::class));
        });

        $this->app->singleton(SpecializationService::class, function ($app) {
            return new SpecializationService($app->make(SpecializationRepository::class));
        });

        $this->app->singleton(UserService::class, function ($app) {
            return new UserService();
        });


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::afterOpenApiGenerated(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}
