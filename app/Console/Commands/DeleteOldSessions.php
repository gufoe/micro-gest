<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Session;

class DeleteOldSessions extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'delete-old-sessions';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete sessions older than x days';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $time = '7 days ago';
        $threshold = date('Y-m-d H:i:s', strtotime($time));
        $num = Session::where('updated_at', '<', $threshold)->delete();
        $this->comment("Deleted {$num} sessions updated before {$time}");
    }
}
