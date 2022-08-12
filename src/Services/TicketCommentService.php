<?php

namespace App\Services;

use App\Repository\TicketCommentsRepository;
use App\Repository\TicketsRepository;
use App\Repository\UsersRepository;

class TicketCommentService
{
    public function __construct(
        private TicketCommentsRepository $ticketCommentsRepository,
        private TicketsRepository $ticketRepository,
        private UsersRepository $userRepository
    ) {
    }
}