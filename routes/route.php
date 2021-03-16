<?php
include_once 'routeInterface.php';

abstract class Route implements routeInterface
{
    public $router;
    public $views;
    public $controllers;

    public function __construct($router)
    {
        $this->router = $router ;
        $this->views = __DIR__ .'/../views/';
        $this->controllers = __DIR__ .'/../controllers/';

    }

    function controller($request,$controllerClass,$function,$middleware = null)
    {
        $controller = new $controllerClass();
        return $controller->{$function}($request,$middleware);
    }

    function view($path)
    {
        return file($this->views.$path);
    }
}
