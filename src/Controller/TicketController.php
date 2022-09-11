<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Services\TicketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{

    public function __construct(private TicketService $ticketService)
    {
    }

    #[Route('/ticket/add', name: 'add ticket', methods: ['POST'])]
    public function addTicket(Request $request): Response
    {
        [$ticket, $error] = $this->ticketService->addTicket($request);

        if ($error) {
            return $this->json([
                'error' => $error,
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'ticket' => $ticket,
        ], Response::HTTP_CREATED);
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

        return $this->json([
            'ticket' => $ticket->getEscensialInformations(),
        ], Response::HTTP_OK);
    }

    #[Route('/tickets', name: 'get all tickets', methods: ['GET'])]
    public function getAllTickets(): Response
    {
        /**
         * @var Tickets
         */
        $tickets = $this->ticketService->getAllTickets();
        $res = [];

        foreach ($tickets as $ticket) {
            $res[] = $ticket->getEscensialInformations();
        }
        return $this->json([
            'tickets' => $res,
        ], Response::HTTP_OK);
    }
}