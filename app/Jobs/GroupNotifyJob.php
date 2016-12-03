<?php

namespace App\Jobs;

use Mail;
use App\GroupEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GroupNotifyJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $ge;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GroupEmail $ge)
    {
        $this->ge = $ge;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ge = $this->ge;

        $ge->group->contacts()->chunk(10, function ($contacts) use ($ge) {
            foreach ($contacts as $c) {
                $body = str_replace('%nome', $c->name, $ge->message);
                notify($c->email, $c->name, $ge->subject, $body, $ge->link);
            }
        });

        $ge->update(['is_finished' => true]);
    }
}
