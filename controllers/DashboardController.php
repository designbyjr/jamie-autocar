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

    public function login($request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $password_hash = password_hash ( $password);

        // check db
        $statement = "SELECT * FROM USERS WHERE email = ? and password = ?";
        $q = $this->db->query($statement,[$email,$password_hash])->numRows();
        if($q == 0)
        {
            throw \Exception('User Not Found, Please check email and password');
        }
        $this->db->close();
    }

    private function view($path,$params)
    {
        global $values;
        $values = $params;
        return include $this->views.$path;
    }

}
