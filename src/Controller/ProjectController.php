<?php

namespace App\Controller;

use App\ArgumentResolver\Model\ProjectDTORequest;
use App\Services\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends AbstractController
{

    #[Route('/project/add', name: 'add project', methods: ['POST'])]
    public function AddProjectControllerAction(
        ProjectDTORequest $projectDtoRequest,
        UsersRepository $usersRepository,
        ProjectService $projectService
    ): Response {
        $project = $projectDtoRequest->findUsersFromProjectDTORequest($usersRepository);
        $projectService->AddProject($project);
        return $this->json($project, Response::HTTP_OK);
    }
    #[Route('/project/{id}', name: 'get Project', methods: ['GET'])]
    public function GetProject(
        string $id,
        ProjectService $projectService
    ): Response {
        $projects = $projectService->findProjectsById($id);
        return $this->json($projects->getUsersEscensialInformations(), Response::HTTP_OK);
    }
    #[Route('/project/{id}', name: 'delete Project', methods: ['DELETE'])]
    public function DeleteProject(
        string $id,
        ProjectService $projectService
    ): Response {
        $projects = $projectService->deleteProject($id);
        return $this->json($projects->getUsersEscensial(), Response::HTTP_OK);
    }
    #[Route('/projects', name: 'get all Project', methods: ['GET'])]
    public function GetProjects(
        ProjectService $projectService
    ): Response {
        $projects = $projectService->findAllProjects();
        $projectEsscencial = [];

        foreach ($projects as $project) {
            array_push($projectEsscencial, $project->getUsersEscensial());
        }

        return $this->json($projectEsscencial, Response::HTTP_OK);
    }
    #[Route('/project-users/{id}', name: 'add user to Project', methods: ['PUT'])]
    public function addUser(
        string $id,
        Request $request,
        ProjectService $projectService
    ): Response {
        $users = $request->toArray();
        $projects = $projectService->updateUsers($id, $users["users"]);
        return $this->json($projects->getUsersEscensial(), Response::HTTP_OK);
    }
}