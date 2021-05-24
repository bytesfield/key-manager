<?php

namespace Bytesfield\KeyManager\Tests;

use Bytesfield\KeyManager\KeyManager;
use Bytesfield\KeyManager\KeyManagerServiceProvider;
use Bytesfield\KeyManager\Models\ApiCredential;
use Mockery as m;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->keyManager = app(KeyManager::class);
        $this->keyManagerMock = m::mock('Bytesfield\KeyManager\KeyManager');
    }

    public function tearDown(): void
    {
        m::close();
    }

    protected function getPackageProviders($app)
    {
        return [
            KeyManagerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Perform environment setup
    }

    protected function createNewClient()
    {
        return $this->keyManager->createClient('Amazon', 'user', ApiCredential::STATUSES['ACTIVE']);
    }
}
