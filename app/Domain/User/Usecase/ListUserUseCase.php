<?php 

namespace App\Domain\User\Usecase;

use App\Domain\User\Domain\Repository\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUserUseCase
{
    public function __construct(private UserRepository $repo){}

    public function __invoke(?string $search = null, int $perPage = 4): LengthAwarePaginator
    {
        return $this->repo->paginate($search,$perPage);
    }
}