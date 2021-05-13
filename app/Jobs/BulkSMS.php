<?php

namespace App\Jobs;

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
    protected $sms;

    protected $sender;

    protected $country_code;

    /**
     * Create a new job instance.
     *
     * @param $message
     * @param array $to
     * @param null $sender
     * @param null $country_code
     */
    public function __construct($message, $to = [], $sender = null, $country_code = null)
    {
        $this->sms = new stdClass();
        $this->sms->message = $message;
        $this->sms->to = $to;
        $this->sender = $sender;
        $this->country_code = $country_code;
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
                $this->sms->to = $batch;
                Queue::push(new SendSMS($this->sms, $this->sender, $this->country_code));
            }
        }
    }

}
