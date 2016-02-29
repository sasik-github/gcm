<?php

namespace Sasik\GCM;

use GuzzleHttp\Client;

/**
 * User: sasik
 * Date: 1/12/16
 * Time: 2:09 PM
 */
class CloudMessaging
{

    /**
     * @param $toToken
     * @param $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    public static function send($toToken, $data)
    {
        $client = new Client([
            'base_uri' => 'https://gcm-http.googleapis.com/gcm/send',
        ]);
        $response = $client->post('', [
            'headers' => [
                'Authorization' => 'key=' . self::getAppKey(),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'registration_ids' => [$toToken],
                'data' => $data,
            ],
            'http_errors' => false,
        ]);
        return $response;
    }

    /**
     * get GSM APP KEY for push notifications
     *
     * @return string
     */
    private static function getAppKey()
    {
        return env('CLOUD_MESSAGE_KEY');
    }
}