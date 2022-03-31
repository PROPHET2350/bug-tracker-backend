<?php

namespace App\Form\Model;

use App\Entity\Users;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UsersDTO implements PasswordAuthenticatedUserInterface
{
    public $id;

    public $username;

    public $password;

    public $roles;

    public function __construct()
    {
        $this->roles = [];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public static function createFromUserEntity(Users $user): self
    {
        $dto = new self();
        $dto->id = $user->getId();
        $dto->username = $user->getUsername();
        $dto->password = $user->getPassword();
        $dto->roles = $user->getRoles();

        return $dto;
    }
}