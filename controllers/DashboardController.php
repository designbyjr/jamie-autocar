<?php


class DashboardController
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
        $this->middleware = new Middleware();
        $this->views = __DIR__ .'/../views/';
    }


    public function index()
    {
        return $this->view()
    }

    public function login($request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $password_hash = password_hash ( $password);

        // check db
        $statement = "SELECT * FROM USERS WHERE email = ? and password =?";
        $q = $this->db->query($statement,[$email,$password_hash])->numRows();
        if($q == 0)
        {
            throw \Exception('User Not Found, Please check email and password');
        }
    }

    private function view($path,$params)
    {
        return file($this->views.$path);
    }

}
