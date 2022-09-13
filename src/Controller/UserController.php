<?php

namespace App\Controller;

use App\ArgumentResolver\Model\UserDTORequest;
use App\Repository\UsersRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RolesRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    #[Route('/user/add', name: 'add user', methods: ['POST'])]
    public function createAccount(
        UserDTORequest $userDTO,
        RolesRepository $rolesRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = $userDTO->UserConstructorFromUserDTORequest($rolesRepository, $passwordHasher);
        $this->userService->AddUser($user);
        return new Response($this->json($user), Response::HTTP_CREATED);
    }

    #[Route('/user/{id}', name: 'update user', methods: ['PUT'])]
    public function updateAccount(
        UserDTORequest $userDTO,
        RolesRepository $rolesRepository,
        UserPasswordHasherInterface $passwordHasher,
        string $id
    ) {
        $newUser = $userDTO->UserConstructorFromUserDTORequest($rolesRepository, $passwordHasher);
        $updatedUser = $this->userService->UpdateUser($newUser, $id);
        return new Response($this->json($updatedUser), Response::HTTP_OK);
    }

    #[Route('/user/{id}', name: 'delete user', methods: ['DELETE'])]
    public function deleteAccount(string $id)
    {
        [$user, $error] = $this->userService->DeleteUser($id);

        if ($error) {
            return new Response($error, Response::HTTP_NOT_FOUND);
        }

        return new Response($this->json($user), Response::HTTP_OK);
    }

    #[Route('/users', name: 'get all users', methods: ['GET'])]
    public function getUsers(UsersRepository $usersRepository): Response
    {
        $users = $usersRepository->findAll();
        return new Response($this->json($users), Response::HTTP_OK);
    }
}