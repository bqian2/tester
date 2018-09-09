<?php
use Slim\Http\Requestst_controller;
use Slim\Http\Response;

$local_path = dirname( __FILE__ ) . '/';
require_once( $local_path . '/../user_controller.php' );
require_once( $local_path . '/../mylist_controller.php' );
require_once( $local_path . '/../authenticate.php');


class handler {
    public function __construct($method, $cls = Null) {
       $this->cls = $cls;
       $this->method = $method;
    }

    public function process_request($request, $response, $args) {
       $method = $this->method;
       print $this->method;
       print $this->cls;
       if($this->cls != Null) {
           $cls = new $this->cls($request, $response);
           $authorized = True;
           if($cls->require_authentication() and !$cls->authenticate()) {
               $authorized = False;
           }
           if($authorized) { 
               $cls->$method($request, $response, $args);
           }else
           {
               print("access denied"); 
           }
       }else {
           $method($request, $response, $args);
       }
    }
}

// Routes
// Define routes
$routes = array(
    "/" => new handler("authenticate"),
    "/list" => new handler("get_mylists", "mylist_controller"),
    "/list/{id:[0-9]*}" => new handler("get_mylist", "mylist_controller"),
);

function show_desc()
{
    print "desc\n";
}

function get_my_lists($request, $response, $args)
{
    print "get_my_lists";
}

function get_my_list($request, $response, $args)
{
    print "get_my_list(". $args["id"] . ")";
}

foreach($routes as $path => $handler)
{
   $app->get($path, function($request, $response, $args) use ($app, $handler) {
       $handler->process_request($app, $request, $response, $args);
   });
}

/*
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/
