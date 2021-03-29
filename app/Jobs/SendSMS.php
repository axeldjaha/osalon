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
    public function __construct(\stdClass $sms, $sender = null)
    {
        $this->sms = $sms;
        $this->sender = $sender;

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

        $this->letexto();
    }

    /**
     * API: 1s2u.com
     */
    public function letexto()
    {
        $baseUrl = 'http://www.letexto.com/send_message';

        /**
         * Compte développeur (principal)
         * 23 Fr/SMS
         * $user = '/user/paxeldp@gmail.com';
         * $secret = '/secret/aayxEoIfSNMykVKsvJ9UG7tyXoiJpUfsUFPnTmvB';
         */
        $baseUrl = 'http://www.letexto.com/sendCampaign';
        $email = 'paxeldp@gmail.com';
        $secret = 'aayxEoIfSNMykVKsvJ9UG7tyXoiJpUfsUFPnTmvB';
        $message = $this->sms->message;
        $receiver = $this->sms->to;
        $sender = $this->sender ?? config("app.sms_sender");
        $cltmsgid = 1;

        $client = new Client();
        $client->request('POST', $baseUrl, [
            'query' => [
                'email' => $email,
                'secret' => $secret,
                'message' => $message,
                'receiver' => $receiver,
                'sender' => $sender,
                'cltmsgid' => $cltmsgid,
            ]
        ]);
    }

    /**
     * API: 1s2u.com
     *
     * @param \stdClass $smsObject
     * @throws GuzzleException
     */
    public function sendWith1s2u()
    {
        $sender = $this->sender ?? config("app.sms_sender");

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
