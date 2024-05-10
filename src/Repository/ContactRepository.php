<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function findAllUserContacts(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user_id')
            ->setParameter('user_id', $user->getId())
            ->getQuery()
            ->getResult();
    }

    public function findContactsSharedWithUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.sharedWith', 's', 'WITH', 's.id = :id')
            ->setParameter('id', $user->getId())
            ->getQuery()
            ->getResult();
    }

    public function findOneContactSharedWithUser(User $user, Contact $contact): mixed
    {
        // TODO write a legit query
//        return $this->createQueryBuilder('c')
//            ->innerJoin('c.sharedWith', 's', 'WITH', 's.id = :id')
//            ->setParameter('id', $user->getId())
//            ->where('c.id = :id')
//            ->setParameter('id', $contact->getId())
//            ->getQuery()
//            ->getResult();
        $allContacts = $this->findContactsSharedWithUser($user);
        foreach ($allContacts as $con) {
            if ($con->getId() === $contact->getId()) {
                return $con;
            }
        }
    }
}
