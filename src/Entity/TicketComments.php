<?php

namespace App\Entity;

use App\Repository\TicketCommentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketCommentsRepository::class)]
class TicketComments
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\ManyToOne(targetEntity: Users::class, cascade: ['all'])]
    #[ORM\JoinColumn(nullable: false, name: 'user_id', onDelete: 'CASCADE')]
    private Users $author;

    #[ORM\ManyToOne(targetEntity: Tickets::class, cascade: ['all'])]
    #[ORM\JoinColumn(nullable: false, name: 'ticket_id', onDelete: 'CASCADE')]
    private Tickets $ticket;

    public function __construct(string $id, string $name, string $description, Users $author, Tickets $ticket)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->author = $author;
        $this->ticket = $ticket;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAuthor(): ?Users
    {
        return $this->author;
    }

    public function getTicket(): ?Tickets
    {
        return $this->ticket;
    }

    public function getEscensialInformations(): array
    {
        $EscensialInformations = array();
        $EscensialInformations['id'] = $this->id;
        $EscensialInformations['name'] = $this->name;
        $EscensialInformations['description'] = $this->description;
        $EscensialInformations['author'] = $this->author->getUsername();
        return $EscensialInformations;
    }
}