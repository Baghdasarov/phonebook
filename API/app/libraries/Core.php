<?php
/* 
* App Core Class  / Creates URL / Load controller
* URL format - /controller/method/params 
*/

class Core {

    protected $currentController = '';
    protected $currentMethod = '';
    protected $params = [];

    public function __construct() {
        if($_SERVER['REQUEST_METHOD']=='OPTIONS' ){
            ob_clean();
            header("Access-Control-Allow-Origin: *");
            header("Content-type: application/json; charset=utf-8");
            header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
            header("Access-Control-Request-Method: POST");
            return true;
        }
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Max-Age: 3600");
        header('Access-Control-Allow-Origin: *');
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header('Content-Type: application/json');
        $url = $this->getUrl();
        if (isset($url[0]) && !empty($url[0])){
            if (file_exists('app/controllers/' . ucwords($url[0]) . '.php')) {
                $this->currentController = ucwords($url[0]);
                unset($url[0]);
            }
            require_once('app/controllers/' . $this->currentController . '.php');
            $this->currentController = new $this->currentController;
            if (isset($url[1])) {
                if (method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                }
            }
            $this->params = $url ? array_values($url) : [] ;
            call_user_func_array([$this->currentController, $this->currentMethod ], $this->params);
        }
    }

    public function getUrl() {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = trim($_SERVER['REQUEST_URI'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return false;
    }
  
}
  