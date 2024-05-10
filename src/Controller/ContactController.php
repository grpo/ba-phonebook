<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\AbstractSerializationConstants;
use App\Dto\Input\ContactInputDto;
use App\Entity\Contact;
use App\Exception\InvalidRequestBodyException;
use App\Exception\UserNotFoundException;
use App\Factory\ContactDtoFactory;
use App\Factory\ContactFactory;
use App\Repository\ContactRepository;
use App\Service\ApiSerializer;
use App\Service\UserProvider;
use App\Service\Validator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contact', name: 'app_contact_')]
class ContactController extends AbstractApiController
{

    public function __construct(
        ApiSerializer          $serializer,
        EntityManagerInterface $entityManager,
        UserProvider           $userProvider,
    ) {
        parent::__construct($serializer, $entityManager, $userProvider);
    }

    /**
     * @throws UserNotFoundException
     * @throws InvalidRequestBodyException
     */
    #[Route('', name: 'post', methods: [Request::METHOD_POST])]
    public function postAction(
        Request        $request,
        Validator      $validator,
        ContactFactory $contactFactory,
    ): JsonResponse {
        /** @var ContactInputDto $contactInputDto */
        $contactInputDto = $this->serializer->jsonToObject($request->getContent(), ContactInputDto::class);
        $validator->validateDto($contactInputDto);

        $user = $this->userProvider->getUserFormRequest($request);
        if (!$user) {
            throw new UserNotFoundException();
        }

        $contact = $contactFactory->fromInputDto($contactInputDto, $user);
        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return new JsonResponse(
            json_decode(
                $this->serializer->objectToJson(
                    $contact,
                    [AbstractSerializationConstants::GROUP_CONTACT]
                )
            ),
            Response::HTTP_CREATED
        );
    }

    /**
     * @throws UserNotFoundException
     */
    #[Route('', name: 'list', methods: [Request::METHOD_GET])]
    public function listAction(
        Request             $request,
        ContactRepository   $contactRepository,
    ): JsonResponse {
        $user = $this->userProvider->getUserFormRequest($request);
        if (!$user) {
            throw new UserNotFoundException();
        }

        $userContacts = $contactRepository->findAllUserContacts($user);
        $sharedWithUser = $contactRepository->findContactsSharedWithUser($user);

        $payload = new ArrayCollection();
        $payload->set('User Contacts', $userContacts);
        $payload->set('Shared Contacts', $sharedWithUser);

        return new JsonResponse(
            json_decode(
                $this->serializer->objectToJson(
                    $payload,
                    [AbstractSerializationConstants::GROUP_CONTACT]
                )
            )
        );
    }

    #[Route('/{contact}', name: 'delete', methods: [Request::METHOD_DELETE])]
    public function deleteAction(
        Contact $contact,
        Request $request
    ): JsonResponse {
        if (!$this->contactBelongToUser($contact, $request)) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }
        $this->entityManager->remove($contact);
        $this->entityManager->flush();

        return new JsonResponse();
    }

    #[Route('/{contact}', name: 'get', methods: [Request::METHOD_GET])]
    public function getAction(
        Contact $contact,
        Request $request,
        ContactRepository $contactRepository,
    ): JsonResponse {
        $belongToUser = $this->contactBelongToUser($contact, $request);
        if (!$belongToUser && $contact) {
            $contact = $contactRepository->findOneContactSharedWithUser(
                $this->userProvider->getUserFormRequest($request),
                $contact
            );
        }


        return new JsonResponse(
            json_decode(
                $this->serializer->objectToJson(
                    $contact,
                    [AbstractSerializationConstants::GROUP_CONTACT])
            )
        );
    }

    /**
     * @throws InvalidRequestBodyException
     */
    #[Route('/{contact}', name: 'put', methods: [Request::METHOD_PUT])]
    public function putAction(
        Contact           $contact,
        Request           $request,
        Validator         $validator,
        ContactDtoFactory $contactDtoFactory,
    ): JsonResponse {
        if (!$this->contactBelongToUser($contact, $request)) {
            return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
        }

        $updatedContactDto = $contactDtoFactory->createUpdatedDto($request, $contact);
        $validator->validateDto($updatedContactDto);

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        return new JsonResponse(
            json_decode(
                $this->serializer->objectToJson(
                    $contact,
                    [AbstractSerializationConstants::GROUP_CONTACT]
                )
            ),
            Response::HTTP_CREATED
        );
    }

    private function contactBelongToUser(Contact $contact, Request $request): bool
    {
        return $contact->getUser() === $this->userProvider->getUserFormRequest($request);
    }
}
