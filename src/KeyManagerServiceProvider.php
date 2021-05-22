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
use Bytesfield\KeyManager\Commands\SuspendClientCommand;
use Bytesfield\KeyManager\Commands\ActivateClientCommand;
use Bytesfield\KeyManager\Middlewares\AuthenticateClient;
use Bytesfield\KeyManager\Commands\InstallKeyManagerCommand;
use Bytesfield\KeyManager\Commands\SuspendApiCredentialCommand;
use Bytesfield\KeyManager\Commands\ActivateApiCredentialCommand;
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

        $this->app->bind('key-manager', function ($app) {

            return new KeyManager($app->request);
        });
        $this->app->alias('key-manager', "Bytesfield\KeyManager\KeyManager");
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //Loads Package Migration
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        //Register Package Middleware
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('auth.client', AuthenticateClient::class);

        if ($this->app->runningInConsole()) {

            //Publishes Package Config
            $this->publishes([
                __DIR__ . '/config/keymanager.php' => config_path('keymanager.php'),
            ], 'config');

            //Publishes Migrations
            if (!class_exists('CreateKeyClientsTable') && !class_exists('CreateKeyApiCredentialsTable')) {
                $this->publishes([
                    __DIR__ . '/database/migrations/2020_12_19_075709_create_key_clients_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '1' . '_create_key_clients_table.php'),
                    __DIR__ . '/database/migrations/2020_12_19_075855_create_key_api_credentials_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '2' . '_create_key_api_credentials_table.php'),
                ], 'migrations');
            }

            //Register Package Console Commands
            $this->commands([
                GenerateEncryptionKeyCommand::class,
                CreateClientCommand::class,
                GetApiKeyCommand::class,
                ChangeKeysCommand::class,
                SuspendClientCommand::class,
                ActivateClientCommand::class,
                SuspendApiCredentialCommand::class,
                ActivateApiCredentialCommand::class,
                InstallKeyManagerCommand::class,

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

            $key = config('keymanager.encryption_key', '47f9c579776a486ce0592803c1174132ae190286dc87a498d938560f8bf31563');

            if (empty($key)) {
                throw new \RuntimeException(
                    'Encryption key for key manager service is not specified.'
                );
            }

            return new CipherSweet(new StringProvider($key), new ModernCrypto());
        });
    }
}
