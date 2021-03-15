<?php


namespace Config;


class Autoloader
{
    private $app_path =  __DIR__ . '/../';

    public function __construct()
    {
        spl_autoload_register('loadModel');
        spl_autoload_register('loadView');
    }

    function loadModel($class) {
        $path = $this->app_path . 'models/';
        require_once $path . $class .'.php';
    }

    function loadView($class) {
        $path = $this->app_path . 'views/';
        require_once $path . $class .'.php';
    }

}
