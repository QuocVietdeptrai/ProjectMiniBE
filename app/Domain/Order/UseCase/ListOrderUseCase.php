<?php

namespace App\Domain\Order\UseCase;

use App\Domain\Order\Domain\Repository\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListOrderUseCase
{
    public function __construct(protected OrderRepository $repository) {}

    public function execute(array $filters = [], int $perPage = 5): LengthAwarePaginator
    {
        return $this->repository->paginate($filters, $perPage);
    }
}