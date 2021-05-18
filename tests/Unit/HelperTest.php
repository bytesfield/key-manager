<?php

namespace Bytesfield\KeyManager\Tests\Unit;

use Bytesfield\KeyManager\KeyManager;
use Bytesfield\KeyManager\Tests\TestCase;

class HelperTest extends TestCase
{

    /**
     * Tests if app returns \Bytesfield\KeyManager\KeyManager if called with alias.
     *
     * @test
     * @return \Bytesfield\KeyManager\KeyManager
     */
    public function initiateKeyManagerFromApp()
    {

        $keymanager = $this->app->make("keymanager");

        $this->assertTrue($keymanager instanceof KeyManager);

        return $keymanager;
    }

    // /**
    //  * Tests that helper returns
    //  *
    //  * @test
    //  * @return void
    //  */
    // function itReturnsInstanceOfKeyManager()
    // {

    //     $this->assertInstanceOf("Bytesfield\KeyManager\KeyManager", $this->keymanager);
    // }
}
