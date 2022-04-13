<?php

namespace App\Services;

use App\Entity\Teams;
use App\Entity\Users;
use App\Form\Model\TeamDTO;
use App\Form\Type\TeamFormType;
use App\Repository\TeamsRepository;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

class TeamService
{
    public function __construct(
        private TeamsRepository $teamsRepository,
        private UsersRepository $usersRepository,
        private FormFactoryInterface $formFactoryInterface
    ) {
    }

    public function addTeam(Request $request): array
    {
        $teamDTO = new TeamDTO();
        $form = $this->formFactoryInterface->create(TeamFormType::class, $teamDTO);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if (!$form->isValid()) {
            return [null, 'Form is not valid'];
        }
        $userInTeam = [];
        foreach ($teamDTO->users as $key) {
            $userInTeam[] = $this->usersRepository->find($key->id);
        }
        $teamDTO->users = $userInTeam;
        $team = new Teams($teamDTO->id, $teamDTO->name, $teamDTO->users);
        $this->teamsRepository->add($team);
        return [$team, null];
    }

    public function addUserToTeam(string $teamId, Request $request): void
    {
        $userToAdd = [];
        $team = $this->teamsRepository->find($teamId);
        $content = json_decode($request->getContent(), true);

        for ($i = 0; $i < count($content); $i++) {
            $userToAdd[] = $this->usersRepository->find($content[$i]['id']);
        }

        $team->updateUsers($userToAdd);
        $this->teamsRepository->add($team);
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