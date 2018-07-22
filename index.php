<?php
    $local_path = dirname( __FILE__ ) . '/';
    require_once( $local_path . 'user_controller.php' );
    require_once( $local_path . 'authentication_controller.php' );

    $tables = ["/rest-api/user" => "user_controller",
               "/rest-api/authentication" => "authentication_controller"];

    $parts = explode( '?', $_SERVER['REQUEST_URI'] );
    $path = $parts[0];
    if (array_key_exists($path, $tables)) {
        $cls = $tables[$path];
        $ctrl = new $cls();
        $ctrl->process();
    } else {
        http_response_code(404);
        # include('my_404.php');
        die();
    }


