<?php

namespace App\Controller;

use App\Services\TeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{

    private TeamService $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    #[Route('/team/add', name: 'add team', methods: ['POST'])]
    public function createTeam(Request $request): Response
    {
        [$team, $error] = $this->teamService->addTeam($request);

        if ($error != null) {
            return new Response($error, Response::HTTP_BAD_REQUEST);
        }

        return new Response($this->json($team), Response::HTTP_CREATED);
    }

    #[Route('/team/{id}/add-user', name: 'add user to team', methods: ['POST'])]
    public function addUserToTeam(string $id, Request $request): Response
    {
        $this->teamService->addUserToTeam($id, $request);
        return new Response("team's users modifying successfully", Response::HTTP_ACCEPTED);
    }
}