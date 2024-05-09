<?php

declare(strict_types=1);


namespace App\Controller;

use App\Dto\Input\ContactInputDto;
use App\Entity\Contact;
use App\Factory\ContactDtoFactory;
use App\Factory\ContactFactory;
use App\Repository\ContactRepository;
use App\Service\ApiSerializer;
use App\Service\UserProvider;
use App\Service\Validator;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contact', name: 'app_')]
class ContactController extends ApiController
{

    public function __construct(
        ApiSerializer $serializer,
        EntityManagerInterface $entityManager,
        UserProvider $userProvider,
    ) {
        parent::__construct($serializer, $entityManager, $userProvider);
    }

    #[Route('', name: 'post', methods: ['POST'])]
    public function postAction(
        Request $request,
        Validator $validator,
        ContactFactory $contactFactory,
    ): JsonResponse {
        /** @var ContactInputDto $contactInputDto */
        $contactInputDto = $this->serializer->jsonToObject($request->getContent(), ContactInputDto::class);
        $validator->validateDto($contactInputDto);

        $user = $this->userProvider->getUserFormRequest($request);
        $contact = $contactFactory->fromInputDto($contactInputDto, $user);

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return new JsonResponse(json_decode($this->serializer->objectToJson($contact, ['contact'])), Response::HTTP_CREATED);
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function listAction(
        Request $request,
        ContactRepository $contactRepository,
    ): JsonResponse {
        $user = $this->userProvider->getUserFormRequest($request);
        $contacts = $contactRepository->findAllUserContacts($user);

        return new JsonResponse(json_decode($this->serializer->objectToJson($contacts, ['contact'])), Response::HTTP_OK);
    }

    #[Route('/{contact}', name: 'delete', methods: ['DELETE'])]
    public function deleteAction(
        Contact $contact,
        Request $request
    ): JsonResponse {
        if ($contact->getUser() !== $this->userProvider->getUserFormRequest($request)) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $this->entityManager->remove($contact);
        $this->entityManager->flush();

        return new JsonResponse();

    }

    #[Route('/{contact}', name: 'get', methods: ['GET'])]
    public function getAction(
        Contact $contact,
        Request $request,
    ): JsonResponse {
        if ($contact->getUser() !== $this->userProvider->getUserFormRequest($request)) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse(json_decode($this->serializer->objectToJson($contact, ['contact'])));
    }

    #[Route('/{contact}', name: 'put', methods: ['PUT'])]
    public function putAction(
        Contact $contact,
        Request $request,
        Validator $validator,
        ContactDtoFactory $contactDtoFactory,
        SerializerInterface $serializer
    ): JsonResponse {
        if ($contact->getUser() !== $this->userProvider->getUserFormRequest($request)) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $updatedContactDto = $contactDtoFactory->createUpdatedDto($request, $contact);
        $validator->validateDto($updatedContactDto);

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return new JsonResponse(
            json_decode($this->serializer->objectToJson($contact, ['contact'])),
            Response::HTTP_CREATED
        );
    }
}
