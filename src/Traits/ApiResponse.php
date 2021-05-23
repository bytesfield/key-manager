<?php

namespace Bytesfield\KeyManager\Traits;

use Bytesfield\KeyManager\Classes\Errors;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Generates a not found response for a request.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound(string $message): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['NOT_FOUND']);
    }

    /**
     * Generates a bad request response for a request.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function badRequest(string $message): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['BAD_REQUEST']);
    }

    /**
     * Generates a not found response for a request.
     *
     * @param string $message
     * @param array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function failedValidation(string $message, array $errors = []): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['VALIDATION_ERROR'], $errors);
    }

    /**
     * Generates an unauthorized response for a request.
     *
     * @param string $message.
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized(string $message): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['UNAUTHORIZED']);
    }

    /**
     * Generates a method not found response for a request.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function methodNotAllowed(string $message): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['METHOD_NOT_FOUND']);
    }

    /**
     * Generates a failed Data Creation response for a request.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function failedDataCreation(string $message): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['BAD_REQUEST']);
    }

    /**
     * Generates a success response for a request.
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(string $message, array $data = []): JsonResponse
    {
        return $this->buildResponse($message, true, Errors::CODE['OK'], $data);
    }

    /**
     * Generates a success response for a request.
     *
     * @param string $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function actionSuccess(string $message, array $data = []): JsonResponse
    {
        return $this->buildResponse($message, true, Errors::CODE['OK'], $data);
    }

    /**
     * Generates an error response for a request.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['CONFLICT']);
    }

    /**
     * Generates a server error response for a request.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function serverError(string $message): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['SERVER_ERROR']);
    }

    /**
     * Generates a forbidden response for a request.
     *
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbidden(string $message): JsonResponse
    {
        return $this->buildResponse($message, false, Errors::CODE['FORBIDDEN']);
    }

    /**
     * Built a response for a request.
     *
     * @param string $message
     * @param bool $status
     * @param int $statusCode
     * @param array $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    private function buildResponse(
        string $message,
        bool $status,
        int $statusCode,
        array $data = [],
        array $headers = []
    ) {
        $responseData = [
            'status' => $status,
            'statusCode' => $statusCode,
            'message' => $message,
        ];

        if (! empty($data)) {
            $responseData['data'] = $data;
        }

        return new JsonResponse($responseData, $statusCode);
    }
}
