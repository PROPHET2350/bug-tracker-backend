<?php

namespace App\Services;

use App\Entity\Project;
use App\Entity\Users;
use App\Repository\ProjectRepository;
use App\Repository\UsersRepository;

class ProjectService
{

    public function __construct(
        private ProjectRepository $projectRepository,
        private UsersRepository $userRepository,
    ) {
    }
    public function AddProject(Project $project): void
    {
        $this->projectRepository->save($project, true);
    }
    public function findProjectsById(string $id): Project
    {
        return $this->projectRepository->find($id);
    }
    public function findAllProjects(): array
    {
        return $this->projectRepository->findAll();
    }
    public function findProjectsByUser(Users $user): array
    {
        $projects = $this->projectRepository->findAll();
        $p = [];
        foreach ($projects as $project) {
            if ($project->getUsers()->contains($user)) {
                array_push($p, $project);
            }
        }
        $userProjects = [];
        foreach ($p as $project) {
            array_push($userProjects, array('id' => $project->getId(), 'name' => $project->getName()));
        }
        return $userProjects;
    }

    public function deleteProject(string $id)
    {
        $project = $this->projectRepository->find($id);
        $this->projectRepository->remove($project);
        return $project;
    }

    public function updateUsers(string $id, array $users)
    {
        $usersToUpdate = array();
        foreach ($users as $user) {
            array_push($usersToUpdate, $this->userRepository->find($user));
        }
        $project = $this->projectRepository->find($id);
        $project->updateUsersService($usersToUpdate);
        $this->projectRepository->save($project);
        return $project;
    }
}