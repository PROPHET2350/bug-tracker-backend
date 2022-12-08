<?php

namespace App\DataFixtures;

use App\Entity\Roles;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $rol1 = new Roles("1", "ROLE_ADMIN");
        $rol2 = new Roles("2", "ROLE_USER");
        $roles = [$rol1];
        $manager->persist($rol1);
        $manager->persist($rol2);
        $manager->flush();
        $user = new Users('1', 'root', 'root1234', $roles);
        $pass = $this->passwordHasher->hashPassword($user, 'root1234');
        $userToSave = new Users('1', 'root', $pass, $roles);
        $manager->persist($userToSave);
        $manager->flush();
    }
}