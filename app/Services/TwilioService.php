<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $sid;
    protected $token;
    protected $from;

    public function __construct()
    {
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->from = config('services.twilio.from');
    }

    public function sendSms($to, $message)
    {
        $client = new Client($this->sid, $this->token);

        try {
            $message = $client->messages->create(
                $to,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );

            return $message->sid;
        } catch (\Exception $e) {
            // Gérez l'exception comme vous le souhaitez
            throw new \Exception('Erreur lors de l\'envoi du SMS : ' . $e->getMessage());
        }
    }
}
