<?php

namespace Bytesfield\KeyManager\Tests\Unit\Commands;

use Bytesfield\KeyManager\Models\ApiCredential;
use Bytesfield\KeyManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetApiKeyCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandCanGetApiKey(): void
    {
        $client = $this->createNewClient();
        $clientId = $client['data']['id'];

        $credential = ApiCredential::firstWhere('key_client_id', $clientId);
        $privateKey = $credential->private_key;

        $this->artisan("client:getkey {$clientId}")
            ->expectsOutput('Success')
            ->expectsOutput($privateKey)
            ->assertExitCode(0);
    }
}
