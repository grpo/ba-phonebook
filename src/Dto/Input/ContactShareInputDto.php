<?php

declare(strict_types=1);

namespace App\Dto\Input;

use App\Dto\DtoInterface;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;

class ContactShareInputDto implements DtoInterface
{
    #[Uuid]
    #[NotBlank]
    #[NotNull]
    #[Type('string')]
    #[Groups(['default'])]
    private string $userId;

    #[Uuid]
    #[NotBlank]
    #[NotNull]
    #[Type('string')]
    #[Groups(['default'])]
    private string $contactId;

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function setContactId(string $contactId): void
    {
        $this->contactId = $contactId;
    }
}