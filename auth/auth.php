<?php

include_once './helpers/db.php';

class auth
{
    public $authorization;
    public $result;

    public function __construct($header, $required_auth = false)
    {
        if ($required_auth === true) {
            if (!array_key_exists('Authorization', $header)) {
                helper::Output_Error(401, 'Authorization header not set');
                exit;
            }
            $this->authorization = $header['Authorization'];

            return $this->check_auth();
        } else {
            return [];
        }
    }

    private function check_auth()
    {
        if (!preg_match('/^Bearer\s[\w\.\-]{44}$/', $this->authorization)) {
            helper::Output_Error(401, 'Invalid authorization header');
            exit;
        }
        // if there is no connection, establish a connection
        if(!db::$conn){
            db::Connection();
        }
        //Query statement prepared
        $sql = 'SELECT * FROM `cecula_apps` WHERE `api_key` = ?';
        $check_key = db::Prepare($sql, 's', substr($this->authorization, 7));
        // check result set if it returns query error
            if (array_key_exists('error', $check_key)) {
                exit(helper::Output_Error(500));
            }
            // check if there is no record
            if (!count($check_key)) {
                exit(helper::Output_Error(401, 'Invalid authorization'));
            }

            return $this->result = $check_key;
        }
    }