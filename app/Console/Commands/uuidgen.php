<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class uuidgen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:uuidgen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // generate uuid remove dashes
        // $uuid = \Illuminate\Support\Str::uuid()->toString();
        // $uuid = \Illuminate\Support\Str::uuid();
        $uuid = \Illuminate\Support\Str::orderedUuid();
        echo $uuid . "\n";
    }
}
