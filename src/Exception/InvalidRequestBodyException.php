<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidRequestBodyException extends ApiException
{
    protected $code = Response::HTTP_BAD_REQUEST;
}