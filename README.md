<p align="center"><img src="/images/key-pairs.jpg" alt="Key Manager Preview"></p>

# Key Manager

[![Latest Stable Version](https://poser.pugx.org/bytesfield/key-manager/v)](//packagist.org/packages/bytesfield/key-manager)
[![License](https://poser.pugx.org/bytesfield/key-manager/license)](//packagist.org/packages/bytesfield/key-manager)

Key Manager is a Laravel Package for generating public and private key pairs storing, retrieving and authenticating using the private key.

## Installation

[PHP](https://php.net) 7.4+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Key Manager, simply require it

```bash
composer require bytesfield/key-manager
```

Or add the following line to the require block of your `composer.json` file.

```
"bytesfield/key-manager": "1.0.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once KeyManager is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

```php
'providers' => [
    ...
    Bytesfield\KeyManager\KeyManagerServiceProvider::class,
    ...
]
```

> If you use **Laravel >= 5.5** you can skip this step and go to [**`configuration`**](https://github.com/bytesfield/key-manager#configuration)

-   `Bytesfield\KeyManager\KeyManagerServiceProvider::class,`

Also, register the Facade like so:

```php
'aliases' => [
    ...
    'KeyManager' => Bytesfield\KeyManager\Facades\KeyManager::class,
    ...
]
```

## Configuration

You can publish the configuration file using this command:

```bash
php artisan key-manager:install
```

This publishes a configuration-file named `keymanager.php` with some sensible defaults will be placed in your `config` directory and two migration files `create_key_clients_table` and `create_key_api_credentials_table` places in your `database\migrations` directory:

```php
<?php

return [

    /**
     * Generated API Encryption Key
     *
     */
    'encryption_key' => env('API_ENCRYPTION_KEY'),

];
```

Then run this command to migrate your database

```bash
php artisan migrate
```

## Usage

Generate API Encryption Key by running this command on your terminal

```bash
php artisan encryption:generate
```

This will generate an encryption key in your .env like so:

```php
API_ENCRYPTION_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

```

_If you are using a hosting service like heroku, ensure to add the above details to your configuration variables._

#### Using KeyManager Facade

Import the KeyManager Facade

```php
use Bytesfield\KeyManager\Facades\KeyManager;
```

Create a Key Client

```php

KeyManager::createClient(string $name, string $type);
// Optional
KeyManager::createClient(string $name, string $type, string $status);
```

This creates a client and `public` and `private` keys pairs

Note : `$status` param can either be `active` or `suspended` while `$type` is dependent on what you want e.g `user` or `admin`.

Get Private Key

```php
KeyManager::getPrivateKey(int $clientId);
```

Change Key Pairs

```php
KeyManager::changeKeys(int $clientId);
```

Suspend a client

```php
KeyManager::suspendClient(int $clientId);
```

Activate a Client

```php
KeyManager::activateClient(int $clientId);
```

Suspend an API Credential

```php
KeyManager::suspendApiCredential(int $clientId);
```

Activate an API Credential

```php
KeyManager::activateApiCredential(int $clientId);
```

#### Injecting KeyManager or KeyManagerInterface in a constructor

Import the KeyManager or KeyManagerInterface

```php
use Bytesfield\KeyManager\KeyManager;
```

```php
public function __construct(KeyManager $keyManager)
{
    $this->keyManager = $keyManager;
}
```

Or

```php
use Bytesfield\KeyManager\KeyManagerInterface;
```

```php
public function __construct(KeyManagerInterface $keyManager)
{
    $this->keyManager = $keyManager;
}
```

Create a Key Client

```php

$this->keyManager->createClient(string $name, string $type);
// Optional
$this->keyManager->createClient(string $name, string $type, string $status);
```

This creates a client and `public` and `private` keys pairs

Note : `$status` param can either be `active` or `suspended` while `$type` is dependent on what you want e.g `user` or `admin`.

Get Private Key

```php
$this->keyManager->getPrivateKey(int $clientId);
```

Change Key Pairs

```php
$this->keyManager->changeKeys(int $clientId);
```

Suspend a Client

```php
$this->keyManager->suspendClient(int $clientId);
```

Activate a Client

```php
$this->keyManager->activateClient(int $clientId);
```

Suspend an API Credential

```php
$this->keyManager->suspendApiCredential(int $clientId);
```

Activate an API Credential

```php
$this->keyManager->activateApiCredential(int $clientId);
```

#### Using Commands

You can use commands to perform these actions too.

Create a Key Client

```bash

client:create {name} {type}

```

Or

```bash

client:create {name} {type} {status=active}
```

This creates a client and `public` and `private` keys pairs

Note : `$status` param can either be `active` or `suspended` while `$type` is dependent on what you want e.g `user` or `admin`.

Get Private Key

```bash
client:getkey {clientId}
```

Change Key Pairs

```bash
client:changekey {clientId}
```

Suspend a client

```bash
client:suspend {clientId}
```

Activate a Client

```bash
client:activate {clientId}
```

Suspend an API Credential

```bash
client:suspend-key {clientId}
```

Activate an API Credential

```bash
client:activate-key {clientId}
```

#### Using the middleware to protect your routes.

In your route add `auth.client` middleware

```php
Route::get('test', function(){
    return "Hello world";
})->middleware('auth.client');
```

Or

In your controller add `auth.client`

```php
public function __construct(){
    $this->middleware('auth.client');
}
```

This Middleware Authenticates a client with a valid private key `api-auth-key` which is to be passed to the request header.

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email abrahamudele@gmail instead of using the issue tracker.

## Credits

-   [Abraham Udele](https://github.com/bytesfield)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
