<?php

declare(strict_types=1);

namespace App\Dto\Input;

use App\Constants\AbstractSerializationConstants;
use App\Dto\AbstractDTO;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Constraint;

class ContactShareInputDto extends AbstractDTO
{
    #[Constraint\Uuid]
    #[Constraint\NotBlank]
    #[Constraint\NotNull]
    #[JMS\Type(AbstractSerializationConstants::TYPE_STRING)]
    #[JMS\Groups([
        AbstractSerializationConstants::GROUP_DEFAULT,
        AbstractSerializationConstants::GROUP_CONTACT,
    ])]
    private string $userId;

    #[Constraint\Uuid]
    #[Constraint\NotBlank]
    #[Constraint\NotNull]
    #[JMS\Type(AbstractSerializationConstants::TYPE_STRING)]
    #[JMS\Groups([
        AbstractSerializationConstants::GROUP_DEFAULT,
        AbstractSerializationConstants::GROUP_CONTACT,
    ])]
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