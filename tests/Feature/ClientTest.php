<?php

namespace Bytesfield\KeyManager\Tests\Unit;

use Bytesfield\KeyManager\Models\ApiCredential;
use Bytesfield\KeyManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanCreateClient()
    {
        $response = $this->keyManager->createClient('Amazon', 'user', ApiCredential::STATUSES['ACTIVE']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function testItCanNotCreateClientWithInvalidStatus()
    {
        $response = $this->keyManager->createClient('Amazon', 'user', 'live');

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanNotSuspendClientThatDoesNotExist()
    {
        $response = $this->keyManager->suspendClient(20);

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanSuspendClientThatExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->suspendClient($client['data']['id']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function testItCanNotActivateClientThatDoesNotExist()
    {
        $response = $this->keyManager->activateClient(20);

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanActivateClientThatExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->activateClient($client['data']['id']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }
}
