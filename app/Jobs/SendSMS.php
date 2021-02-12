<?php

namespace App\Jobs;

use App\SMSCounter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Nombre maximum de tentative d'exécution du job en cas d'échec:
     * @var int
     */
    public $tries = 3;

    /**
     * Temps maximum d'exécution du job: passé ce temps, le job est tué
     * @var int (en seconde)
     */
    public $timeout = 60;

    /**
     * SMS à envoyer
     * @var
     */
    private $sms;

    /**
     * Prefix des numéros
     * @var string
     */
    private $prefix = '225';

    private $api;

    private $sender;

    //todo To remove when Moov SMS API is implemented
    private $smsCounter;

    /**
     * Create a new job instance.
     *
     * @param \stdClass $sms
     * @param null $api
     * @param null $sender
     */
    public function __construct(\stdClass $sms)
    {
        $this->sms = $sms;

        //todo To remove when Moov SMS API is implemented
        $this->smsCounter = new SMSCounter();
        $this->smsCounter->smsInfo = $this->smsCounter->count($sms->message);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle()
    {
        $to = '';
        for($count = 0, $size = count($this->sms->to); $count < $size; $count++)
        {
            $to .= ($count != ($size - 1)) ? $this->prefix . $this->sms->to[$count] . ',' : $this->prefix . $this->sms->to[$count];
        }

        $this->sms->to = $to;

        $this->sendWith1s2u();
    }

    /**
     * API: 1s2u.com
     */
    public function letexto()
    {
        $sender = env("SMS_SENDER");

        $baseUrl = 'http://www.letexto.com/send_message';

        /**
         * Compte développeur (principal)
         * 23 Fr/SMS
         * $user = '/user/paxeldp@gmail.com';
         * $secret = '/secret/aayxEoIfSNMykVKsvJ9UG7tyXoiJpUfsUFPnTmvB';
         */

        $user = '/user/paxeldp@gmail.com';
        $secret = '/secret/aayxEoIfSNMykVKsvJ9UG7tyXoiJpUfsUFPnTmvB';
        $msg = '/msg/'.$this->sms->message;
        $receiver = '/receiver/'.$this->sms->to;
        $sender = '/sender/'.$sender;
        $cltmsgid = '/cltmsgid/'.'1';
        $baseUrl .= $user;
        $baseUrl .= $secret;
        $baseUrl .= $msg;
        $baseUrl .= $receiver;
        $baseUrl .= $sender;
        $baseUrl .= $cltmsgid;

        $client = new Client();
        $client->request('GET', $baseUrl);
    }

    /**
     * API: 1s2u.com
     *
     * @param \stdClass $smsObject
     * @throws GuzzleException
     */
    public function sendWith1s2u()
    {
        $sender = env("SMS_SENDER");

        $this->sms->type = Str::is($this->smsCounter->smsInfo->encoding, 'UTF16') ? 1 : 0;

        $baseUrl = 'https://api.1s2u.io/bulksms';
        $userName = 'axeldjaha';
        $password = '1s2uzn6';
        $from = $sender;
        $to = $this->sms->to;
        $text = $this->sms->message;
        $type = $this->sms->type;
        $fl = 0;

        $client = new Client();
        $status = $client->request('GET', $baseUrl, [
            'query' => [
                'username' => $userName,
                'password' => $password,
                'mt' => $type,
                'fl' => $fl,
                'Sid' => $from,
                'mno' => $to,
                'msg' => $text,
            ]
        ]);
    }


}
