<?php

namespace Bytesfield\KeyManager\Commands;

use Bytesfield\KeyManager\KeyManagerInterface;
use Illuminate\Console\Command;

class ChangeKeysCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:changekey {clientId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to change client public/private keys';

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
     * @return void
     */
    public function handle(): void
    {
        $key = $this->manager->changeKeys($this->argument('clientId'));
        $this->info($key->getData()->status == true ? 'Success' : 'Failed');

        $this->info($key->getData()->message);

        $this->info($key->getData()->data->key);
    }
}
