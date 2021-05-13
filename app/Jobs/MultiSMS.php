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

class MultiSMS implements ShouldQueue
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

    protected $country_code;

    /**
     * Create a new job instance.
     *
     * @param array $smsArray
     * @param null $sender
     */
    public function __construct($smsArray = [], $sender = null, $country_code = null)
    {
        $this->smsArray = $smsArray;
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
        foreach($this->smsArray as $sms)
        {
            Queue::push(new SendSMS($sms, $this->sender, $this->country_code));
        }
    }

}
