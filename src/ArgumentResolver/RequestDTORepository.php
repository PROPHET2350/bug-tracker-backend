<?php

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;

interface RequestDTORepository
{
    public function __construct(Request $request);
}