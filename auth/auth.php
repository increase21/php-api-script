<?php

include_once './helpers/db.php';

class auth
{
    private static $header;
    public static $authorization;

    public function __construct($header = null, $header_prop = null)
    {
        if (!is_null($header_prop)) {
            if (!array_key_exists($header_prop, $header)) {
                exit(helper::Output_Error(401, 'Auth header not set'));
            }
            self::$header = $header;
            self::$authorization = $header[$header_prop];
        } else {
            return [];
        }
    }

    // check Authorization header
    public static function Check_Auth($auth_regex = null)
    {
        //  check whether the auth header is empty
        if (empty(self::$authorization)) {
            exit(helper::Output_Error(401, 'Auth header not submitted'));
        }
        //   check whether the submitted auth match the regex
        if (!is_null($auth_regex) && !preg_match($auth_regex, self::$authorization)) {
            helper::Output_Error(401, 'Invalid authorization header');
            exit;
        }
        // if there is no connection, establish a connection
        if (!db::$conn) {
            db::Connection();
        }
        //Query statement prepared
        $sql = 'SELECT * FROM `cecula_apps` WHERE `api_key` = ?';
        $check_key = db::Prepare($sql, 's', substr(self::$authorization, 7));
        // check result set if it returns query error
        if (array_key_exists('error', $check_key)) {
            exit(helper::Output_Error(500));
        }
        // check if there is no record
        if (!count($check_key)) {
            exit(helper::Output_Error(401, 'Invalid authorization'));
        }

        return $check_key;
    }

    // check the IP connection
    public static function Check_IP(array $require_ip)
    {
        $request_ip = $_SERVER['REMOTE_ADDR']; // get the request IP
        //   check if the IP does not exist in the required IPs
        if (!in_array($request_ip, $require_ip)) {
            exit(helper::Output_Error(401));
        }
    }
}
