<?php

namespace App\Providers;

use App\Listeners\QueueMonitorListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        JobProcessing::class => [
            [QueueMonitorListener::class, 'handleProcessing'],
        ],
        JobProcessed::class => [
            [QueueMonitorListener::class, 'handleProcessed'],
        ],
        JobFailed::class => [
            [QueueMonitorListener::class, 'handleFailed'],
        ],

    ];

    public function boot(): void
    {
        //
    }
}
