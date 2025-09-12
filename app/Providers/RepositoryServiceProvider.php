<?php

namespace App\Providers;

use App\Repositories\Contracts\PetRepositoryInterface;
use App\Repositories\PetRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PetRepositoryInterface::class, PetRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
