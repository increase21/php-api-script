<?php

require_once './helpers/db.php';
if (!db::$conn) {
    db::Connection();
}

class user_function extends db
{
    
}