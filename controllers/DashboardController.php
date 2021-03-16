<?php


class DashboardController
{
    private $db;

    public function __construct()
    {
        $this->db = new DB();
        $this->middleware = new LoginMiddleware();
        $this->views = __DIR__ .'/../views/';
    }

    public function logout()
    {
        session_destroy();
        header("location: /");
    }


    public function index()
    {   $statement = "SELECT * FROM `members` left join `members_passwords` 
        ON `members`.`member_id` = `members_passwords`.`member_id`
        WHERE `members`.`member_id` = ?";
        $q = $this->db->query($statement,[$_SESSION["member_id"]])->fetchAll();

        return $this->view('dashboard.php',$q);
    }

    public function addRecord($request)
    {
        $array = $request->input();
        if(isset($array['website']) && isset($array['website_password'])) {
            $array['member_id'] = $_SESSION['member_id'];
            $this->middleware->auth->insert('members_passwords', $array);
        }
        else
        {
            global $errors;
            $errors = ["Website and Website Password was not filled in"];
        }
        header("location: /dashboard");
    }

    public function deleteRecord($request)
    {
        $array = $request->input();

        if(isset($array['id'])) {
            $statement = "DELETE FROM members_passwords WHERE id = ?";

            $this->db->query($statement,$array['id']);
           $this->db->close();
        }
        else
        {
            global $errors;
            $errors = ["Website and Website Password was not filled in"];
        }
        header("location: /dashboard");
    }

    public function updateRecord($request)
    {
        $array = $request->input();

        if(isset($array['id'])) {
            $statement = "UPDATE `members_passwords`
            SET
            `type` = ?,
            `website` = ?,
            `website_password` = ?
            WHERE `id` = ?;";
            $this->db->query($statement,[$array['type'],$array['website'],$array['website_password'],$array['id']]);
            $this->db->close();
        }
        else
        {
            global $errors;
            $errors = ["Website and Website Password was not filled in"];
        }
        header("location: /dashboard");
    }

    public function login($request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $this->middleware->validate($email,$password,'login');
        if($this->middleware->isLoggedIn || $this->middleware->isAuthenticated)
        {
            header("location: /dashboard");
        }

    }

    private function view($path,$params)
    {
        global $values;
        $values = $params;
        return include $this->views.$path;
    }

}
