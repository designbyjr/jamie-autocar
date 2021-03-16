<?php
include 'Request.php';
include 'Router.php';
require_once '../env.php';

class Autoloader
{
    public function __construct(){
        session_start();
    }

    public function loadRoutes($router)
    {
        $path = __DIR__ . '/../routes/';
        $files = scandir($path);
        foreach ($files as $file)
        {
            if(strpos($file,'.') !== 0)
            {
                require_once $path .$file;

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

    public function loadMiddleware()
    {
        $path = __DIR__ . '/../middleware/';
        $files = scandir($path);
        foreach ($files as $file)
        {
            if(strpos($file,'.') !== 0 && strpos($file,'.php') !== false)
            {
                require_once $path .$file;

            }

        }
        $path = __DIR__ . '/../middleware/Auth/';
        $files = scandir($path);
        foreach ($files as $file)
        {
            if(strpos($file,'.') !== 0 && strpos($file,'.php') !== false)
            {
                require_once $path .$file;

            }

        }
    }


    public function loader()
    {
        $this->loadMiddleware();
        $router = new Router(new Request);
        $this->loadRoutes($router);

        $this->loadControllers();
        return $router;
    }

}
