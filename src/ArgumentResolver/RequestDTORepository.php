<?php

namespace App\Form;

use Symfony\Component\HttpFoundation\Request;

interface RequestDTORepository
{
    public function __construct(Request $request);
}