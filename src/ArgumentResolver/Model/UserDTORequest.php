<?php

namespace App\ArgumentResolver\Model;

use App\ArgumentResolver\RequestDTORepository;
use App\Repository\RolesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserDTORequest implements RequestDTORepository,  PasswordAuthenticatedUserInterface
{
    /**
     * @Assert\NotBlank()
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=4)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     */
    private $roles;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->id = $data['id'] ?? null;
        $this->username = $data['username'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->roles = $data['roles'] ?? null;
    }



    /**
     * Get the value of roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Get the value of password
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    public function UserConstructorFromUserDTORequest(RolesRepository $rolesRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $roles = [];
        foreach ($this->roles as $id) {
            if (!$rolesRepository->find($id)) {
                throw new BadRequestException("Role with id " . $id . " does not exist");
            }
            array_push($roles, $rolesRepository->find($id));
        }
        $pass = $passwordHasher->hashPassword($this, $this->password);
        $user = new Users($this->id, $this->username, $pass, $roles);
        return $user;
    }
    /**
     * Get the value of username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }
}