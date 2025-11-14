<?php 

namespace App\Domain\User\Usecase;

use App\Domain\User\Domain\Repository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteUserUseCase
{
    public function __construct(private UserRepository $repo) {}

    public function __invoke(int $id): bool
    {
        if(!$this->repo->find($id)){
            throw new ModelNotFoundException('Người dùng không tồn tại');
        }
        return $this->repo->delete($id);
    }
}