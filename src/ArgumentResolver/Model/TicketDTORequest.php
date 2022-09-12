<?php

namespace App\ArgumentResolver\Model;

use App\ArgumentResolver\RequestDTORepository;
use App\Entity\Tickets;
use App\Repository\ProjectRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TicketDTORequest implements RequestDTORepository
{
    /**
     * @Assert\NotBlank()
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=4)
     */
    private $name;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private $description;
    /**
     * @Assert\NotBlank()
     */
    private $type;
    /**
     * @Assert\NotBlank()
     */
    private $state;
    /**
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $date;
    /**
     * @Assert\NotBlank()
     */
    private $priority;
    /**
     * @Assert\NotBlank()
     */
    private $project;
    /**
     * @Assert\NotBlank()
     */
    private $user;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->id = $data["id"] ?? null;
        $this->name = $data["name"] ?? null;
        $this->description = $data["description"] ?? null;
        $this->type = $data["type"] ?? null;
        $this->state = $data["state"] ?? null;
        $this->date = $data["date"] ?? null;
        $this->priority = $data["priority"] ?? null;
        $this->project = $data["project"] ?? null;
        $this->user = $data["user"] ?? null;
    }

    /**
     * Get the value of user
     */

    public function createTicketFromTicketDTORequest(UsersRepository $usersRepository, ProjectRepository $projectRepository)
    {
        $user = $usersRepository->find($this->user);
        $project = $projectRepository->find($this->project);

        if (!$user) {
            return new BadRequestException("User with id {$this->user} does not exist");
        }

        if (!$project) {
            return new BadRequestException("Project with id {$this->project} does not exist");
        }
        $date = new DateTime();
        $date->format("Y-m-d H:i:s");

        $ticket = new Tickets(
            $this->id,
            $this->name,
            $this->description,
            $this->type,
            $this->state,
            $date,
            $this->priority,
            $project,
            $user
        );
        return $ticket;
    }
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the value of project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Get the value of priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }
}