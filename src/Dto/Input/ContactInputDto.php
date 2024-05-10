<?php

declare(strict_types=1);

namespace App\Dto\Input;

use App\Constants\AbstractSerializationConstants;
use App\Dto\AbstractDTO;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Constraint;

class ContactInputDto extends AbstractDTO
{
    #[Constraint\NotNull]
    #[Constraint\NotBlank]
    #[JMS\Type(AbstractSerializationConstants::TYPE_STRING)]
    #[JMS\Groups([
        AbstractSerializationConstants::GROUP_DEFAULT,
        AbstractSerializationConstants::GROUP_CONTACT,
    ])]
    private ?string $name = null;

    #[Constraint\NotNull]
    #[Constraint\NotBlank]
    #[Constraint\Length(min: 8, max: 16)]
    #[JMS\Type(AbstractSerializationConstants::TYPE_STRING)]
    #[JMS\Groups([
        AbstractSerializationConstants::GROUP_DEFAULT,
        AbstractSerializationConstants::GROUP_CONTACT,
    ])]
    private ?string $phone = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }
}