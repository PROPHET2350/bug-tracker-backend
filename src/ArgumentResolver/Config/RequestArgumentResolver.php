<?php

namespace App\ArgumentResolver\Config;

use App\Form\RequestDTORepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;


class RequestArgumentResolver implements ArgumentValueResolverInterface
{
        private ValidatorInterface $validator;

        public function __construct(ValidatorInterface $validator)
        {
                $this->validator = $validator;
        }
        public function supports(Request $request, ArgumentMetadata $argument): bool
        {
                $reflect = new \ReflectionClass($argument->getType());
                if ($reflect->implementsInterface(RequestDTORepository::class)) {
                        return true;
                }

                return false;
        }


        public function resolve(Request $request, ArgumentMetadata $argument): iterable
        {
                $class = $argument->getType();
                $dto = new $class($request);

                $error = $this->validator->validate($dto);

                if (count($error) > 0) {
                        throw new BadRequestException((string) $error);
                }

                yield $dto;
        }
}