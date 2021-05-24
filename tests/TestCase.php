<?php

namespace Bytesfield\KeyManager\Tests;

use Bytesfield\KeyManager\KeyManager;
use Bytesfield\KeyManager\KeyManagerServiceProvider;
use Bytesfield\KeyManager\Models\ApiCredential;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery as m;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use WithFaker;

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
        $this->loadMigrations();
    }

    private function loadMigrations()
    {
        include_once __DIR__ .'/../src/database/migrations/2020_12_19_075709_create_key_clients_table.php.stub';
        include_once __DIR__ .'/../src/database/migrations/2020_12_19_075855_create_key_api_credentials_table.php.stub';

        // run the up() method (perform the migration)
        (new \CreateKeyClientsTable)->up();
        (new \CreateKeyApiCredentialsTable)->up();
    }

    protected function createNewClient()
    {
        return $this->keyManager->createClient($this->faker->name, 'user', ApiCredential::STATUSES['ACTIVE']);
    }
}
