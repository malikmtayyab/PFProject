<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class everyFiveMinutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fiveMinute:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will print Echo Command on Web Page';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // echo "Command Executed Successfully.";
        $this->info('Command Executed Successfully.');
    }
}
