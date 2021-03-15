<?php

class Autoloader
{
    private $app_path =  __DIR__ . '/../';

    public function __construct(){}

    function loadModel() {
        $path = $this->app_path . 'models/';
        require_once $path .'.php';
    }


    public function loadView() {
        $path = $this->app_path . 'views/';
        $files = scandir($path);
        foreach ($files as $file)
        {
            if(strpos($file,'.') !== 0)
            {
                $views[] = require_once $path .$file;
            }

        }
        return $views;
    }


    public function loadRoutes($router)
    {
        $path = __DIR__ . '/../routes/';
        $files = scandir($path);
        foreach ($files as $file)
        {
            if(strpos($file,'.') !== 0)
            {
                require $path .$file;

            }

        }
    }

    public function loadControllers()
    {
        $path = __DIR__ . '/../controllers/';
        $files = scandir($path);
        foreach ($files as $file)
        {
            if(strpos($file,'.') !== 0)
            {
                require_once $path .$file;

            }

        }
    }

    public function loader($router)
    {
        $this->loadRoutes($router);
       // $this->loadControllers();
    }

}
