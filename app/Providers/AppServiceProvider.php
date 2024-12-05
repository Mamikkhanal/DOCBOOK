<?php

namespace App\Providers;

use App\Models\Service;
use App\Services\PaymentService;

use App\Services\ServiceService;
use App\Services\ScheduleService;
use App\Repositories\UserRepository;
use App\Services\AppointmentService;
use App\Repositories\ServiceRepository;
use App\Services\SpecializationService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ScheduleRepository;
use App\Repositories\SpecializationRepository;

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

        $this->app->singleton(AppointmentService::class, function ($app) {
            return new AppointmentService($app->make(PaymentService::class));
        });

        $this->app->singleton(ServiceService::class, function ($app) {
            return new ServiceService($app->make(ServiceRepository::class));
        });

        $this->app->singleton(SpecializationService::class, function ($app) {
            return new SpecializationService($app->make(SpecializationRepository::class));
        });

        $this->app->singleton(ScheduleService::class, function ($app) {
            return new ScheduleService($app->make(ScheduleRepository::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
