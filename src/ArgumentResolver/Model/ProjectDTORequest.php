<?php

namespace App\ArgumentResolver\Model;

use App\ArgumentResolver\RequestDTORepository;
use App\Entity\Project;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectDTORequest implements RequestDTORepository
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
        $this->users = $data["users"] ?? null;
    }

    public function findUsersFromProjectDTORequest(UsersRepository $usersRepository)
    {
        $usersFind = [];
        foreach ($this->users as $user) {
            if (!$usersRepository->find($user)) {
                throw new BadRequestException("User with id {$user} not found");
            }
            array_push($usersFind, $usersRepository->find($user));
        }
        return new Project($this->id, $this->name, $usersFind);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get the value of user
     */
    public function getUser()
    {
        return $this->users;
    }
}