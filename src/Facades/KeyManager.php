<?php

namespace Bytesfield\KeyManager\Facades;

use Illuminate\Support\Facades\Facade;

class KeyManager extends Facade
{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'key-manager';
    }
}
