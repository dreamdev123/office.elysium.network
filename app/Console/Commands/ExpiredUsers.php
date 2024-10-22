<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Blueprint\Services\UserServices;

class ExpiredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {
        $service = new UserServices();
        $service->expire_alarm();
        var_dump("expression");
        //
    }
}
