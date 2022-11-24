<?php

namespace App\Services;

use App\Entity\Tickets;
use App\Repository\TicketsRepository;

class TicketService
{
    public function __construct(
        private TicketsRepository $ticketsRepository,
    ) {
    }

    public function getAllTickets(): array
    {
        return $this->ticketsRepository->findAll();
    }

    public function getTicketById(string $id): ?Tickets
    {
        $ticket = $this->ticketsRepository->find($id);

        if (!$ticket) {
            return null;
        }

        return $ticket;
    }

    public function addTicket(Tickets $ticket)
    {
        $this->ticketsRepository->add($ticket, true);
    }

    public function updateTicket(Tickets $ticket, string $id): Tickets
    {
        $ticketToUpdate = $this->ticketsRepository->find($id);
        $ticketToUpdate->updateTicket(
            $ticket->getState(),
            $ticket->getType(),
            $ticket->getDescription(),
            $ticket->getPriority(),
            $ticket->getName(),
            $ticket->getUsers()->toArray(),
        );

        $this->ticketsRepository->add($ticketToUpdate);
        return $ticketToUpdate;
    }

    public function deleteTicket(string $ticketId)
    {
        $ticket = $this->ticketsRepository->find($ticketId);
        if (!$ticket) {
            return [null, 'Ticket not found'];
        }
        $this->ticketsRepository->remove($ticket);
        return [$ticket, null];
    }

    public function getTicketsByProjectId($projectId)
    {
        $tickets = $this->ticketsRepository->findBy(array('project' => $projectId));

        return $tickets;
    }
}