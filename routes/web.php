<?php

class Web
{
    public $router;
    public $views;
    public $controllers;

    public function __construct($router)
    {
        $this->router = $router ;
        $this->routes();
        $this->views = __DIR__ .'/../views/';
        $this->controllers = __DIR__ .'/../controllers/';
        return $this->router->resolve();
    }

    function routes()
    {
        $this->router->get('/', function () {
            $view = $this->views . 'login.php';
            include_once $this->views . 'login.php';
        });

        $this->router->get('/create', function () {
            $view = $this->views.'create_account.php';
            include_once $view;
        });

    }

    function controller($controllerClass,$function)
    {
        $controller = new $controllerClass();
        return $controller->{$function};
    }

}

