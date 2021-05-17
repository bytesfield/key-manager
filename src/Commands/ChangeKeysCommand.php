<?php

namespace Bytesfield\KeyManager\Commands;

use Illuminate\Console\Command;

use Bytesfield\KeyManager\KeyManagerInterface;

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
     * The KeyManagerInterface
     *
     * @var KeyManagerInterface $manager
     */
    private $manager;

    /**
     *
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
        $this->info($key['status'] == true ? 'Success' : "Failed");

        $this->info($key['message']);

        $this->info($key['data']['key']);
    }
}
