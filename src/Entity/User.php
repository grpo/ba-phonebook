<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column]
    private string $id;

    public function getId(): ?string
    {
        return $this->id;
    }
}
