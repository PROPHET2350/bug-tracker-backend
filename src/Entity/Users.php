<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    private string $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\ManyToMany(targetEntity: Roles::class, cascade: ['persist'])]
    #[ORM\JoinTable(name: 'users_roles')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id')]
    /**
     * @var Collection<string,Roles>
     */
    private Collection $roles;

    public function __construct(string $id, string $username, string $password, array $role)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->roles = new ArrayCollection($role);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $userRoles = $this->getRole()->map(function (Roles $role) {
            return $role->getName();
        })->toArray();
        $roles = [];
        foreach ($userRoles as $role) {
            $roles[] = $role;
        }
        return array_unique($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @see UserInterface
     */

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    /**
     * @return Collection<string,Roles>
     */
    public function getRole(): Collection
    {
        return $this->roles;
    }

    public function addRole(Roles $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    public function removeRole(Roles $role): self
    {
        $this->roles->removeElement($role);

        return $this;
    }

    /**
     * @param array|Roles[] $role
     */

    public function updateCategories(array $roles)
    {
        $originalRoles = new ArrayCollection();
        foreach ($this->roles as $rol) {
            $originalRoles->add($rol);
        }


        foreach ($originalRoles as $originalRol) {
            if (!in_array($originalRol, $roles)) {
                $this->removeRole($originalRol);
            }
        }

        foreach ($roles as $newRol) {
            if (!$originalRoles->contains($newRol)) {
                $this->addRole($newRol);
            }
        }
    }
    /**
     * @param string $username
     * @param string $password
     * @param array|Roles[] $role
     * @return void
     */

    public function update(string $username, string $password, array $role)
    {
        $this->username = $username;
        $this->password = $password;
        $this->updateCategories($role);
    }
}