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

    #[Route('/team/{id}', name: 'update team', methods: ['PUT'])]
    public function updateTeam(string $id, Request $request): Response
    {
        $content = json_decode($request->getContent(), true);
        [$team, $error] = $this->teamService->updateTeam($id, $content['name']);

        if ($error != null) {
            return new Response($error, Response::HTTP_BAD_REQUEST);
        }

        return new Response($this->json($team), Response::HTTP_OK);
    }

    #[Route('/team/{id}', name: 'delete team', methods: ['DELETE'])]
    public function deleteTeam(string $id): Response
    {
        $this->teamService->deleteTeam($id);
        return new Response("team deleted successfully", Response::HTTP_ACCEPTED);
    }

    #[Route('/team/{id}', name: 'get team', methods: ['GET'])]
    public function getTeam(string $id): Response
    {
        $team = $this->teamService->getTeam($id);
        return new Response($this->json($team), Response::HTTP_OK);
    }

    #[Route('/teams', name: 'get all teams', methods: ['GET'])]
    public function getAllTeams(): Response
    {
        $teams = $this->teamService->getAllTeams();
        return new Response($this->json($teams), Response::HTTP_OK);
    }
}