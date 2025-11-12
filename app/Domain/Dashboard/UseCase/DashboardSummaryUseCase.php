<?php

namespace App\Domain\Dashboard\UseCase;

use App\Domain\Product\Domain\Repository\ProductRepository;
use App\Domain\Student\Domain\Repository\StudentRepository;
use App\Domain\Order\Domain\Repository\OrderRepository;

class DashboardSummaryUseCase
{
    public function __construct(
        private ProductRepository $productRepo,
        private StudentRepository $studentRepo,
        private OrderRepository $orderRepo
    ) {}

    public function __invoke(): array
    {
        return [
            'products' => $this->productRepo->count(),
            'students' => $this->studentRepo->count(),
            'orders'   => $this->orderRepo->count(),
        ];
    }
}