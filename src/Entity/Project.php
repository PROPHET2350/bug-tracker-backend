<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Users::class, cascade: ['refresh'])]
    #[ORM\JoinTable(name: 'projects_users')]
    #[ORM\JoinColumn(name: 'project_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
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
            $this->users->add($user);
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

    public function updateUsersService(array $users)
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

    public function getUsersEscensialInformations(): array
    {
        $users = [];

        foreach ($this->users as $user) {
            array_push($users, array('id' => $user->getId(), 'name' => $user->getUsername()));
        }

        return $users;
    }
    public function getUsersEscensial(): array
    {
        $project = array();
        $project['id'] = $this->id;
        $project['name'] = $this->name;
        $project['users'] = [];
        foreach ($this->users as $user) {
            array_push($project['users'], array('id' => $user->getId(), 'name' => $user->getUsername()));
        }

        return $project;
    }
}