<?php

declare(strict_types=1);

namespace App\Entity;

use App\Constants\AbstractSerializationConstants;
use App\Repository\ContactRepository;
use Doctrine\Common\Collections as Doctrine;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: '`contacts`')]
class Contact
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[JMS\Type(AbstractSerializationConstants::TYPE_STRING)]
    #[JMS\Groups([
        AbstractSerializationConstants::GROUP_DEFAULT,
        AbstractSerializationConstants::GROUP_CONTACT,
    ])]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[JMS\Type(AbstractSerializationConstants::TYPE_STRING)]
    #[JMS\Groups([
        AbstractSerializationConstants::GROUP_DEFAULT,
        AbstractSerializationConstants::GROUP_CONTACT,
    ])]
    private string $phone;

    #[ORM\Column(type: 'string', length: 255)]
    #[JMS\Type(AbstractSerializationConstants::TYPE_STRING)]
    #[JMS\Groups([
        AbstractSerializationConstants::GROUP_DEFAULT,
        AbstractSerializationConstants::GROUP_CONTACT,
    ])]
    private string $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'contacts')]
    #[JMS\Type(AbstractSerializationConstants::TYPE_STRING)]
    #[JMS\Groups([
        AbstractSerializationConstants::GROUP_DEFAULT,
    ])]
    private User $user;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Doctrine\Collection $sharedWith;

    public function __construct()
    {
        $this->sharedWith = new Doctrine\ArrayCollection();
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

    public function getSharedWith(): Doctrine\Collection
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
