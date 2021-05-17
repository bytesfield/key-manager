<?php

namespace Bytesfield\KeyManager;

use Illuminate\Routing\Router;
use Bytesfield\KeyManager\KeyManager;
use ParagonIE\CipherSweet\CipherSweet;
use Illuminate\Support\ServiceProvider;
use Bytesfield\KeyManager\KeyManagerInterface;
use ParagonIE\CipherSweet\Backend\ModernCrypto;
use Bytesfield\KeyManager\Commands\GetApiKeyCommand;
use Bytesfield\KeyManager\Commands\ChangeKeysCommand;
use ParagonIE\CipherSweet\KeyProvider\StringProvider;
use Bytesfield\KeyManager\Commands\CreateClientCommand;
use Bytesfield\KeyManager\Http\Middleware\AuthenticateClient;
use Bytesfield\KeyManager\Commands\GenerateEncryptionKeyCommand;


class KeyManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCipherSweet();
        $this->app->singleton(KeyManagerInterface::class, KeyManager::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->publishes([
            __DIR__ . '/config/keymanager.php' => config_path('keymanager.php'),
        ]);

        //register our middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('auth.client', AuthenticateClient::class);

        //register our console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateEncryptionKeyCommand::class,
                CreateClientCommand::class,
                GetApiKeyCommand::class,
                ChangeKeysCommand::class,

            ]);
        }
    }

    /**
     * Register CipherSweet library.
     *
     * @return void
     */
    private function registerCipherSweet(): void
    {
        $this->app->singleton(CipherSweet::class, function () {
            $key = config('keymanager.encryption_key');

            if (empty($key)) {
                throw new \RuntimeException(
                    'Encryption key for key manager service is not specified.'
                );
            }

            return new CipherSweet(new StringProvider($key), new ModernCrypto());
        });
    }
}
