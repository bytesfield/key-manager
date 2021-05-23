<?php

namespace Bytesfield\KeyManager\Commands;

use Bytesfield\KeyManager\KeyManagerInterface;
use Illuminate\Console\Command;

class CreateClientCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:create {name} {type} {status=active}';

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
     * The KeyManagerInterface.
     *
     * @var KeyManagerInterface
     */
    private $manager;

    /**
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

        $status = $this->argument('status');

        $client = $this->manager->createClient($name, $type, $status);

        $this->info($client->getData()->status == true ? 'Success' : 'Failed');

        $this->info($client->getData()->message);

        $key = $this->manager->getPrivateKey($client->getData()->data->id);

        $this->info('client key: '.$key->getData()->data->key);
    }
}
