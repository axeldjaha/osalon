<?php

namespace App\Jobs;

use App\Fakedata;
use App\SMSCounter;
use App\Token;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mediumart\Orange\SMS\Http\SMSClient;
use Mediumart\Orange\SMS\SMS;

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
    private $prefix = '+225';

    private $api;

    private $sender;

    private $token;

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

        $this->callOrangeAPI();
    }

    public function callOrangeAPI()
    {
        $token = Token::first();
        if ($token == null || Carbon::parse($token->valid_until)->lessThan(Carbon::now()))
        {
            $response = SMSClient::authorize(config("app.sms_client_id"), config("app.sms_client_secret"));
            DB::table("tokens")->truncate();
            $token = Token::create([
                "access_token" => $response["access_token"],
                "expires_in" => $response["expires_in"],
                "valid_until" => Carbon::now()->addSeconds($response["expires_in"]),
            ]);
        }

        \Mediumart\Orange\SMS\Http\SMSClientRequest::verify(false);

        $client = SMSClient::getInstance($token->access_token);
        $sms = new SMS($client);
        $sms->message($this->sms->message)
            ->from('+2250758572785', $this->sender ?? config("app.sms_sender"))
            ->to($this->sms->to)
            ->send();
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

}
