<?php

namespace Bytesfield\KeyManager\Commands;

use Bytesfield\KeyManager\KeyManagerInterface;
use Illuminate\Console\Command;

class GetApiKeyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:getkey {clientId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to get clients private key';

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
        $key = $this->manager->getPrivateKey($this->argument('clientId'));

        $this->info($key['status'] == true ? 'Success' : 'Failed');

        $this->info($key['message']);

        $this->info($key['data']['key']);
    }
}
