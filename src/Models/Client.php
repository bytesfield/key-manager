<?php

namespace Bytesfield\KeyManager\Models;

use Illuminate\Database\Eloquent\Model;
use Bytesfield\KeyManager\Models\ApiCredential;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    //use LoggerOverided;

    /**
     * log any attribute that has been affected in this model
     *
     * @var array
     */
    protected static $logAttributes = ['*'];

    /**
     * chose whether to log only changed attributes
     *
     * @var boolean
     */
    protected static $logOnlyDirty = true;

    /**
     * Modify the description for events
     *
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This User has been {$eventName}";
    }

    /**
     * Get the api key for the related client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apiCredential(): HasOne
    {
        return $this->hasOne(ApiCredential::class, 'client_id');
    }
}
