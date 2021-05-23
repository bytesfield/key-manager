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

        $this->assertFalse($response->getData()->status);
        $this->assertEquals(409, $response->getData()->statusCode);
    }

    public function testItCanGetPrivateKeyIfClientExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->getPrivateKey($client->getData()->data->id);
        
        $this->assertTrue($response->getData()->status);
        $this->assertEquals(200, $response->getData()->statusCode);
    }

    public function testItCanNotChangeKeysIfClientDoesNotExist()
    {
        $response = $this->keyManager->changeKeys(20);

        $this->assertFalse($response->getData()->status);
        $this->assertEquals(409, $response->getData()->statusCode);
    }

    public function testItCanChangeKeysIfClientExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->changeKeys($client->getData()->data->id);

        $this->assertTrue($response->getData()->status);
        $this->assertEquals(200, $response->getData()->statusCode);
    }

    public function testItCanNotSuspendCredentialIfClientDoesNotExist()
    {
        $response = $this->keyManager->suspendApiCredential(20);

        $this->assertFalse($response->getData()->status);
        $this->assertEquals(409, $response->getData()->statusCode);
    }

    public function testItCanSuspendCredentialIfClientExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->suspendApiCredential($client->getData()->data->id);

        $this->assertTrue($response->getData()->status);
        $this->assertEquals(200, $response->getData()->statusCode);
    }

    public function testItCanNotActivateCredentialIfClientDoesNotExist()
    {
        $response = $this->keyManager->activateApiCredential(20);

        $this->assertFalse($response->getData()->status);
        $this->assertEquals(409, $response->getData()->statusCode);
    }

    public function testItCanActivateCredentialIfClientExist()
    {
        $client = $this->createNewClient();

        $response = $this->keyManager->activateApiCredential($client->getData()->data->id);

        $this->assertTrue($response->getData()->status);
        $this->assertEquals(200, $response->getData()->statusCode);
    }
}
