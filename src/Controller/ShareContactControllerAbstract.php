<?php

namespace App\Controller;

use App\Dto\Input\ContactShareInputDto;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use App\Service\ApiSerializer;
use App\Service\UserProvider;
use App\Service\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ShareContactControllerAbstract extends AbstractApiController
{
    const SHARE = 'share';
    const UN_SHARE = 'unshare';

    public function __construct
    (
        ApiSerializer                      $serializer,
        EntityManagerInterface             $entityManager,
        UserProvider                       $userProvider,
        private readonly Validator         $validator,
        private readonly ContactRepository $contactRepository,
        private readonly UserRepository    $userRepository,
    ) {
        parent::__construct($serializer, $entityManager, $userProvider);
    }

    #[Route('/share', name: 'app_contact_share', methods: [Request::METHOD_POST])]
    public function postShareAction(Request $request): JsonResponse
    {
        return $this->shareContact(self::SHARE, $request);
    }

    #[Route('/unshare', name: 'app_contact_un-share', methods: [Request::METHOD_POST])]
    public function postUnShareAction(Request $request): JsonResponse
    {
        return $this->shareContact(self::UN_SHARE, $request);
    }

    private function shareContact(string $action, Request $request): JsonResponse
    {
        /** @var ContactShareInputDto $contactShareDto */
        $contactShareDto = $this->serializer->jsonToObject($request->getContent(), ContactShareInputDto::class);
        $this->validator->validateDto($contactShareDto);

        $contact = $this->contactRepository->find($contactShareDto->getContactId());
        if ($contact->getUser() !== $this->userProvider->getUserFormRequest($request)) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->find($contactShareDto->getUserId());

        if (!$contact || !$user) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        match ($action) {
            self::SHARE => $contact->shareWith($user),
            self::UN_SHARE => $contact->unShareWith($user),
            default => throw new BadRequestHttpException()
        };

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return new JsonResponse();
    }
}
