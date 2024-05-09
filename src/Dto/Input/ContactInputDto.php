<?php

declare(strict_types=1);

namespace App\Dto\Input;

use App\Dto\DtoInterface;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Constraints;

class ContactInputDto implements DtoInterface
{
    #[Constraints\NotNull]
    #[Constraints\NotBlank]
    #[Type('string')]
    #[Groups(['default', 'contact'])]
    private ?string $name = null;

    #[Constraints\NotNull]
    #[Constraints\NotBlank]
    #[Constraints\Length(min: 8, max: 16)]
    #[Type('string')]
    #[Groups(['default', 'contact'])]
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