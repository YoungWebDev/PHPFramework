<?php

namespace app\Core;


use app\Model\Mail;
use app\Routing\Router;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;

class Run {

    public function __construct()
    {

        $this->initWhoops();

        //error_reporting(0);

        $route = new Router();

        require_once __DIR__."/../routes.php";

        $route->run();

    }

    private function initWhoops()
    {
        $run        = new \Whoops\Run;
        $handler    = new PrettyPageHandler;

        $run->pushHandler($handler);
        $run->pushHandler(new JsonResponseHandler);

        $run->register();
    }

}