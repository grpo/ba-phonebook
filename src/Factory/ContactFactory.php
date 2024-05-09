<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\DtoInterface;
use App\Entity\Contact;
use App\Entity\User;

class ContactFactory
{
    public function fromInputDto(DtoInterface $contactDto, User $user): Contact
    {
        return (new Contact())
            ->setName($contactDto->getName())
            ->setPhone($contactDto->getPhone())
            ->setUser($user);
    }

}