<?php

namespace App\Controller;

use App\ArgumentResolver\Model\TicketsCommentDTORequest;
use App\Repository\TicketsRepository;
use App\Repository\UsersRepository;
use App\Services\TicketCommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketCommentController extends AbstractController
{
    private TicketCommentService $ticketCommentService;
    public function __construct(TicketCommentService $ticketCommentService)
    {
        $this->ticketCommentService = $ticketCommentService;
    }
    #[Route('/ticket-comment/add', name: 'add tickets comment', methods: ['POST'])]
    public function addTicketComment(
        TicketsCommentDTORequest $ticketsCommentDtoRequest,
        UsersRepository $usersRepository,
        TicketsRepository $ticketsRepository
    ): Response {
        $ticketsComment = $ticketsCommentDtoRequest->createTicketsCommentFromTicketCommentDTORequest(
            $usersRepository,
            $ticketsRepository
        );
        $this->ticketCommentService->createTicketsComment($ticketsComment);
        return $this->json($ticketsComment);
    }

    #[Route('/ticket-comment/ticket/{id}', name: 'get comments', methods: ['GET'])]
    public function getComments(string $id)
    {
        [$comment, $error] = $this->ticketCommentService->getAllCommentsByTicketId($id);

        if ($error) {
            return $this->json('');
        }
        $commentsEscensialInformations = [];

        foreach ($comment as $value) {
            array_push($commentsEscensialInformations, $value->getEscensialInformations());
        }

        return $this->json($commentsEscensialInformations);
    }
}