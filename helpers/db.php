<?php

require_once './helpers/config.php';
class db
{
    public static $host;
    public static $user;
    public static $pass;
    public static $db_name;
    public static $conn;

    // function to establis a database connection
    public static function Connection($hostname = null, $username = null, $password = null, $database = null)
    {
        self::$host = is_null($hostname) ? DB_HOST : $hostname;
        self::$user = is_null($username) ? DB_USER : $username;
        self::$pass = is_null($password) ? DB_PASS : $password;
        self::$db_name = is_null($database) ? DB_NAME : $database;
        self::$conn = new mysqli(self::$host, self::$user, self::$pass, self::$db_name);
        // check if there is an error in the connection
        if (mysqli_connect_errno()) {
            exit(mysqli_connect_error());
        }
    }

    // function to execute a preapre query statement
    public static function Prepare($prepared_statemet, $param_type, ...$param_values)
    {
        if ($query = self::$conn->prepare($prepared_statemet)) {
            $query->bind_param($param_type, ...$param_values);
            $query->execute();
            $result = $query->get_result();
            // check if there is no record
            if ($query->affected_rows < 1) {
                return [];
            }
            // get all the results set
            while ($row = $result->fetch_object()) {
                $arr[] = $row;
            }

            return $arr;
        } else {
            // return error from the query
            return ['error' => mysqli_error(self::$conn)];
        }
    }

    // function to execute Raw Query statement
    public static function Query($statemet)
    {
        if ($ex = self::$conn->query($statemet)) {
            // check if there is no roll fetched;
            if ($ex->num_rows === 0) {
                return [];
            }
            // get result
            while ($row = $ex->fetch_object()) {
                $arr[] = $row;
            }

            return $arr;
        } else {
            // return the error
            return ['error' => mysqli_error(self::$conn)];
        }
    }
}
