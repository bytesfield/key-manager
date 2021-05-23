<?php

namespace Bytesfield\KeyManager\Tests\Unit;

use Bytesfield\KeyManager\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiCredentialTest extends TestCase
{
    use RefreshDatabase;

    public function testItCanNotGetPrivateKeyIfClientDoesNotExist()
    {
        $response = $this->keyManager->getPrivateKey(20);

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanGetPrivateKeyIfClientExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->getPrivateKey($client['data']['id']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function testItCanNotChangeKeysIfClientDoesNotExist()
    {
        $response = $this->keyManager->changeKeys(20);

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanChangeKeysIfClientExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->changeKeys($client['data']['id']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function testItCanNotSuspendCredentialIfClientDoesNotExist()
    {
        $response = $this->keyManager->suspendApiCredential(20);

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanSuspendCredentialIfClientExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->suspendApiCredential($client['data']['id']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }

    public function testItCanNotActivateCredentialIfClientDoesNotExist()
    {
        $response = $this->keyManager->activateApiCredential(20);

        $this->assertFalse($response['status']);
        $this->assertEquals(400, $response['statusCode']);
    }

    public function testItCanActivateCredentialIfClientExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->activateApiCredential($client['data']['id']);

        $this->assertTrue($response['status']);
        $this->assertEquals(200, $response['statusCode']);
    }
}
