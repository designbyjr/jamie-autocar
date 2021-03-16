<?php
class Web extends Route
{
    public function __construct($router)
    {
        parent::__construct($router);
        $this->publicRoutes();
        $this->privateRoutes();
        return $this->router->resolve();
    }

    function publicRoutes()
    {
        $this->router->get('/', $this->view('login.php'));

        $this->router->post('/login', function ($request) {
            return $this->controller($request,'DashboardController','login');
        });

        $this->router->post('/register', function ($request) {
            return $this->controller($request,'RegisterController','register');
        });

        $this->router->get('/create', $this->view('create_account.php'));

        $this->router->get('/forget-password',$this->view('forget_password.php'));


    }

    function privateRoutes()
    {
        //check if logged in
        //if not destroy session and log out and redirect to login page
        $middleware = new LoginMiddleware();
        $middleware->auth();
        if($middleware->isLoggedIn) {

            $this->router->get('/dashboard', function ($request) use ($middleware) {
                return $this->controller($request, 'DashboardController', 'index', $middleware);
            });

            $this->router->post('/add', function ($request) use ($middleware) {
                return $this->controller($request, 'DashboardController', 'addRecord', $middleware);
            });
            $this->router->post('/update', function ($request) use ($middleware) {
                return $this->controller($request, 'DashboardController', 'updateRecord', $middleware);
            });

            $this->router->post('/delete', function ($request) use ($middleware) {
                return $this->controller($request, 'DashboardController', 'deleteRecord', $middleware);
            });

        }
        $this->router->get('/logout', function ($request) use ($middleware) {
            return $this->controller($request, 'DashboardController', 'logout', $middleware);
        });

    }




}

