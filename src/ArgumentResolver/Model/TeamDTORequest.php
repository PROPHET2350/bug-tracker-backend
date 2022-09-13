<?php

namespace App\ArgumentResolver\Model;

use App\ArgumentResolver\RequestDTORepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Teams;

class TeamDTORequest implements RequestDTORepository
{
    /**
     * @Assert\NotBlank()
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    private $name;
    /**
     * @Assert\NotBlank()
     */
    private $users;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
        $this->users = $data['users'] ?? null;
    }


    public function findUsersFromTeamDTORequest(UsersRepository $usersRepository)
    {
        $usersFind = [];
        foreach ($this->users as $user) {
            if (!$usersRepository->find($user)) {
                throw new BadRequestException("User with id {$user} not found");
            }
            array_push($usersFind, $usersRepository->find($user));
        }
        return new Teams($this->id, $this->name, $usersFind);
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of users
     */
    public function getUsers()
    {
        return $this->users;
    }
}