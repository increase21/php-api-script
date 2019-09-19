<?php

header('Content-Type:application/json');
class helper
{
    public static function Output_Error($code = null, $message = null)
    {
        if (isset($code)) {
            if (is_int($code)) {
                switch ($code) {
                    case 400: $message = !is_null($message) ? $message : 'Bad Request';
                    break;
                    case 401: $message = !is_null($message) ? $message : 'Unauthorized';
                    break;
                    case 404:  $message = !is_null($message) ? $message : 'Requested resource not found';
                    break;
                    case 405:  $message = !is_null($message) ? $message : 'Method Not Allowed';
                    break;
                    case 500:  $message = !is_null($message) ? $message : 'Whoops! somthing went wrong, our engineers have been notified';
                  break;
                    default:
                    isset($message) ? $message : '';
                }
                http_response_code($code);
            }
        }
        $response = [];
        if (!is_null($code)) {
            $response['code'] = $code;
        }
        $response['error'] = $message;
        echo json_encode($response);
    }

    public static function Output_Success($data)
    {
        echo json_encode($data);
    }
}