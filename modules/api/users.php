<?php

include_once './helpers/validator.php';
class users
{
    private $request_method;
    private $data;

    public function __construct($req_mth, $input, $userData = null)
    {
        $this->request_method = $req_mth;
        $this->data = $input;
    }

    public function totp()
    {
        // check the request method
        if ($this->request_method !== 'POST') {
            exit(helper::Output_Error(405));
        }
        // check for empty fields
        if (count($missing_fields = validator::Check_Required_Fields(['user_vereafy_id', 'cdp_app_id', 'pin'], $this->data))) {
            exit(helper::Output_Error('CE7005', 'The following fileds are reqiured "'.implode(', ', $missing_fields)));
        }
    }
}