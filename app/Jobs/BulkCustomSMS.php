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

class BulkCustomSMS implements ShouldQueue
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
    protected $smsArray;

    protected $sender;

    /**
     * Create a new job instance.
     *
     * @param array $smsArray
     * @param null $sender
     */
    public function __construct($smsArray = [], $sender = null)
    {
        $this->smsArray = $smsArray;
        $this->sender = $sender;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customSMSArray = array_chunk($this->smsArray->to, self::$BATCH);
        if(count($customSMSArray) > 0)
        {
            foreach($customSMSArray as $sms)
            {
                Queue::push(new SendSMS($sms, $this->sender));
            }
        }
    }

}
