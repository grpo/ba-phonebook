<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $users = [
          [
              'name' => 'John Doe',
              'password' => 'secret123'

          ],
          [
              'name' => 'Jane Doe1',
              'password' => 'secret123'

          ],
          [
              'name' => 'Jane Doe1',
              'password' => 'secret123'

          ],
        ];

        foreach ($users as $user) {
            $user = (new User())
                ->setPassword($user['password'])
            ;

            $manager->persist($user);
        }
        $manager->flush();
    }
}
