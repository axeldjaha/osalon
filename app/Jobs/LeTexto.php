<?php

namespace App\Jobs;

use App\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ixudra\Curl\Facades\Curl;

class LeTexto implements ShouldQueue
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
     */
    public function handle()
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
}
