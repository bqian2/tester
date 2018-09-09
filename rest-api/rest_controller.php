<?php
    interface rest_controller_i {
        public function process();
    }

    abstract class rest_controller implements rest_controller_i {
        protected $method;
        protected $data;
        protected $request;
        protected $response;
        protected $args;
        public abstract function require_authentication();

        function __construct($request, $response, $args) {
            $this->request = $request;
            $this->response = $response;
            $this->args = $args;
        }

        public function is_authorized() {
            if(!$this->require_authentication()) {
                return 200;
            }

            if(!$this->is_authenticated())
            {
                return 401;
            }

            if($this->can_access())
            {
                return 200;
            }
            return 403;
        }

        protected function can_access() {
            return true;
        }

        public function is_authenticated() {
            return true;
        }
        
        public function authenticate() {

        }

        public function process() {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $result = $this->is_authorized();
            if(200 != $result)
            {
                $this->access_denied($result);
            }else
            {
                if( 'POST' == $this->method ) {
                    $post_str=file_get_contents('php://input');
                    $this->post_data=json_decode($post_str);
                    // $this->queries = $this->queries + Array("_post_data"=>$this->post_data);
                }
                return $this->handle_request();
            }
            switch($this->method) {
                case "get":
                    break;
                case "post":
                    $this->data = new stdClass();
                    if(array_key_exists("data", $_REQUEST))
                    {
                        $data = json_decode($_REQUEST["data"]);
                    }
                    break;
                default:
                    break;
            }
        }

        public function access_denied() {
            if( $this->is_authenticated() ) {
                header('WWW-Authenticate', 'Basic realm=Authorization Required');
                header('HTTP/1.0 403 Forbidden');
            } else {
                header('WWW-Authenticate', 'Basic realm=Authorization Required');
                header("HTTP/1.1 401 Unauthorized");
            }
        }

        function response($data) {
            header('Content-Type: application/json');
            print(json_encode($data));
        }

        // public abstract function handle_request();

    }

