<?php

namespace Bytesfield\KeyManager\Commands;

use Illuminate\Console\Command;

class InstallKeyManagerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'key-manager:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install KeyManager Package';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Installing Key Manager...');

        $this->info('Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => "Bytesfield\KeyManager\KeyManagerServiceProvider",
            '--tag' => 'config',
        ]);

        if (! class_exists('CreateKeyClientsTable') && ! class_exists('CreateKeyApiCredentialsTable')) {
            $this->call('vendor:publish', [
                '--provider' => "Bytesfield\KeyManager\KeyManagerServiceProvider",
                '--tag' => 'migrations',
            ]);
        }

        $this->info('Key Manager Installed Successfully.');
    }
}
