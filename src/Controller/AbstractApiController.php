<?php

declare(strict_types=1);


namespace App\Controller;

use App\Service\ApiSerializer;
use App\Service\UserProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractApiController extends AbstractController
{
    public function __construct(
        protected readonly ApiSerializer $serializer,
        protected readonly EntityManagerInterface $entityManager,
        protected readonly UserProvider $userProvider,
    ) {}
}