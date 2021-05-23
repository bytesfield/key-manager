<?php

namespace Bytesfield\KeyManager\Commands;

use Bytesfield\KeyManager\KeyManagerInterface;
use Illuminate\Console\Command;

class ActivateApiCredentialCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:activate-key {clientId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to activate a client credential';

    /**
     * The KeyManagerInterface.
     *
     * @var KeyManagerInterface
     */
    private $manager;

    /**
     * @param KeyManagerInterface $manager
     * @return void
     */
    public function __construct(KeyManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $key = $this->manager->activateApiCredential($this->argument('clientId'));

        $this->info($key->getData()->status == true ? 'Success' : 'Failed');

        $this->info($key->getData()->message);
    }
}
