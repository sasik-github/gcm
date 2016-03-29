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
    public static function sendToAndroid($toToken, $data)
    {
        $preparedData = [];
        $preparedData['data'] = $data;

        return self::send($toToken, $preparedData);
    }

    public static function sendToIOS($toToken, $data)
    {
        $message = '';
        if (array_key_exists('message', $message)) {
            $message = $data['message'];
        }

        $preparedData = [
            'content_available' => true,
            'notification' => [
                'sound' => 'default',
                'badge' => '1',
                'title' => 'default',
                'body' => $message,
            ],
            'aps' => $data,
        ];

        return self::send($toToken, $preparedData);
    }

    private static function send($toToken, $preparedData)
    {

        $json = [
            'registration_ids' => [$toToken]
        ];

        $json = array_merge($json, $preparedData);

        $client = new Client([
            'base_uri' => 'https://gcm-http.googleapis.com/gcm/send',
        ]);
        $response = $client->post('', [
            'headers' => [
                'Authorization' => 'key=' . self::getAppKey(),
                'Content-Type' => 'application/json',
            ],
            'json' => $json,
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