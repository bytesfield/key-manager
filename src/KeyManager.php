<?php

namespace Bytesfield\KeyManager;

use Bytesfield\KeyManager\Models\Client;
use Bytesfield\KeyManager\KeyManagerInterface;
use Bytesfield\KeyManager\Models\ApiCredential;

class KeyManager implements KeyManagerInterface
{

    /**
     * Create a new client with credentials
     *
     * @param string $name
     * @param int $user
     * @param string $type
     * @param string $status
     * 
     * @return array
     */
    public function createClient(string $name, string $type, string $status = "active"): array
    {
        if (!in_array($status, [ApiCredential::STATUSES['ACTIVE'], ApiCredential::STATUSES['SUSPENDED']])) {
            return $this->response(false, 400, "Status must be either active or suspended");
        }

        $client = Client::create([
            'name' => $name,
            'type' => $type,
            'status' => $status
        ]);

        $client->apiCredential()->create(ApiCredential::generateKeyPairArray());

        return $this->response(true, 200, "Client created successfully", $client->toArray());
    }


    /**
     * Get client's private key
     *
     * @param integer $client_id
     * @return array
     */
    public function getPrivateKey(int $client_id): array
    {
        $client = Client::with('apiCredential')->where('id', $client_id)->first();
        if (!$client) {
            return $this->response(false, 400, "No client found with id $client_id");
        }

        return $this->response(true, 200, "Key retrieved successfully", ["key" => $client->apiCredential->private_key]);
    }

    /**
     * Change Client's public and private keys
     *
     * @param integer $client_id
     * @return array
     */
    public function changeKeys(int $client_id): array
    {
        $key = ApiCredential::where('key_client_id', $client_id)->first();

        if (!$key) {
            return $this->response(false, 400, "Client information not found");
        }

        $key->update(ApiCredential::generateKeyPairArray());

        return $this->response(true, 200, "Api Keys successfully changed", ["key" => $key->private_key]);
    }

    /**
     * Suspend Client's Account
     *
     * @param integer $client_id
     * @return array
     */
    public function suspendClient(int $client_id): array
    {
        $client = Client::find($client_id);
        if (!$client) {
            return $this->response(false, 400, "No client found with id $client_id");
        }

        $client->update([
            'status' => ApiCredential::STATUSES['SUSPENDED']
        ]);

        return $this->response(true, 200, "Client successfully suspended");
    }

    /**
     * Activate Client's Account
     *
     * @param integer $client_id
     * @return array
     */
    public function activateClient(int $client_id): array
    {
        $client = Client::find($client_id);
        if (!$client) {
            return $this->response(false, 400,  "No client found with id $client_id");
        }

        $client->update([
            'status' => ApiCredential::STATUSES['ACTIVE']
        ]);

        return $this->response(true, 200, "Client successfully activated");
    }

    /**
     * Suspend Api Credential
     *
     * @param integer $client_id
     * @return array
     */
    public function suspendApiCredential(int $client_id): array
    {
        $key = ApiCredential::where('key_client_id', $client_id)->first();

        if (!$key) {
            return $this->response(false, 404, "Client information not found");
        }

        $key->update([
            'status' => ApiCredential::STATUSES['SUSPENDED']
        ]);

        return $this->response(true, 200, "ApiCredential successfully suspended");
    }

    /**
     * Activate ApiCredential
     *
     * @param integer $client_id
     * @return array
     */
    public function activateApiCredential(int $client_id): array
    {
        $key = ApiCredential::where('key_client_id', $client_id)->first();

        if (!$key) {
            return $this->response(false, 404, "Client information not found");
        }

        $key->update([
            'status' => ApiCredential::STATUSES['ACTIVE']
        ]);

        return $this->response(true, 200, "ApiCredential successfully activated");
    }


    /**
     * Response array
     *
     * @param boolean $success
     * @param string $message
     * @param array $data
     * @return array
     */
    private function response(bool $status, int $statusCode, string $message, array $data = []): array
    {
        $responseData = [
            "status" => $status,
            'statusCode' => $statusCode,
            "message" => $message,
        ];

        if (!empty($data)) {
            $responseData['data'] = $data;
        }

        return $responseData;
    }
}
