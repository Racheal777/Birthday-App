<?php

namespace App\Listeners;

use App\Events\StatusUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendStatusNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\StatusUpdate  $event
     * @return void
     */
    public function handle(StatusUpdate $event)
    {
        $user = $event->user;
         $status = $user->status;
         $user->update(['status' => !$status]);

        // $status = true;
        // $updated =   $user->status? !$status : $status;
        // $user->update(['status' => $updated]);


    }
}
