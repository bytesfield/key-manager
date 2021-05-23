<?php

namespace Bytesfield\KeyManager\Tests\Unit\Commands;

use Bytesfield\KeyManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InstallKeyManagerCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCommandCanInstallKeyManager(): void
    {
        $this->artisan('key-manager:install')
            ->expectsOutput('Key Manager Installed Successfully.')
            ->assertExitCode(0);
    }
}
