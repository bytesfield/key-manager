<?php

namespace Bytesfield\KeyManager\Tests\Unit;

use Bytesfield\KeyManager\Tests\TestCase;

class KeyManagerServiceProviderTest extends TestCase
{
    /**
     * Tests if Service Provider Binds alias "keymanager" to \Bytesfield\KeyManager\KeyManager
     */
    public function testIsBound()
    {
        $this->assertTrue($this->app->bound('keymanager'));
    }

    /**
     * Test if Service Provider returns \KeyManager as alias for \Bytesfield\KeyManager\KeyManager
     */
    public function testHasAliased()
    {
        $alias = "Bytesfield\KeyManager\KeyManager";

        $this->assertTrue($this->app->isAlias($alias));
        $this->assertEquals('keymanager', $this->app->getAlias($alias));
    }
}
