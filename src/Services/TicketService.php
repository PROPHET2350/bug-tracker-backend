<?php

namespace App\Services;

use App\Entity\Tickets;
use App\Form\Model\TicketsDTO;
use App\Form\Type\TicketFormType;
use App\Repository\ProjectRepository;
use App\Repository\TicketsRepository;
use App\Repository\UsersRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class TicketService
{
    public function __construct(
        private TicketsRepository $ticketsRepository,
        private UsersRepository $usersRepository,
        private ProjectRepository $projectRepository,
        private FormFactoryInterface $formFactory
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

    public function addTicket(Request $request): array
    {
        $ticketDTO = new TicketsDTO();
        $form = $this->formFactory->create(TicketFormType::class, $ticketDTO);
        $content = json_decode($request->getContent(), true);
        $form->submit($content);


        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if (!$form->isValid()) {
            return [null, 'Form is not valid'];
        }


        $project = $this->projectRepository->find($ticketDTO->project[0]->id);
        $user = $this->usersRepository->find($ticketDTO->user[0]->id);

        if (!$project) {
            return [null, 'Project not found'];
        }

        if (!$user) {
            return [null, 'User not found'];
        }

        $ticket = new Tickets(
            $ticketDTO->id,
            $ticketDTO->name,
            $ticketDTO->description,
            $ticketDTO->type,
            $ticketDTO->state,
            $ticketDTO->date,
            $ticketDTO->priority,
            $project,
            $user
        );
        $this->ticketsRepository->add($ticket, true);
        return [$ticket, null];
    }

    public function deleteTicket(string $ticketId)
    {
        $ticket = $this->ticketsRepository->find($ticketId);
        if (!$ticket) {
            return [null, 'Ticket not found'];
        }
        $this->ticketsRepository->delete($ticket);
        return [$ticket, null];
    }
}