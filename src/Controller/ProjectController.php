<?php

namespace App\Controller;

use App\ArgumentResolver\Model\ProjectDTORequest;
use App\Services\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Project;

class ProjectController extends AbstractController
{

    #[Route('/project/add', name: 'add project', methods: ['POST'])]
    public function AddProjectControllerAction(ProjectDTORequest $projectDtoRequest, ProjectService $projectService): JsonResponse
    {
        $project = new Project($projectDtoRequest->getId(), $projectDtoRequest->getName());
        $projectService->AddProject($project);
        return new JsonResponse($projectDtoRequest);
    }
}