<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Queue;
use stdClass;

class SMSDispatcher implements ShouldQueue
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
    private static $BATCH = 15;

    /**
     * @var array Destinataires
     */
    protected $sms;

    /**
     * Create a new job instance.
     *
     * @param $to
     * @param $message
     */
    public function __construct($to = [], $message)
    {
        $this->sms = new stdClass();
        $this->sms->to = $to;
        $this->sms->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smsBatchs = array_chunk($this->sms->to, self::$BATCH);
        if(count($smsBatchs) > 0)
        {
            foreach($smsBatchs as $batch)
            {
                $sms = new stdClass();
                $sms->to = $batch;
                $sms->message = $this->sms->message;
                Queue::push(new SendSMS($sms));
            }
        }
    }

}
