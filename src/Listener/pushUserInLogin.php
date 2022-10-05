<?php

namespace App\Listener;

use App\Services\TeamService;
use App\Services\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class pushUserInLogin
{
    private RequestStack $RequestStack;
    private UserService $UserService;
    private TeamService $TeamService;
    public function __construct(RequestStack $RequestStack, UserService $UserService, TeamService $TeamService)
    {
        $this->RequestStack = $RequestStack;
        $this->UserService = $UserService;
        $this->TeamService = $TeamService;
    }
    public function getUser(AuthenticationSuccessEvent $AuthenticationSuccessEvent)
    {
        $data = $AuthenticationSuccessEvent->getData();
        $username = json_decode($this->RequestStack->getCurrentRequest()->getContent())->username;
        $user = $this->UserService->findByUsername($username);
        $teams = $this->TeamService->findTeamsByUser($user);
        $userResponse = [
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
            'teams' => $teams
        ];
        $AuthenticationSuccessEvent->setData([
            'token' => $data['token'],
            'user' => $userResponse
        ]);
    }
}