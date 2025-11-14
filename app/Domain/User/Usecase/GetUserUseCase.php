<?php 

namespace App\Domain\User\Usecase;

use App\Domain\Auth\Domain\Entity\UserEntity;
use App\Domain\User\Domain\Repository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetUserUseCase
{
    public function __construct(private UserRepository $repo){}

    public function __invoke(int $id): UserEntity
    {
        $user = $this->repo->find($id);
        if(!$user){
            throw new ModelNotFoundException('Người dùng không tồn tại');
        }
        return $user;
    }
}
