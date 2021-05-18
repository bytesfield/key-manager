<?php

namespace Bytesfield\KeyManager\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Bytesfield\KeyManager\KeyManager;
use Bytesfield\KeyManager\Models\ApiCredential;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClientTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    public function testItCanCreateClient()
    {
        $key = app(KeyManager::class);

        $response = $key->createClient('Amazon', 'user', ApiCredential::STATUSES['ACTIVE']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function testItCanNotCreateClientWithInvalidStatus()
    {
        $key = app(KeyManager::class);

        $response = $key->createClient('Amazon', 'user', 'live');

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanNotSuspendClientThatDoesNotExist()
    {
        $key = app(KeyManager::class);

        $response = $key->suspendClient(20);

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }
    public function testItCanSuspendClientThatExist()
    {
        $client = $this->createNewClient();

        $key = app(KeyManager::class);

        $response = $key->suspendClient($client['data']['id']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function testItCanNotActivateClientThatDoesNotExist()
    {
        $key = app(KeyManager::class);

        $response = $key->activateClient(20);

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanActivateClientThatExist()
    {
        $client = $this->createNewClient();

        $key = app(KeyManager::class);

        $response = $key->activateClient($client['data']['id']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function createNewClient()
    {
        $key = app(KeyManager::class);

        return $key->createClient('Amazon', 'user', ApiCredential::STATUSES['ACTIVE']);
    }
}
