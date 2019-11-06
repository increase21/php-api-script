<?php

include_once './helpers/validator.php';
class users
{
    public static function totp($req_mth, $input, $user_data)
    {
        // check the request method
        if ($req_mth !== 'POST') {
            exit(helper::Output_Error(405));
        }
        // check for empty fields
        if (count($missing_fields = validator::Check_Required_Fields(['user_vereafy_id', 'cdp_app_id', 'pin'], $input))) {
            exit(helper::Output_Error('CE7005', 'The following fileds are reqiured "'.implode(', ', $missing_fields)));
        }
    }
}
