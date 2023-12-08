<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthentionFaildException extends Exception
{
    public function render(): JsonResponse
    {
        return response()->error(['message' => 'Unathenticated!'], Response::HTTP_FORBIDDEN);
    }
}
