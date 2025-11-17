<?php

namespace App\Domain\Order\Usecase;

use App\Domain\Order\Domain\Repository\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListOrderUseCase
{
    public function __construct(
        private OrderRepository $repository
    ) {}

    public function __invoke(?string $search = null, int $perPage = 4): LengthAwarePaginator
    {
        return $this->repository->paginate($search, $perPage);
    }
}