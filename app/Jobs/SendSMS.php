<?php

namespace App\Jobs;

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
use Ixudra\Curl\Facades\Curl;
use Mediumart\Orange\SMS\Http\SMSClient;
use Mediumart\Orange\SMS\Http\SMSClientRequest;
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

    protected $country_code;

    private $token;

    /**
     * Create a new job instance.
     *
     * @param \stdClass $sms
     * @param null $sender
     * @param null $country_code
     */
    public function __construct(\stdClass $sms, $sender = null, $country_code = null)
    {
        $this->sms = $sms;
        $this->sender = $sender;
        if($country_code != null)
        {
            $this->country_code = "+" . $country_code;
        }
        else
        {
            $this->country_code = $this->prefix;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws GuzzleException
     */
    public function handle()
    {
        $this->callOrangeAPI();
    }

    public function letexto()
    {
        $recipients = [];
        foreach ($this->sms->to as $to)
        {
            $recipients[] = [
                "phone" => $this->prefix . $to,
            ];
        }

        $data = [
            'step' => NULL,
            'sender' => $this->sender ?? config("app.sms_sender"),
            'name' => 'Campagne',
            'campaignType' => 'SIMPLE',
            'recipientSource' => 'CUSTOM',
            'groupId' => NULL,
            'filename' => NULL,
            'saveAsModel' => false,
            'destination' => 'NAT',
            'message' => $this->sms->message,
            'emailText' => NULL,
            'recipients' => $recipients,
            'sendAt' => [],
        ];

        $response = Curl::to('https://api.letexto.com/v1/campaigns')
            ->withData(json_encode($data))
            ->withHeaders([
                'Authorization: Bearer a6db19270e853ad23483ba31973659',
                'Content-Type: application/json'
            ])
            ->post();

        $id = json_decode($response)->id;

        $response = Curl::to("https://api.letexto.com/v1/campaigns/$id/schedules")
            ->withData(json_encode($data))
            ->withHeaders([
                'Authorization: Bearer a6db19270e853ad23483ba31973659',
                'Content-Type: application/json'
            ])
            ->post();
    }

    public function callOrangeAPI()
    {
        $to = '';
        for($count = 0, $size = count($this->sms->to); $count < $size; $count++)
        {
            $to .= ($count != ($size - 1)) ? $this->country_code . $this->sms->to[$count] . ',' : $this->country_code . $this->sms->to[$count];
        }

        $this->sms->to = $to;

        if(config("app.env") != "production")
        {
            SMSClientRequest::verify(false);
        }

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

        $client = SMSClient::getInstance($token->access_token);
        $sms = new SMS($client);
        $sms->message($this->sms->message)
            ->from('+2250758572785', $this->sender ?? config("app.sms_sender"))
            //->from('+2250758572785', null)
            ->to($this->sms->to)
            ->send();
    }

}
