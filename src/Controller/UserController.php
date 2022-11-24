<?php

namespace App\Controller;

use App\ArgumentResolver\Model\UserDTORequest;
use App\Repository\UsersRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RolesRepository;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->json($user, Response::HTTP_OK);
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
        return $this->json($updatedUser, Response::HTTP_OK);
    }

    #[Route('/user/{id}', name: 'delete user', methods: ['DELETE'])]
    public function deleteAccount(string $id)
    {
        [$user, $error] = $this->userService->DeleteUser($id);

        if ($error) {
            return $this->json($error, Response::HTTP_BAD_REQUEST);
        }

        return $this->json($user, Response::HTTP_OK);
    }

    #[Route('/users', name: 'get all users', methods: ['GET'])]
    public function getUsers(UsersRepository $usersRepository): Response
    {
        $users = $usersRepository->findAll();
        $usersResponse = [];

        foreach ($users as $user) {
            array_push($usersResponse, $user->getEscensialInformations());
        }

        return $this->json($usersResponse, Response::HTTP_OK);
    }
    #[Route('/user/{id}', name: 'get one user', methods: ['GET'])]
    public function getUserById(UsersRepository $usersRepository, string $id): Response
    {
        $users = $usersRepository->find($id);

        return $this->json($users, Response::HTTP_OK);
    }

    #[Route('/user-reset/{id}', name: 'update user password', methods: ['PUT'])]
    public function updatePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UsersRepository $usersRepository,
        string $id
    ) {
        $password = $request->toArray();
        $hashedPassword = $passwordHasher->hashPassword(
            $usersRepository->find($id),
            $password['password']
        );
        $updatedUser = $this->userService->UpdatePassword($id, $hashedPassword);
        return $this->json($updatedUser, Response::HTTP_OK);
    }
}