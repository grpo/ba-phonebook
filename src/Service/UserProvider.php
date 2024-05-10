<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserProvider
{
    public function __construct(
        private readonly UserRepository           $userRepository,
        private readonly RequestHeaderParser      $requestParser,
        private readonly JWTTokenManagerInterface $tokenManager,
    ) {}

    public function getUserFormRequest(Request $request): ?User
    {
        $token = $this->requestParser->getBearerToken($request);
        $username = $this->tokenManager->parse($token)['username'];

        return $this->userRepository->findByUsername($username);
    }
}