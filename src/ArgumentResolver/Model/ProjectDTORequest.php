<?php

namespace App\ArgumentResolver\Model;

use App\ArgumentResolver\RequestDTORepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectDTORequest implements RequestDTORepository
{
    /**
     * @Assert\NotBlank()
     */
    private $id;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    private $name;

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