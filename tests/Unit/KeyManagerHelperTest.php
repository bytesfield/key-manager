<?php

namespace Bytesfield\KeyManager\Tests\Unit;

use Bytesfield\KeyManager\KeyManager;
use Bytesfield\KeyManager\Tests\TestCase;

class KeyManagerHelperTest extends TestCase
{

    public function testItReturnsInstanceOfKeyManagerIfCalledWithAlias()
    {
        $keymanager = $this->app->make("key-manager");

        $this->assertTrue($keymanager instanceof KeyManager);
    }

    function testItReturnsInstanceOfKeyManager()
    {
        $this->assertInstanceOf("Bytesfield\KeyManager\KeyManager", $this->keyManagerMock);
    }
}
