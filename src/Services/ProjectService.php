<?php

namespace App\Services;

use App\Entity\Project;
use App\Form\Model\ProjectDTO;
use App\Form\Type\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ProjectService
{

    public function __construct(
        private ProjectRepository $projectRepository,
        private FormFactoryInterface $formFactory
    ) {
    }

    public function AddProject(Request $request): array
    {
        $ProjectDTO = new ProjectDTO();
        $form = $this->formFactory->create(ProjectType::class, $ProjectDTO);
        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return [null, 'invalid form submitted'];
        }
        $project = new Project($ProjectDTO->getId(), $ProjectDTO->getName());
        $this->projectRepository->save($project, true);
        return [$project, null];
    }
}