<?php

namespace App\Providers;

use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Infrastructure\DbUserInfrastructure;
use Illuminate\Support\ServiceProvider;

class AuthDomainServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, DbUserInfrastructure::class);
    }
}