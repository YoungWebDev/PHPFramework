<?php

namespace app\Routing;


use app\Core\App;

class Router {

    private $requestProvider;

    protected $_request;

    protected $postRouteCollection = [];

    protected $getRouteCollection = [];

    public function __construct()
    {

        $this->requestProvider = new requestProvider();

        $this->_request = $this->requestProvider->cleanRequest();

    }

    public function run()
    {


        $controller = $this->search(strtolower($_SERVER['REQUEST_METHOD']), $this->_request);

        if ( $controller === false )
        {
            echo App::redirect(404);
        } else {
            echo $this->execute($controller);
        }


    }

    public function get($path, $controller)
    {
        $this->addRoute("get", $path, $controller);
    }

    public function post($path, $controller)
    {
        $this->addRoute("post", $path, $controller);
    }

    protected function search($method, $path)
    {

        $collection = $method."RouteCollection";

        foreach( $this->$collection as $array => $routes )
        {
            foreach( $routes as $route => $value ) {

                if ($route === $path)
                {
                    return $this->controller = $value;
                }

            }
        }

        return false;

    }

    protected function addRoute($method, $path, $controller)
    {

        $this->addToArray($method, $path, $controller);

    }

    protected function addToArray($method, $path, $controller)
    {

        $collection = $method."RouteCollection";

        $isInArray = in_array($path, $this->$collection);

        if ($isInArray == false)
        {
            array_push($this->$collection, array($path => $controller));
        }

    }

    protected function execute($controller)
    {

        $controller = explode("@", $controller);
        $class      = $controller[0];
        $method     = $controller[1];
        $caller     = "app\\Controller\\$class";

        $controller = new $caller;

        return $controller->$method($this->requestProvider->cleanQuery());
    }



}