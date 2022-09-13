<?php

namespace App\Services;

use App\Entity\TicketComments;
use App\Repository\TicketCommentsRepository;

class TicketCommentService
{
    public function __construct(
        private TicketCommentsRepository $ticketCommentsRepository,
    ) {
    }

    public function createTicketsComment(TicketComments $ticketComments)
    {
        $this->ticketCommentsRepository->add($ticketComments);
    }

    public function getAllCommentsByTicketId(string $ticketId): array
    {
        $comments = $this->ticketCommentsRepository->findBy(
            ['ticket' => $ticketId]
        );

        if (!$comments) {
            return [null, "Comment in tickets with id {$ticketId} does not found"];
        }

        return [$comments, null];
    }
}