<?php

namespace Bytesfield\KeyManager\Models;

use Illuminate\Database\Eloquent\Model;
use Bytesfield\KeyManager\Models\ApiCredential;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'key_clients';

    /**
     * Get the api key for the related client
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apiCredential(): HasOne
    {
        return $this->hasOne(ApiCredential::class, 'key_client_id');
    }
}
