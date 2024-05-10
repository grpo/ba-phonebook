<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $users = [
            [
                'username' => 'user7',
                'password' => 'secret123'

            ],
            [
                'username' => 'user8',
                'password' => 'secret123'

            ],
            [
                'username' => 'user9',
                'password' => 'secret123'

            ],
        ];

        foreach ($users as $user) {
            $entity = new User();
            $entity->setUsername($user['username'])
                ->setPassword(
                    $this->passwordHasher->hashPassword($entity, $user['password'])
                )
            ;
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
