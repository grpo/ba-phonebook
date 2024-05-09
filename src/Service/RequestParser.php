<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class RequestParser
{
    public function getBearerToken(Request $request): ?string
    {
        $authorization = $request->headers->get('Authorization');
        return trim(str_replace('Bearer', '', $authorization));
    }
}