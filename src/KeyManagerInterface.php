<?php

namespace Bytesfield\KeyManager;
use Illuminate\Http\JsonResponse;

interface KeyManagerInterface
{
  
    public function createClient(string $name, string $type, string $status = 'active'): JsonResponse;

    public function getPrivateKey(int $client_id): JsonResponse;

    public function changeKeys(int $client_id): JsonResponse;

    public function suspendClient(int $client_id): JsonResponse;

    public function activateClient(int $client_id): JsonResponse;

    public function suspendApiCredential(int $client_id): JsonResponse;

    public function activateApiCredential(int $client_id): JsonResponse;
}
