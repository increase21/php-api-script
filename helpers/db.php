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
    public static function Create_Connection($hostname = null, $username = null, $password = null, $database = null)
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

    // function to execute Raw Query statement
    public static function Db_Query($statemet, $type = null)
    {
        if ($ex = self::$conn->query($statemet)) {
            // check if the query type is present and it's not a select query
            if (isset($type) && $type !== 'select') {
                return $ex;
            }
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
