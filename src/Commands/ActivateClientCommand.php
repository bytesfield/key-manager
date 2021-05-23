<?php

namespace Bytesfield\KeyManager\Commands;

use Bytesfield\KeyManager\KeyManagerInterface;
use Illuminate\Console\Command;

class ActivateClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:activate {clientId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to activate a client';

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
        $key = $this->manager->activateClient($this->argument('clientId'));

        $this->info($key->getData()->status == true ? 'Success' : 'Failed');

        $this->info($key->getData()->message);
    }
}
