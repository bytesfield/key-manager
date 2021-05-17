<?php

namespace Bytesfield\KeyManager\Commands;

use Illuminate\Console\Command;

use Bytesfield\KeyManager\KeyManagerInterface;

class CreateClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:create {name} {type} {userId?} {status=active}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create client with API keys';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    /**
     * The KeyManagerInterface
     *
     * @var KeyManagerInterface $manager
     */
    private $manager;

    /**
     *
     * @param KeyManagerInterface $manager
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
        $name = $this->argument('name');

        $type = $this->argument('type');

        $userId = $this->argument('userId');

        $status = $this->argument('status');

        $client = $this->manager->createClient($name, $type, $userId, $status);

        $this->info($client['status'] == true ? 'Success' : 'Failed');

        $this->info($client['message']);

        $key = $this->manager->getPrivateKey($client['data']['id']);

        $this->info("client key: " . $key['data']['key']);
    }
}
