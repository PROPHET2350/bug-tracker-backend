<?php

namespace App\Entity;

use App\Repository\TicketsRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketsRepository::class)]
class Tickets
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $description;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\Column(type: 'string', length: 255)]
    private string $state;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $date;

    #[ORM\Column(type: 'string', length: 255)]
    private string $priority;

    #[ORM\ManyToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(nullable: false, name: 'project_id')]
    private Project $project;

    public function __construct(
        string $id,
        string $name,
        string $description,
        string $type,
        string $state,
        DateTimeInterface $date,
        string $priority,
        Project $project
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
        $this->state = $state;
        $this->date = $date;
        $this->priority = $priority;
        $this->project = $project;
    }

    public function getId(): ?int
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function getPriority(): ?string
    {
        return $this->priority;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }
}