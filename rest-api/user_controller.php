<?php
$local_path = dirname( __FILE__ ) . '/';
require_once($local_path . "rest_controller.php");

class user_controller extends rest_controller{
    public function require_authentication() {
        return true;
    }

    public function handle_request() {
        $data = new stdClass();
        if(array_key_exists("data", $_REQUEST))
        {
            $data = json_decode($_REQUEST["data"]);
        }

        if(array_key_exists("x-auth-token", $data)) 
        {

        }elseif(array_key_exists("userid", $data) and 
                 array_key_exists("password", $data))
        {

        }
    }

}

