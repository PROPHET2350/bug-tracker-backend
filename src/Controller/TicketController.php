<?php

namespace App\Controller;

use App\ArgumentResolver\Model\TicketDTORequest;
use App\Repository\ProjectRepository;
use App\Repository\UsersRepository;
use App\Services\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{

    public function __construct(private TicketService $ticketService)
    {
    }

    #[Route('/ticket/add', name: 'add ticket', methods: ['POST'])]
    public function addTicket(
        TicketDTORequest $ticketDtoRequest,
        UsersRepository $usersRepository,
        ProjectRepository $projectRepository
    ): Response {
        $ticket = $ticketDtoRequest->createTicketFromTicketDTORequest($usersRepository, $projectRepository);
        $this->ticketService->addTicket($ticket);
        return $this->json($ticket->getEscensialInformations());
    }

    #[Route('/ticket/{id}', name: 'update ticket', methods: ['PUT'])]
    public function updateTicket(
        TicketDTORequest $ticketDtoRequest,
        UsersRepository $usersRepository,
        ProjectRepository $projectRepository,
        string $id
    ): Response {
        $ticketToUpdate = $ticketDtoRequest->createTicketFromTicketDTORequest($usersRepository, $projectRepository);
        $ticket = $this->ticketService->updateTicket($ticketToUpdate, $id);
        return $this->json($ticket->getEscensialInformations());
    }

    #[Route('/ticket/{id}', name: 'get ticket', methods: ['GET'])]
    public function getTicketById(string $id): Response
    {
        $ticket = $this->ticketService->getTicketById($id);

        if (!$ticket) {
            return $this->json([
                'error' => 'Ticket not found',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            $ticket->getEscensialInformations(),
            Response::HTTP_OK
        );
    }
    #[Route('/real-ticket/{id}', name: 'get Real ticket', methods: ['GET'])]
    public function getRealTicketById(string $id): Response
    {
        $ticket = $this->ticketService->getTicketById($id);

        if (!$ticket) {
            return $this->json([
                'error' => 'Ticket not found',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            $ticket->getInformations(),
            Response::HTTP_OK
        );
    }

    #[Route('/tickets', name: 'get all tickets', methods: ['GET'])]
    public function getAllTickets(): Response
    {
        $tickets = $this->ticketService->getAllTickets();
        $res = [];

        foreach ($tickets as $ticket) {
            $res[] = $ticket->getEscensialInformations();
        }
        return $this->json([
            'tickets' => $res,
        ], Response::HTTP_OK);
    }
    #[Route('/tickets/{id}', name: 'get all tickets in a team', methods: ['GET'])]
    public function getAllTicketsInATeam(string $id)
    {
        $tickets =  $this->ticketService->getTicketsByProjectId($id);
        $res = [];

        foreach ($tickets as $ticket) {
            $res[] = $ticket->getEscensialInformations();
        }
        return $this->json($res);
    }
    #[Route('/ticket/{id}', name: 'delete ticket', methods: ['DELETE'])]
    public function deleteTicket(string $id)
    {
        $tickets =  $this->ticketService->deleteTicket($id);
        return $this->json($tickets);
    }
}