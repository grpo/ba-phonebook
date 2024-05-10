<?php

declare(strict_types=1);

namespace App\Service;

use App\Constants\AbstractSerializationConstants;
use App\Exception\ApiException;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class ApiSerializer
{

    public function __construct(private readonly SerializerInterface $serializer) {}

    public function objectToJson(
        object|array $object,
        array $contextGroups = [AbstractSerializationConstants::GROUP_DEFAULT]
    ): string {
        return $this->serializer->serialize(
            $object,
            'json',
            SerializationContext::create()->setGroups($contextGroups)
        );
    }

    public function jsonToObject(
        string $json,
        string $class,
        array $contextGroups = [AbstractSerializationConstants::GROUP_DEFAULT]
    ): object {
        $this->checkIsValidJson($json);
        return $this->serializer->deserialize(
            $json,
            $class,
            'json',
            DeserializationContext::create()->setGroups($contextGroups)
        );
    }

    /**
     * @throws ApiException
     */
    private function checkIsValidJson($data): bool
    {
        if (is_string($data) && json_validate($data)) {
            return true;
        }

        throw new ApiException('Invalid JSON data.');
    }
}