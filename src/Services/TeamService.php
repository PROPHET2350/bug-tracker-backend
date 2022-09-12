<?php

namespace App\Services;

use App\Entity\Teams;
use App\Repository\TeamsRepository;
use App\Repository\UsersRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TeamService
{
    public function __construct(
        private TeamsRepository $teamsRepository,
        private UsersRepository $usersRepository,
        private FormFactoryInterface $formFactoryInterface
    ) {
    }

    public function addTeam(Teams $team): void
    {
        $this->teamsRepository->add($team);
    }

    public function addUserToTeam(string $teamId, array $usersIds): mixed
    {
        $userToAdd = [];
        $team = $this->teamsRepository->find($teamId);

        foreach ($usersIds as $id) {
            $user = $this->usersRepository->find($id);
            if (!$user) {
                return new BadRequestException("User with ID {$id} not found");
            }
            array_push($userToAdd, $user);
        }
        $team->updateUsers($userToAdd);
        $this->teamsRepository->add($team);
        return $team;
    }

    public function getTeam(string $teamId): Teams
    {
        return $this->teamsRepository->find($teamId);
    }

    public function getAllTeams(): array
    {
        return $this->teamsRepository->findAll();
    }

    public function updateTeam(string $teamId, string $name): array
    {
        $team = $this->teamsRepository->find($teamId);

        if ($team === null) {
            return [null, 'Team not found'];
        }
        $team->updateTeamName($name);
        $this->teamsRepository->add($team);

        return [$team, null];
    }

    public function deleteTeam(string $teamId): void
    {
        $team = $this->teamsRepository->find($teamId);

        if ($team === null) {
            return;
        }
        $this->teamsRepository->remove($team);
    }
}