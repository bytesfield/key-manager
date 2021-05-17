<?php

namespace Bytesfield\KeyManager;

use Bytesfield\KeyManager\Models\Client;
use Bytesfield\KeyManager\KeyManagerInterface;
use Bytesfield\KeyManager\Models\ApiCredential;

class KeyManager implements KeyManagerInterface
{

    /**
     * function to create a new client application
     *
     * @param string $name
     * @param int $user
     * @param string $type
     * @param string $status
     * @return array
     */
    public function createClient(string $name, string $type, int  $userId = null,  string $status = "active"): array
    {
        if (!in_array($status, ['active', 'suspended'])) {
            return $this->response(false, 400, "Status must be either active or suspended");
        }

        $client = new Client();
        $client->user_id = $userId;
        $client->name = $name;
        $client->type = $type;
        $client->status = $status;
        $client->save();

        $client->apiCredential()->create(ApiCredential::generateKeyPairArray());

        return $this->response(true, 200, "Client created successfully", $client->toArray());
    }


    /**
     * return clients private key
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

        return $this->response(true, 200, "Key fetched successfully", ["key" => $client->apiCredential->private_key]);
    }

    /**
     * Change client public and private keys
     *
     * @param integer $client_id
     * @return array
     */
    public function changeKeys(int $client_id): array
    {
        $key = ApiCredential::where('client_id', $client_id)->first();

        if (!$key) {
            return $this->response(false, 400, "Client information not found");
        }

        $key->update(ApiCredential::generateKeyPairArray());

        return $this->response(true, 200, "keys successfully changed", ["key" => $key->private_key]);
    }

    /**
     * suspend client account
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

        $client->status = "suspended";
        $client->save();

        return $this->response(false, 200, "Client successfully suspended");
    }

    /**
     * Activate client account
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

        $client->status = ApiCredential::STATUSES['ACTIVE'];
        $client->save();

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
        $key = ApiCredential::where('client_id', $client_id)->first();

        if (!$key) {
            return $this->response(false, 404, "Client information not found");
        }

        $key->status =  ApiCredential::STATUSES['SUSPENDED'];
        $key->save();

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
        $key = ApiCredential::where('client_id', $client_id)->first();

        if (!$key) {
            return $this->response(false, 404, "Client information not found");
        }

        $key->status = ApiCredential::STATUSES['ACTIVE'];
        $key->save();

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
