<?php
require_once "Middleware.php";

class LoginMiddleware extends Middleware
{
    public $isAuthenticated;

    public function __construct()
    {
        parent::__construct();
        $this->auth();
    }


    public function auth()
    {

        // Get Current date, time
        $current_time = time();
        $current_date = date("Y-m-d H:i:s", $current_time);

        // Set Cookie expiration for 1 month
        $this->cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month
        // Check if loggedin session and redirect if session exists
        if (!empty($_SESSION["member_id"])) {
            $this->isLoggedIn = true;
        } // Check if loggedin session exists
        else if (!empty($_COOKIE["member_login"]) && !empty($_COOKIE["random_password"]) && !empty($_COOKIE["random_selector"])) {
            // Initiate auth token verification directive to false
            $isPasswordVerified = false;
            $isSelectorVerified = false;
            $isExpiryDateVerified = false;

            // Get token for username
            $userToken = $this->auth->getTokenByMemberId($_COOKIE["member_login"], 0);

            // Validate random password cookie with database
            if (password_verify($_COOKIE["random_password"], $userToken[0]["password_hash"])) {
                $isPasswordVerified = true;
            }

            // Validate random selector cookie with database
            if (password_verify($_COOKIE["random_selector"], $userToken[0]["selector_hash"])) {
                $isSelectorVerified = true;
            }

            // check cookie expiration by date
            if ($userToken[0]["expiry_date"] >= $current_date) {
                $isExpiryDareVerified = true;
            }

            // Redirect if all cookie based validation returns true
            // Else, mark the token as expired and clear cookies
            if (!empty($userToken[0]["id"]) && $isPasswordVerified && $isSelectorVerified && $isExpiryDareVerified) {
                $this->isLoggedIn = true;
            } else {
                if (!empty($userToken[0]["id"])) {
                    $this->auth->markAsExpired($userToken[0]["id"]);
                }
                // clear cookies
                $this->util->clearAuthCookie();
            }
        }
    }



}
