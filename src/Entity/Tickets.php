<?php

namespace App\Entity;

use App\Repository\TicketsRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[ORM\JoinColumn(nullable: false, name: 'project_id', onDelete: 'CASCADE')]
    private Project $project;

    #[ORM\ManyToOne(targetEntity: Users::class, cascade: ['refresh'])]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Users $user;

    #[ORM\ManyToMany(targetEntity: Users::class, cascade: ['refresh'])]
    #[ORM\JoinTable(name: 'tickets_users')]
    #[ORM\JoinColumn(name: 'ticket_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    /**
     * @var Collection<Users->getId(), Users>
     */
    private Collection $users;

    public function __construct(
        string $id,
        string $name,
        string $description,
        string $type,
        string $state,
        DateTimeInterface $date,
        string $priority,
        Project $project,
        Users $user,
        array $users
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->type = $type;
        $this->state = $state;
        $this->date = $date;
        $this->priority = $priority;
        $this->project = $project;
        $this->user = $user;
        $this->users = new ArrayCollection($users);
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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    /**
     * @return Collection<Users->getId(), Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }


    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @param Users|array $users
     */

    public function updateUsers(array $users)
    {
        $originalUsers = new ArrayCollection();
        foreach ($this->users as $user) {
            $originalUsers->add($user);
        }


        foreach ($originalUsers as $originalUser) {
            if (!in_array($originalUser, $users)) {
                $this->removeUser($originalUser);
            }
        }

        foreach ($users as $newUser) {
            if (!$originalUsers->contains($newUser)) {
                $this->addUser($newUser);
            }
        }
    }
    public function getEscensialInformations(): array
    {
        $EscensialInformations = array();
        $EscensialInformations['id'] = $this->id;
        $EscensialInformations['name'] = $this->name;
        $EscensialInformations['description'] = $this->description;
        $EscensialInformations['type'] = $this->type;
        $EscensialInformations['state'] = $this->state;
        $EscensialInformations['date'] = $this->date;
        $EscensialInformations['priority'] = $this->priority;
        $EscensialInformations['project'] = $this->project->getName();
        $EscensialInformations['user'] = $this->user->getUsername();
        $EscensialInformations['users'] = [];
        foreach ($this->users as $key) {
            array_push($EscensialInformations['users'], array('id' => $key->getId(), 'name' => $key->getUsername()));
        }

        return $EscensialInformations;
    }
    public function getInformations(): array
    {
        $EscensialInformations = array();
        $EscensialInformations['id'] = $this->id;
        $EscensialInformations['name'] = $this->name;
        $EscensialInformations['description'] = $this->description;
        $EscensialInformations['type'] = $this->type;
        $EscensialInformations['state'] = $this->state;
        $EscensialInformations['date'] = $this->date;
        $EscensialInformations['priority'] = $this->priority;
        $EscensialInformations['project'] = $this->project->getId();
        $EscensialInformations['user'] = $this->user->getId();
        $EscensialInformations['users'] = [];
        foreach ($this->users as $key) {
            array_push($EscensialInformations['users'], array('id' => $key->getId(), 'name' => $key->getUsername()));
        }

        return $EscensialInformations;
    }

    public function updateTicket(
        string $state,
        string $type,
        string $description,
        string $priority,
        string $name,
        array $users
    ) {
        $this->state = $state;
        $this->type = $type;
        $this->description = $description;
        $this->priority = $priority;
        $this->name = $name;
        $this->updateUsers($users);
    }
}