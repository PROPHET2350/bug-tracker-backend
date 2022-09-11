<?php

namespace App\ArgumentResolver\Model;

use App\Form\RequestDTORepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectDTORequest implements RequestDTORepository
{
    /**
     * @Assert\NotBlank()
     */
    public $id;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public $name;

    public function __construct(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? null;
    }



    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}