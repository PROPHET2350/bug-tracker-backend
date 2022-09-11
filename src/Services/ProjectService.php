<?php

namespace App\Services;

use App\Entity\Project;
use App\Repository\ProjectRepository;

class ProjectService
{

    public function __construct(
        private ProjectRepository $projectRepository,
    ) {
    }
    public function AddProject(Project $project): void
    {
        $this->projectRepository->save($project, true);
    }
}