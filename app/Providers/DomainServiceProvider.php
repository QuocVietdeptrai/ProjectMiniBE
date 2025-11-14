<?php

namespace App\Providers;

use App\Domain\Auth\Domain\Repository\UserRepositoryInterface;
use App\Domain\Auth\Domain\Service\AuthTokenServiceInterface;
use App\Domain\Auth\Domain\Service\OtpServiceInterface;
use App\Domain\Auth\Infrastructure\DbUserInfrastructure;
use App\Domain\Auth\Infrastructure\JwtAuthTokenService;
use App\Domain\Auth\Infrastructure\OtpService;
use App\Domain\Order\Domain\Repository\OrderRepository;
use App\Domain\Order\Infrastructure\DbOrderInfrastructure;
use App\Domain\Product\Domain\Repository\ProductRepository;
use App\Domain\Product\Infrastructure\DbProductInfrastructure;
use App\Domain\Student\Domain\Repository\StudentRepository;
use App\Domain\Student\Infrastructure\DbStudentInfrastructure;
use App\Domain\User\Domain\Repository\UserRepository;
use App\Domain\User\Infrastructure\DbUserInfrastructure as DbUserInfrastructureUserNew;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProductRepository::class,
            DbProductInfrastructure::class
        );

        $this->app->bind(
            StudentRepository::class,
            DbStudentInfrastructure::class
        );

        $this->app->bind(
            OrderRepository::class,
            DbOrderInfrastructure::class
        );
        $this->app->bind(
            UserRepositoryInterface::class, 
            DbUserInfrastructure::class
        );
        $this->app->bind(
            AuthTokenServiceInterface::class,
            JwtAuthTokenService::class
        );

        // Bind OtpService
        $this->app->bind(
            OtpServiceInterface::class,
            OtpService::class
        );
        $this->app->bind(
            UserRepository::class,
            DbUserInfrastructureUserNew::class
        );
    }
}
