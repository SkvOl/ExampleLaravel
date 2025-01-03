<?php

namespace App\Listeners\Logs;

use App\Events\Logs\LogsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogsListener// implements ShouldQueue
{
    /**
     * Имя очереди, на которую должна быть отправлена задача.
     *
     * @var string|null
     */
    //public $queue = 'listeners';
 
    /**
     * Время (в секундах), через которое должна быть обработана задача.
     *
     * @var int
     */
    //public $delay = 0;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LogsEvent $event): void
    {
        $event->log->save();
    }
}
