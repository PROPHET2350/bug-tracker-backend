<?php

namespace App\Controller;

use App\Services\ProjectService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    #[Route('/project/add', name: 'add project', methods: ['POST'])]
    public function AddProjectControllerAction(ProjectService $projectService, Request $request): Response
    {
        [$project, $error] = $projectService->AddProject($request);

        if ($error) {
            return $this->json([
                'error' => Response::HTTP_INTERNAL_SERVER_ERROR
            ]);
        }

        return $this->json([
            'project' => $project,
            'status' => Response::HTTP_CREATED
        ]);
    }
}