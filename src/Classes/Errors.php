<?php

namespace Bytesfield\KeyManager\Classes;

class Errors
{

    public const CODE = [
        'OK' => 200,
        'NOT_FOUND' => 404,
        'UNPROCESSIBLE_ENTITY' => 402,
        'BAD_REQUEST' => 400,
        'FORBIDDEN' => 403,
        'METHOD_NOT_FOUND' => 405,
        'UNAUTHORIZED' => 401,
        'CONFLICT' => 409,
        'VALIDATION_ERROR' => 422,
        'SERVER_ERROR' => 500,
        'SERVICE_UNAVAILABLE' => 503,
    ];

    public const MESSAGE = [
        'OK' => 'Success',
        'NOT_FOUND' => "We didn't find what you're looking for.",
        'UNPROCESSIBLE_ENTITY' => 'Entity can not be processed',
        'BAD_REQUEST' =>  'Bad Request Recieved',
        'FORBIDDEN' => 'You are Forbidden to access this resource',
        'METHOD_NOT_FOUND' => 'The route method is not allowed.',
        'UNAUTHORIZED' => 'You are not authorized to access this resource.',
        'CONFLICT' => 'Conflict Found',
        'VALIDATION_ERROR' => 'Validation failed.',
        'SERVER_ERROR' => 'Unexpected server error occurred. Try again later',
        'SERVICE_UNAVAILABLE' => 'Service not available',
    ];
}
