<?php

namespace App\Controller;

use App\Dto\Input\ContactShareInputDto;
use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use App\Service\ApiSerializer;
use App\Service\UserProvider;
use App\Service\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShareContactController extends ApiController
{
    public function __construct
    (
        ApiSerializer $serializer,
        EntityManagerInterface $entityManager,
        UserProvider $userProvider,
        private Validator $validator,
        private ContactRepository $contactRepository,
        private UserRepository $userRepository,
    ) {
        parent::__construct($serializer, $entityManager, $userProvider);
    }

    #[Route('/share', name: 'app_contact_share', methods: ['POST'])]
    public function postShareAction(Request $request): JsonResponse
    {
        return $this->shareContact('share', $request);
    }

    #[Route('/unshare', name: 'app_contact_un-share', methods: ['POST'])]
    public function postUnShareAction(Request $request): JsonResponse
    {
        return $this->shareContact('unshare', $request);
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

        if ($action === 'share') {
            $contact->shareWith($user);
        }
        if ($action === 'unshare') {
            $contact->unShareWith($user);
        }

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return new JsonResponse();
    }
}
