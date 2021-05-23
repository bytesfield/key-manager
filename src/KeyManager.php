<?php

namespace Bytesfield\KeyManager;

use Bytesfield\KeyManager\Models\ApiCredential;
use Bytesfield\KeyManager\Models\Client;
use Bytesfield\KeyManager\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class KeyManager implements KeyManagerInterface
{
    use ApiResponse;
    /**
     * Create a new Client with Api credentials.
     *
     * @param string $name
     * @param string $type
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function createClient(string $name, string $type, string $status = 'active'): JsonResponse
    {
        if (! in_array($status, [ApiCredential::STATUSES['ACTIVE'], ApiCredential::STATUSES['SUSPENDED']])) {
            return $this->error('Status must be either active or suspended');
        }

        $client = Client::create([
            'name' => $name,
            'type' => $type,
            'status' => $status,
        ]);

        $client->apiCredential()->create(ApiCredential::generateKeyPairArray());

        return $this->success('Client created successfully', $client->toArray());
    }

    /**
     * Get client's private key.
     *
     * @param int $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrivateKey(int $client_id): JsonResponse
    {
        $client = Client::with('apiCredential')->where('id', $client_id)->first();

        if (! $client) {
            return $this->error("No client found with id $client_id");
        }

        return $this->success('Key retrieved successfully', ['key' => $client->apiCredential->private_key]);
    }

    /**
     * Change Client's public and private keys.
     *
     * @param int $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeKeys(int $client_id): JsonResponse
    {
        $key = ApiCredential::where('key_client_id', $client_id)->first();

        if (! $key) {
            return $this->error('Client information not found');
        }

        $key->update(ApiCredential::generateKeyPairArray());

        return $this->success('Api Keys successfully changed', ['key' => $key->private_key]);
    }

    /**
     * Suspend Client's Account.
     *
     * @param int $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function suspendClient(int $client_id): JsonResponse
    {
        $client = Client::find($client_id);

        if (! $client) {
            return $this->error("No client found with id $client_id");
        }

        $client->update([
            'status' => ApiCredential::STATUSES['SUSPENDED'],
        ]);

        return $this->success('Client successfully suspended');
    }

    /**
     * Activate Client's Account.
     *
     * @param int $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateClient(int $client_id): JsonResponse
    {
        $client = Client::find($client_id);

        if (! $client) {
            return $this->error("No client found with id $client_id");
        }

        $client->update([
            'status' => ApiCredential::STATUSES['ACTIVE'],
        ]);

        return $this->success('Client successfully activated');
    }

    /**
     * Suspend Client's Api Credential.
     *
     * @param int $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function suspendApiCredential(int $client_id): JsonResponse
    {
        $key = ApiCredential::where('key_client_id', $client_id)->first();

        if (! $key) {
            return $this->error('Client information not found');
        }

        $key->update([
            'status' => ApiCredential::STATUSES['SUSPENDED'],
        ]);

        return $this->success('ApiCredential successfully suspended');
    }

    /**
     * Activate Client's Api Credential.
     *
     * @param int $client_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function activateApiCredential(int $client_id): JsonResponse
    {
        $key = ApiCredential::where('key_client_id', $client_id)->first();

        if (! $key) {
            return $this->error('Client information not found');
        }

        $key->update([
            'status' => ApiCredential::STATUSES['ACTIVE'],
        ]);

        return $this->success('ApiCredential successfully activated');
    }
}
