<?php

namespace App\Services;

use App\Entity\Users;
use App\Form\Model\UserFormType;
use App\Form\Model\UsersDTO;
use App\Repository\RolesRepository;
use App\Repository\UsersRepository;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private UsersRepository $userRepository,
        private FormFactoryInterface $formFactory,
        private UserPasswordHasherInterface $passwordHasher,
        private RolesRepository $rolesRepository
    ) {
    }

    public function AddUser(Request $request): array
    {
        $UserDTO = new UsersDTO();
        $form = $this->formFactory->create(UserFormType::class, $UserDTO);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        $HashPassword = $this->passwordHasher->hashPassword($UserDTO, $UserDTO->getPassword());
        $UserDTO->password = $HashPassword;
        $RoleTrueEntity = [];
        foreach ($UserDTO->roles as $key) {
            $RoleTrueEntity[] = $this->rolesRepository->find($key->id);
        }
        $UserDTO->roles = $RoleTrueEntity;
        $user = new Users($UserDTO->id, $UserDTO->username, $UserDTO->password, $UserDTO->roles);
        $this->userRepository->add($user);
        return [$user, null];
    }

    public function UpdateUser(Request $request, string $id): array
    {
        $userFinder = $this->userRepository->find($id);

        $UserDTO = new UsersDTO();
        $form = $this->formFactory->create(UserFormType::class, $UserDTO);
        $form->submit(json_decode($request->getContent(), true));

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if (!$form->isValid()) {
            return [null, 'Form is not valid'];
        }
        $RoleTrueEntity = [];
        foreach ($UserDTO->roles as $key) {
            $RoleTrueEntity[] = $this->rolesRepository->find($key->id);
        }
        $UserDTO->roles = $RoleTrueEntity;
        $HashPassword = $this->passwordHasher->hashPassword($UserDTO, $UserDTO->getPassword());
        $UserDTO->password = $HashPassword;
        $userFinder->update($UserDTO->username, $UserDTO->password, $UserDTO->roles);
        $this->userRepository->add($userFinder, true);
        return [$userFinder, null];
    }
}