<?php

namespace Bytesfield\KeyManager\Tests\Unit\Commands;

use Bytesfield\KeyManager\Tests\TestCase;
use Bytesfield\KeyManager\Models\ApiCredential;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChangeKeysCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandCanChangeKeys(): void
    {
        $client = $this->createNewClient();
        $clientId = $client['data']['id'];

        $credential = ApiCredential::firstWhere('key_client_id', $clientId);
        $privateKey = $credential->private_key;

        $this->artisan("client:changekey {$clientId}")
            ->expectsOutput('Success')
            ->expectsOutput('Api Keys successfully changed')
            ->assertExitCode(0);

        $newCredential = ApiCredential::firstWhere('key_client_id', $clientId);
        $newPrivateKey = $newCredential->private_key;

        $this->assertNotEquals($privateKey, $newPrivateKey);
    }
}
