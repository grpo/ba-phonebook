<?php

declare(strict_types=1);

namespace App\Factory;

use App\Constants\AbstractSerializationConstants;
use App\Dto\AbstractDTO;
use App\Dto\Input\ContactInputDto;
use App\Dto\Output\ContactOutputDto;
use App\Service\ApiSerializer;
use Symfony\Component\HttpFoundation\Request;

class ContactDtoFactory
{
    public function __construct(
        private ApiSerializer $serializer,
    ) {}

    public function createUpdatedDto(Request $request, $contactEntity): AbstractDTO
    {
        /** @var ContactInputDto $contactInputDto */
        $contactInputDto = $this->serializer->jsonToObject($request->getContent(), ContactInputDto::class);

        ($contactInputDto->getPhone() === null)?:$contactEntity->setPhone($contactInputDto->getPhone());
        ($contactInputDto->getName() === null)?:$contactEntity->setName($contactInputDto->getName());

        $contactOutputDto = $this->serializer->objectToJson(
            $contactEntity,
            [AbstractSerializationConstants::GROUP_CONTACT]
        );
        $contactOutputDto = $this->serializer->jsonToObject($contactOutputDto, ContactOutputDto::class);

        return $contactOutputDto;
    }
}