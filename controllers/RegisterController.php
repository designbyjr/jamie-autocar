<?php
require_once '../middleware/LoginMiddleware.php';

class RegisterController
{
    private $middleware;
    private $auth;

    public function __construct()
    {
        $this->middleware = new LoginMiddleware();
        $this->auth = new Auth();
    }

    public function register($request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $confirm_password = $request->input('confirm_password');
        $name = $request->input('name');

        if($password !== $confirm_password)
        {
            throw \Exception('Passwords do not match');
        }
        $user = $this->auth->getUserByEmail($email);
        if(empty($user) && !$this->middleware->isLoggedIn) {
            $phash = password_hash($password, PASSWORD_DEFAULT);
            $data = ['member_name' => $name,
                'member_password' => $phash,
                'member_email' => $email,
            ];
            $this->auth->insert('members', $data);
        }

        //validate user
        $this->middleware->validate($email,$password,'register');


    }

}
