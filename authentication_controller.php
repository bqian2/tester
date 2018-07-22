<?php
$local_path = dirname( __FILE__ ) . '/';
require_once($local_path . "rest_controller.php");

class authentication_controller extends rest_controller{
    public function require_authentication() {
        return false;
    }

    public function handle_request() {
        $uid = $_REQUEST["uid"];
        $pwd = $_REQUEST["pwd"];
        var_dump(array("uid"=>$uid, "pwd"=>$pwd));
    }

}

