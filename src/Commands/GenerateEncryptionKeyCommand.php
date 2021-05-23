<?php

namespace Bytesfield\KeyManager\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateEncryptionKeyCommand extends Command
{
    protected const ENCRYPTION_KEY_NAME = 'API_ENCRYPTION_KEY';

    protected string $envFile = '.env';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encryption:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates new encryption key for api credentials';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        if (app()->environment() === 'testing') {
            $this->envFile = '.env.testing';
        }

        if (! File::exists($this->envFile)) {
            exec('cp .env.example '.$this->envFile);
            exec('echo "'.self::ENCRYPTION_KEY_NAME.'=">>'.$this->envFile);
        }

        if (! config('keymanager.api_encryption_key')) {
            exec('echo "'.self::ENCRYPTION_KEY_NAME.'=">>'.$this->envFile);
        }

        $envContents = File::get($this->envFile);
        $envVariablesArray = explode(PHP_EOL, $envContents);

        $filter = fn ($variable) => Str::contains($variable, self::ENCRYPTION_KEY_NAME);
        $envKeyValueToReplace = collect($envVariablesArray)->filter($filter)->first();

        $newKey = \bin2hex(\random_bytes(32));
        $newEncryptionKey = self::ENCRYPTION_KEY_NAME.'='.$newKey;

        $newContents = Str::replaceFirst($envKeyValueToReplace, $newEncryptionKey, $envContents);

        File::put($this->envFile, $newContents);
        $this->callSilently('config:clear');

        $this->info('New api encryption key generated');
    }
}
