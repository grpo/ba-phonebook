<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

final class UserUpdateListener
{
    #[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: User::class)]
    public function postUpdate(User $user, PreUpdateEventArgs $event): void
    {
        $user->setUpdatedAt();
    }
}
