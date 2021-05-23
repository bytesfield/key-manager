<?php

namespace Bytesfield\KeyManager\Tests\Unit\Commands;

use Bytesfield\KeyManager\Models\ApiCredential;
use Bytesfield\KeyManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SuspendApiCredentialCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandCanSuspendApiCredential(): void
    {
        $client = $this->createNewClient();
        $clientId = $client['data']['id'];

        $this->artisan("client:suspend-key {$clientId}")
            ->expectsOutput('Success')
            ->expectsOutput('ApiCredential successfully suspended')
            ->assertExitCode(0);

        $this->assertDatabaseHas('key_api_credentials', [
            'key_client_id' => $clientId,
            'status' => ApiCredential::STATUSES['SUSPENDED'],
        ]);
    }
}
