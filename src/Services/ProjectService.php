<?php

namespace App\Services;

use App\Entity\Project;
use App\Form\Model\ProjectDTO;
use App\Form\Type\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

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
        $content = json_decode($request->getContent(), true);
        $form->submit($content);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if (!$form->isValid()) {
            return [null, 'Form is not valid'];
        }

        $project = new Project($ProjectDTO->id, $ProjectDTO->name);
        $this->projectRepository->save($project, true);
        return [$project, null];
    }
}