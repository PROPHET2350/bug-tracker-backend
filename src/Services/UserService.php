<?php

namespace App\Services;

use App\Entity\Users;
use App\Repository\UsersRepository;

class UserService
{
    public function __construct(
        private UsersRepository $userRepository,
    ) {
    }

    public function AddUser(Users $user): void
    {
        $this->userRepository->add($user);
    }

    public function findByUsername(string $username): Users
    {
        return $this->userRepository->findOneBy(['username' => $username]);
    }
    public function UpdateUser(Users $userWithUpdatedRoles, string $id): Users
    {
        $userToUpdate = $this->userRepository->find($id);

        $userToUpdate->update(
            $userWithUpdatedRoles->getUsername(),
            $userWithUpdatedRoles->getRole()->toArray()
        );
        $this->userRepository->add($userToUpdate, true);
        return $userToUpdate;
    }

    public function DeleteUser(string $id): array
    {
        $userFinder = $this->userRepository->find($id);

        if (!$userFinder) {
            return [null, 'User not found'];
        }
        $this->userRepository->remove($userFinder);

        return [$userFinder, null];
    }

    public function UpdatePassword(string $id, string $password)
    {
        $user = $this->userRepository->find($id);
        $user->resetPassword($password);
        $this->userRepository->add($user, true);

        return $user;
    }
}