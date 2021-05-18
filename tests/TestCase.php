<?php

namespace Bytesfield\KeyManager\Tests;

use Mockery as m;
use Bytesfield\KeyManager\KeyManager;
use Bytesfield\KeyManager\Models\ApiCredential;
use Bytesfield\KeyManager\KeyManagerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $keyManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->keyManager = m::mock('Bytesfield\KeyManager\KeyManager');
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
        include_once __DIR__ . '/../src/database/migrations/2020_12_19_075709_create_key_clients_table.php';
        include_once __DIR__ . '/../src/database/migrations/2020_12_19_075855_create_key_api_credentials_table.php';

        // run the up() method (perform the migration)
        (new \CreateKeyClientsTable)->up();
        (new \CreateKeyApiCredentialsTable)->up();
    }
}
