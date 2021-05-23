<?php

namespace Bytesfield\KeyManager\Tests\Unit\Commands;

use Bytesfield\KeyManager\Models\ApiCredential;
use Bytesfield\KeyManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivateClientCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandCanActivateAClient(): void
    {
        $client = $this->createNewClient();
        $clientId = $client['data']['id'];

        $this->artisan("client:activate {$clientId}")
            ->expectsOutput('Success')
            ->expectsOutput('Client successfully activated')
            ->assertExitCode(0);

        $this->assertDatabaseHas('key_clients', [
            'id' => $clientId,
            'name' => $client['data']['name'],
            'type' => $client['data']['type'],
            'status' => ApiCredential::STATUSES['ACTIVE'],
        ]);
    }
}
