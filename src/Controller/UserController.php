<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/user/add', name: 'add user', methods: ['POST'])]
    public function createAccount(Request $request): Response
    {
        [$user, $error] = $this->userService->AddUser($request);
        if ($error != null) {
            return new Response($error, Response::HTTP_BAD_REQUEST);
        }

        return new Response($this->json($user), Response::HTTP_CREATED);
    }

    #[Route('/user/{id}', name: 'update user', methods: ['PUT'])]
    public function updateAccount(Request $request, string $id)
    {
        [$user, $error] = $this->userService->UpdateUser($request, $id);

        if ($error != null) {
            return new Response($error, Response::HTTP_BAD_REQUEST);
        }

        return new Response($this->json($user), Response::HTTP_OK);
    }

    #[Route('/users', name: 'delete user', methods: ['GET'])]
    public function getUsers(UsersRepository $usersRepository): Response
    {
        $users = $usersRepository->findAll();
        return new Response($this->json($users), Response::HTTP_OK);
    }
}