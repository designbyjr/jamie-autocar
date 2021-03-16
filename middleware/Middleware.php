<?php
require_once "Auth/Auth.php";
require_once "Auth/Util.php";

class Middleware
{
    public $util;
    public $isLoggedIn;
    public $auth;
    public $cookie_expiration_time;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->util = new Util();
        $this->isLoggedIn = false;
    }

    public function validate($email,$password,$route)
    {
        if ($this->isLoggedIn) {
            $this->util->redirect("/dashboard");
        }

        $this->isAuthenticated = false;

        $username = $email;

        $user = $this->auth->getUserByEmail($username);
        var_dump($user);
        if (password_verify($password, $user[0]["member_password"])) {
            $this->isAuthenticated = true;
        }


        if ($this->isAuthenticated) {
            $_SESSION["member_id"] = $user[0]["member_id"];

            // Set Auth Cookies if 'Remember Me' checked
            if (!empty($_POST["remember"])) {
                setcookie("member_login", $_SESSION["member_id"], $this->cookie_expiration_time);

                $random_password = $this->util->getToken(16);
                setcookie("random_password", $random_password, $this->cookie_expiration_time);

                $random_selector = $this->util->getToken(32);
                setcookie("random_selector", $random_selector, $this->cookie_expiration_time);

                $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
                $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);

                $expiry_date = date("Y-m-d H:i:s", $this->cookie_expiration_time);

                // mark existing token as expired
                $userToken = $this->auth->getTokenByMemberId( $_SESSION["member_id"], 0);
                if (!empty($userToken[0]["id"])) {
                    $this->auth->markAsExpired($userToken[0]["id"]);
                }
                // Insert new token
                $this->auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
            } else {
                $this->util->clearAuthCookie();
            }
            if($route == 'login' || $route == 'register')
            {
                return $this->util->redirect("/dashboard");
            }

        }
    }




}
