<?php


namespace App;


class Message
{
    /**
     * @var string Message à envoyer
     */
    private $body;
    /**
     * @var array Destinataires
     */
    private $to = [];
    /**
     * @var string Indicatif téléphone sans le +
     */
    private $indicatif;
    /**
     * @var string sender
     */
    private $sender;

    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @param array $to
     */
    public function setTo(array $to): void
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getIndicatif(): string
    {
        return $this->indicatif;
    }

    /**
     * @param string $indicatif
     */
    public function setIndicatif(string $indicatif): void
    {
        $this->indicatif = $indicatif;
    }

    /**
     * @return string
     */
    public function getSender(): string
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     */
    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }



}
