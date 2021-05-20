<?php

namespace App\Jobs;

use App\Message;
use App\Token;
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
     * @var Message SMS à envoyer
     */
    private $message;

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
     * @throws GuzzleException
     */
    public function handle()
    {
        //$this->callOrangeAPI();

        $this->letexto();
    }

    public function letexto()
    {
        $recipients = [];
        foreach ($this->message->getTo() as $to)
        {
            $recipients[] = [
                "phone" => $this->message->getIndicatif() . $to,
            ];
        }

        $data = [
            'step' => NULL,
            'sender' => $this->message->getSender(),
            'name' => 'Campagne',
            'campaignType' => 'SIMPLE',
            'recipientSource' => 'CUSTOM',
            'groupId' => NULL,
            'filename' => NULL,
            'saveAsModel' => false,
            'destination' => 'NAT',
            'message' => $this->message->getBody(),
            'emailText' => NULL,
            'recipients' => $recipients,
            'sendAt' => [],
        ];

        $response = Curl::to('https://api.letexto.com/v1/campaigns')
            ->withData(json_encode($data))
            ->withHeaders([
                'Authorization: Bearer ' . config("app.letexto_token"),
                'Content-Type: application/json'
            ])
            ->post();

        $id = json_decode($response)->id;

        Curl::to("https://api.letexto.com/v1/campaigns/$id/schedules")
            ->withData(json_encode($data))
            ->withHeaders([
                'Authorization: Bearer ' . config("app.letexto_token"),
                'Content-Type: application/json'
            ])
            ->post();
    }

    public function callOrangeAPI()
    {
        $to = '';
        for($count = 0, $size = count($this->message->getTo()); $count < $size; $count++)
        {
            $to .= ($count != ($size - 1)) ?
                ("+" . $this->message->getIndicatif() . $this->message->getTo()[$count] . ',') :
                ("+" . $this->message->getIndicatif() . $this->message->getTo()[$count]);
        }

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
        $sms->message($this->message->getBody())
            ->from('+2250758572785', $this->message->getSender())
            //->from('+2250758572785', null)
            ->to($to)
            ->send();
    }

}
