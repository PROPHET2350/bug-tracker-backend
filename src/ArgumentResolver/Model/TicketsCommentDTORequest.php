<?php

namespace App\ArgumentResolver\Model;

use App\ArgumentResolver\RequestDTORepository;
use App\Repository\TicketsRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Entity\TicketComments;
use Symfony\Component\Validator\Constraints as Assert;

class TicketsCommentDTORequest implements RequestDTORepository
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
     */
    private $description;
    /**
     * @Assert\NotBlank()
     */
    private $author;
    /**
     * @Assert\NotBlank()
     */
    private $ticket;
    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->author = $data['author'];
        $this->ticket = $data['ticket'];
    }

    public function createTicketsCommentFromTicketCommentDTORequest(
        UsersRepository $usersRepository,
        TicketsRepository $ticketsRepository
    ) {
        $user = $usersRepository->find($this->author);

        if (!$user) {
            return new BadRequestException("User with ID {$this->author} does not exists");
        }

        $ticket = $ticketsRepository->find($this->ticket);

        if (!$ticket) {
            return new BadRequestException("Ticket with ID {$this->ticket} does not exists");
        }

        return new TicketComments($this->id, $this->name, $this->description, $user, $ticket);
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
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get the value of author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get the value of ticket
     */
    public function getTicket()
    {
        return $this->ticket;
    }
}