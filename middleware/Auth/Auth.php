<?php

require_once __DIR__.'/../../config/db.php';

class Auth {

    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    /*
   * @var get user by email.
   *
   */
    function getUserByEmail($email) {

    $query = "Select * from members where member_email = ?";
    $result = $this->db->query($query, [$email])->fetchAll();
    $this->db->close();
    return $result;
    }


    /*
     * @var mark token related to cookie as expired.
     *
     */
    function getTokenByMemberId($memberId,$expired) {
    
    $query = "Select * from tbl_token_auth where member_id = ? and is_expired = ?";
    $result = $this->db->query($query, array($memberId, $expired));
    var_dump($result);
    die();
        $this->db->close();
    return $result;
    }

    /*
     * @var mark token related to cookie as expired.
     */
    function markAsExpired($tokenId) {

    $query = "UPDATE tbl_token_auth SET is_expired = ? WHERE id = ?";
    $expired = 1;
    $result = $this->db->query($query,array($expired, $tokenId));
        $this->db->close();
    return $result;
    }

    /*
     * @var Insert token related to cookie.
     */
    function insertToken($member_id, $random_password_hash, $random_selector_hash, $expiry_date) {

    $query = "INSERT INTO tbl_token_auth (member_id, password_hash, selector_hash, expiry_date) values (?, ?, ?,?)";
    $result = $this->db->query($query, array($member_id, $random_password_hash, $random_selector_hash, $expiry_date));
    $this->db->close();
    return $result;
    }

    /*
     * @var Insert into the database.
     */
    public function insert(string $table,array $params) {

        $end = array_keys($params);
        $last_key = end($end);
        $query = "INSERT INTO ".$table.' (';
        $values = ' values (';
        foreach($params as $key => $param)
        {
            if ($key == $last_key) {
                $query .= $key.')';
                $values .= '"'.$param.'"'.')';
                $query .= $values;
            }
            else {
                $query .= $key . ',';
                $values .= '"'.$param.'"' . ',';
            }
        }
        var_dump($query);
        $result = $this->db->query($query);
        $this->db->close();
        return $result;
    }




}
?>
