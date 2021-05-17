<?php

namespace Bytesfield\KeyManager\Http\Middleware;

use Bytesfield\KeyManager\Models\ApiCredential;
use Bytesfield\KeyManager\Models\Client;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class AuthenticateClient
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->authenticateClientFromRequest($request);

        return $next($request);
    }


    /**
     * Authenticate client from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Models\Client
     * @throws \Illuminate\Auth\AuthenticationException
     */
    private function authenticateClientFromRequest(Request $request): Client
    {
        if (!$privateKey = $request->header('api-auth-key')) {
            throw new AuthenticationException('API key in api-auth-key header is required.');
        }

        if (!$apiCredential = ApiCredential::findByPrivateKey($privateKey)) {
            throw new AuthenticationException('Invalid API private key.');
        }

        if ($apiCredential->status !== 'active') {
            throw new AuthenticationException('The private key is currently suspended.');
        }

        if ($apiCredential->client->status !== 'active') {
            throw new AuthenticationException('The owner of this private key is currently suspended.');
        }

        return $apiCredential->client;
    }
}
