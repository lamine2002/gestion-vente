<?php

namespace App\Services;

use HTTP_Request2;
use HTTP_Request2_Exception;

class SmsService
{
    /**
     * @throws \HTTP_Request2_LogicException
     */
    public function sendSms($phoneNumber, $message)
    {
        $request = new HTTP_Request2();
        $request->setUrl('https://8gdx51.api.infobip.com/sms/2/text/advanced');
        $request->setMethod(HTTP_Request2::METHOD_POST);
        $request->setConfig(array(
            'follow_redirects' => TRUE
        ));
        $request->setHeader(array(
            'Authorization' => 'App addcc0224edb0b0f149b0f9b957e2416-0d243295-730e-40b1-9b67-1458e1f5d302',
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ));

        $body = json_encode([
            'messages' => [
                [
                    'destinations' => [['to' => $phoneNumber]],
                    'from' => 'ServiceSMS',
                    'text' => $message
                ]
            ]
        ]);

        $request->setBody($body);

        try {
            $response = $request->send();
            if ($response->getStatus() == 200) {
                return $response->getBody();
            } else {
                throw new \Exception('Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase());
            }
        } catch(HTTP_Request2_Exception $e) {
            throw new \Exception('Error: ' . $e->getMessage());
        }
    }
}
