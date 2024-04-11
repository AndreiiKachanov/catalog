<?php

namespace App\Providers;

use App\Services\TestService;
use App\Services\TestServiceToFacade;
use App\UseCases\ApiService;
use App\UseCases\MessangerNotificatorInterface;
use App\UseCases\SendOrderService;
use App\UseCases\TelegramNotificator;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Generator::class, function() {
            $faker = Factory::create('ru_RU');
            $faker->addProvider(new FakerImageProvider($faker));
            return $faker;
        });

        $this->app->bind(TestService::class, function () {
            return new TestService(true);
        });

        $this->app->bind(MessangerNotificatorInterface::class, TelegramNotificator::class);

        //$this->app->singleton(ApiService::class, function ($app) {
        //    return new ApiService($app->make(SendOrderService::class));
        //});
        //$this->app->bind(ApiService::class, function ($app) {
        //    return new ApiService($app->make(SendOrderService::class));
        //});
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFour();
        //$this->app->singleton(TestServiceToFacade::class, function () {
        //
        //    return new TestServiceToFacade('token123');
        //});

    }
}
