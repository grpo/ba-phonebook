<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Type;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: '`contacts`')]
class Contact
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[Type('string')]
    #[Groups(['contact', 'default'])]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Type('string')]
    #[Groups(['contact', 'default'])]
    private string $phone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Type('string')]
    #[Groups(['contact', 'default'])]
    private string $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'contacts')]
    #[Type(User::class)]
    #[Groups(['default'])]
    private User $user;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $sharedWith;

    public function __construct()
    {
        $this->sharedWith = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getSharedWith(): Collection
    {
        return $this->sharedWith;
    }

    public function shareWith(User $user): self
    {
        if (!$this->sharedWith->contains($user)) {
            $this->sharedWith[] = $user;
        }
        return $this;
    }

    public function unShareWith(User $user): self
    {
        if ($this->sharedWith->contains($user)) {
            $this->sharedWith->removeElement($user);
        }

        return $this;
    }
}
