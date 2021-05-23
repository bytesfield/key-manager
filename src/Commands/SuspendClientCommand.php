<?php

namespace Bytesfield\KeyManager\Commands;

use Bytesfield\KeyManager\KeyManagerInterface;
use Illuminate\Console\Command;

class SuspendClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:suspend {clientId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to suspend a client';

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
        $key = $this->manager->suspendClient($this->argument('clientId'));

        $this->info($key->getData()->status == true ? 'Success' : 'Failed');

        $this->info($key->getData()->message);
    }
}
