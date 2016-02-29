<?php
/**
 * User: sasik
 * Date: 1/28/16
 * Time: 11:01 PM
 */

namespace Sasik\GSM;


use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseCode
 *
 * здесь происходить перевод ответа от Google Cloud Messaging в Константу
 *
 * @package Sasik\Models
 */
class ResponseCode
{
    const MISSING_REGISTRATION = 1;

    /**
     * Check the format of the registration token you pass to the server.
     * Make sure it matches the registration token the client app receives from registering with GCM.
     * Do not truncate or add additional characters.
     */
    const INVALID_REGISTRATION = 2;

    /**
     * An existing registration token may cease to be valid in a number of scenarios,
     * including:
     *   - If the client app unregisters with GCM.
     *   - If the client app is automatically unregistered,
     *          which can happen if the user uninstalls the application.
     *          For example, on iOS, if the APNS Feedback Service reported the APNS token as invalid.
     *   - If the registration token expires (for example,
     *          Google might decide to refresh registration tokens,
     *          or the APNS token has expired for iOS devices).
     *   - If the client app is updated but the new version is not configured to receive messages.
     * For all these cases, remove this registration token from the app server and stop using it to send messages.
     */
    const NOT_REGISTERED = 3;

    /**
     * 401
     */
    const AUTHENTICATION_ERROR = 5;

    const PARENT_NOT_HAVE_TOKEN = 6;

    const CHILDREN_NOT_FOUND = 7;

    const UNKNOWN_ERROR = 666;

    const UNKNOWN_RESPONSE = 777;

    const OK = 999;

    public static function fromResponse(ResponseInterface $response)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode === 200) {
            return self::handler200($response);
        }
        if ($statusCode === 401) {
            return self::AUTHENTICATION_ERROR;
        }
        return self::UNKNOWN_RESPONSE;
    }

    private static function handler200(ResponseInterface $response)
    {
        $body = json_decode($response->getBody()->getContents(), true);
        if (array_key_exists('error', $body['results'][0])) {
            $err = $body['results'][0]['error'];
            return self::errorHandler($err);
        }
        return self::OK;
    }

    private static function errorHandler($errMsg)
    {
        switch ($errMsg) {
            case 'InvalidRegistration':
                return self::INVALID_REGISTRATION;
            case 'NotRegistered':
                return self::NOT_REGISTERED;
            default:
                return self::UNKNOWN_ERROR;
        }
    }

    public static function getMessageFromCode($code)
    {
        switch ($code) {
            case self::NOT_REGISTERED:
                return 'NotRegistered';
            case self::AUTHENTICATION_ERROR:
                return 'AuthenticationError';
            case self::INVALID_REGISTRATION:
                return 'InvalidRegistration';
            case self::UNKNOWN_ERROR:
                return 'UNKNOWN_ERROR';
            case self::UNKNOWN_RESPONSE:
                return 'UNKNOWN_RESPONSE';
            case self::PARENT_NOT_HAVE_TOKEN:
                return 'PARENT_NOT_HAVE_TOKEN';
            case self::CHILDREN_NOT_FOUND:
                return 'CHILDREN_NOT_FOUND';
            case self::OK:
                return 'OK';
            default:
                return 'Something went wrong with code = ' . $code;
        }


    }
}