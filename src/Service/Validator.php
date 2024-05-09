<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\DtoInterface;
use App\Exception\InvalidRequestBodyException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{
    public function __construct(
       private ValidatorInterface $validator,
       private ApiSerializer     $serializer,
    ) {}

    public function validateDto(DtoInterface $dto): void
    {
        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errors = $this->getViolationsArray($errors);
            $message = $this->serializer->objectToJson($errors);

            throw new InvalidRequestBodyException($message);
        }
    }

    private function getViolationsArray(ConstraintViolationList $errors): ?array
    {
        $violations = [];
        foreach ($errors as $error) {
            $violations[$error->getPropertyPath()] = $error->getMessage();
        }
        return $violations;
    }
}