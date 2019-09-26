<?php

class router
{
    private $data;
    private $method;
    private $userData;
    private $endpoint;
    private $file_path;

    public function __construct($urlPath, $method, $input = null, $authData = null)
    {
        $path = preg_replace('/^\/+|\/+$/', '', $urlPath); //sanitize the request URL
        $this->endpoint = explode('/', $path); // split the url
      //   check if the url are not complete
        if (count($this->endpoint) < 3) {
            exit(helper::Output_Error(404, 'endpoint not specified. e.g api/load/data'));
        }
        if (count($this->endpoint) > 3) {
            exit(helper::Output_Error(404));
        }
        //   check if the url file exist
        $this->file_path = './modules/'.strval($this->endpoint[0]).'/'.strval($this->endpoint[1]).'.php';
        if (!file_exists($this->file_path)) {
            exit(helper::Output_Error(404));
        }
        //   check if there is no payload submitted
        if (is_null($input) || !is_object($input)) {
            exit(helper::Output_Error('CE2001', 'JSON data required'));
        }
        $this->data = $input;
        $this->method = $method;
        $this->userData = $authData;
    }

    public function run()
    {
        include_once $this->file_path;
        $ex = new $this->endpoint[1]($this->method, $this->data, $this->userData);
        if (is_callable([$ex, $this->endpoint[2]])) {
            $str_action = strval($this->endpoint[2]);
            $ex->$str_action();
        } else {
            exit(helper::Output_Error(404));
        }
    }
}
