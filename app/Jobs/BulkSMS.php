<?php

namespace App\Jobs;

use App\Message;
use App\SMSCounter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use stdClass;

class BulkSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Nombre de tentative d'exécution du job.
     * @var int
     */
    public $tries = 1;

    /**
     * Temps maximum d'exécution du job: passé ce temps, le job est tué
     * @var int (en seconde)
     */
    public $timeout = 90;

    /**
     * @var int
     * Nombre maximum de contacts par requête
     */
    private static $BATCH = 1;

    /**
     * @var array Destinataires
     */
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smsBatchs = array_chunk($this->message->getTo(), self::$BATCH);
        if(count($smsBatchs) > 0)
        {
            foreach($smsBatchs as $batch)
            {
                $this->message->setTo($batch);
                Queue::push(new SendSMS($this->message));
            }
        }
    }

}
