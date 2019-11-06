<?php

include_once './helpers/db.php';

class auth
{
    private static $header;
    public static $auth_key;

    public function __construct($header = null, $require_auth = false)
    {
        //  Checking if the application requires Authentication
        if ($require_auth === false) {
            return [];
        }
        self::$header = $header;
    }

    // check Authorization header
    public static function Check_Auth($header_prop = null, $header_prop_regex = null)
    {
        if (!array_key_exists($header_prop, self::$header)) {
            exit(helper::Output_Error(401, 'Auth header not set'));
        }

        //  check whether the auth header is empty
        if (empty(self::$header[$header_prop])) {
            exit(helper::Output_Error(401, 'Auth header not submitted'));
        }
        //   check whether the submitted auth match the regex
        if (!is_null($header_prop_regex) && !preg_match($header_prop_regex, self::$header[$header_prop])) {
            exit(helper::Output_Error(401, 'Invalid auth header'));
        }
        self::$auth_key = self::$header[$header_prop];
        // if there is no connection, establish a connection
        if (!db::$conn) {
            db::Connection();
        }
        //Query statement prepared
        $sql = 'SELECT * FROM `cecula_apps` WHERE `api_key` = ?';
        $check_key = db::Prepare($sql, 's', substr(self::$auth_key, 7));
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
    public static function Check_IPAddress(array $require_ip)
    {
        $request_ip = $_SERVER['REMOTE_ADDR']; // get the request IP
        //   check if the IP does not exist in the required IPs
        if (!in_array($request_ip, $require_ip)) {
            exit(helper::Output_Error(401));
        }
    }
}
