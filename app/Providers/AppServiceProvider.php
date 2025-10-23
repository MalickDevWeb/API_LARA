<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CompteRepositoryInterface;
use App\Interfaces\CompteServiceInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Interfaces\TransactionServiceInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\UserServiceInterface;
use App\Repositories\CompteRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\CompteService;
use App\Services\TransactionService;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(CompteRepositoryInterface::class, CompteRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Bind service interfaces to implementations
        $this->app->bind(CompteServiceInterface::class, CompteService::class);
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
