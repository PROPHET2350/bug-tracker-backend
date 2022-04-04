<?php

namespace App\Form\Model;

class TeamDTO
{
    public $id;
    public $name;
    public $users;

    public function __construct()
    {
        $this->users = [];
    }
}