<?php

namespace Bytesfield\KeyManager\Tests\Unit\Commands;

use Bytesfield\KeyManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateClientCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandCanCreateAClient(): void
    {
        $this->artisan('client:create Bytesfield user')
            ->expectsOutput('Success')
            ->expectsOutput('Client created successfully')
            ->assertExitCode(0);

        $this->assertDatabaseHas('key_clients', [
            'name' => 'Bytesfield',
            'type' => 'user',
        ]);
    }
}
