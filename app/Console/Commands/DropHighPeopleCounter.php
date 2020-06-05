<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Widmogrod\Monad\Either\Left;

class DropHighPeopleCounter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drop:highpeople-counter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drops counter to a random value every hour and 20 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(\App\Services\HighPeopleService $highPeopleService)
    {
        if (($result = $highPeopleService->dropCounter()) instanceof Left) {
            $this->error(($result->extract())->getMessage());
            return;
        }

        $this->info('Counter dropped successfully!');
    }
}
