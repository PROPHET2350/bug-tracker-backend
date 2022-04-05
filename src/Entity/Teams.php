<?php

namespace App\Entity;

use App\Repository\TeamsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamsRepository::class)]
class Teams
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Users::class, cascade: ['refresh'])]
    #[ORM\JoinTable(name: 'teams_users')]
    #[ORM\JoinColumn(name: 'team_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    /**
     * @var Collection<Users->getId(), Users>
     */
    private Collection $users;

    public function __construct(string $id, string $name, array $user)
    {
        $this->id = $id;
        $this->name = $name;
        $this->users = new ArrayCollection($user);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
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

    public function updateTeamName($name): void
    {
        $this->name = $name;
    }
}