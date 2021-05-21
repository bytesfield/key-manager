<?php

namespace Bytesfield\KeyManager\Tests\Unit\Commands;

use Bytesfield\KeyManager\Tests\TestCase;
use Bytesfield\KeyManager\Models\ApiCredential;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivateApiCredentialCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandCanActivateApiCredential(): void
    {
        $client = $this->createNewClient();
        $clientId = $client['data']['id'];

        $this->artisan("client:activate-key {$clientId}")
            ->expectsOutput('Success')
            ->expectsOutput('ApiCredential successfully activated')
            ->assertExitCode(0);

        $this->assertDatabaseHas('key_api_credentials', [
            'key_client_id' => $clientId,
            'status' => ApiCredential::STATUSES['ACTIVE']
        ]);
    }
}
