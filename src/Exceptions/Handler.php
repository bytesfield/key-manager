<?php

namespace Bytesfield\KeyManager\Exceptions;

use Bytesfield\KeyManager\Classes\Errors;
use Bytesfield\KeyManager\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [

    ];

    /**
     * Handles all throwable exceptions.
     *
     * @param $request
     * @param \Throwable $e
     * @return \Illuminate\Support\Facades\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->methodNotAllowed(Errors::MESSAGE['METHOD_NOT_FOUND']);
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->notFound(Errors::MESSAGE['NOT_FOUND']);
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->notFound(Errors::MESSAGE['NOT_FOUND']);
        }

        if ($e instanceof ValidationException) {
            return $this->failedValidation(Errors::MESSAGE['VALIDATION_ERROR'], $e->errors());
        }

        if ($e instanceof AuthenticationException) {
            return $this->unauthorized($e->getMessage());
        }

        return $this->buildResponse($e->getMessage(), false, Errors::CODE['SERVER_ERROR']);
    }
}
