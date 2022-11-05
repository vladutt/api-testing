<?php

namespace App\Console\Commands;

use App\Models\Endpoint;
use Illuminate\Console\Command;

class RestTestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $endpoints = Endpoint::with(['endpoints.auth', 'endpoints.parameters', 'host'])->cursor();

        foreach ($endpoints as $endpoint) {
            // send to RabbitMQ
        }
    }
}
