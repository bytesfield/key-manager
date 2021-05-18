<?php

namespace Bytesfield\KeyManager;

interface KeyManagerInterface
{
    public function createClient(string $name,  string $type, string $status = "active"): array;

    public function getPrivateKey(int $client_id): array;

    public function changeKeys(int $client_id): array;

    public function suspendClient(int $client_id): array;

    public function activateClient(int $client_id): array;

    public function suspendApiCredential(int $client_id): array;

    public function activateApiCredential(int $client_id): array;
}
