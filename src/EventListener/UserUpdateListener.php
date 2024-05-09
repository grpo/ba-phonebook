<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

final class UserUpdateListener
{
    #[AsEntityListener(event: Events::preUpdate, method: 'postUpdate', entity: User::class)]
    public function postUpdate(User $user, PreUpdateEventArgs $event): void
    {
        $user->setUpdatedAt(new \DateTime());
    }
}
