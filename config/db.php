<?php
class DB {

    protected $connection;
    protected $query;
    protected $show_errors = TRUE;
    protected $query_closed = TRUE;
    public $query_count = 0;

    public function __construct() {
        $this->preFlightCheck();
        $dbhost = getenv ('DATABASE_HOST') ?? 'localhost';
        $dbuser = getenv ('DATABASE_USER') ?? 'root';
        $dbpass = getenv ('DATABASE_PASSWORD') ?? '';
        $dbname = getenv ('DATABASE_SCHEMA') ?? null;


        $charset = 'utf8';

            $this->connection = new mysqli($dbhost, $dbuser, $dbpass,$dbname);
            if ($this->connection->connect_error) {
                $this->error('Failed to connect to MySQL - ' . $this->connection->connect_error);
            }
            $this->connection->set_charset($charset);


    }


    private function preFlightCheck()
    {
        $migration = getenv('DB_MIGRATION');
        if(!$migration)
        {
            $this->migrate();
            putenv('DB_MIGRATION=true');
        }

    }

    public function migrate()
    {
        $dbhost = getenv ('DATABASE_HOST') ?? 'localhost';
        $dbuser = getenv ('DATABASE_USER') ?? 'root';
        $dbpass = getenv ('DATABASE_PASSWORD') ?? '';


        $charset = 'utf8';
        $connection = new mysqli($dbhost, $dbuser, $dbpass);

        $connection->set_charset($charset);
        $connection->query('CREATE DATABASE IF NOT EXISTS `loggymclogface`');
        $connection->select_db('loggymclogface');
        $connection->query("CREATE TABLE IF NOT EXISTS `members` (
            `member_id` int(8) NOT NULL AUTO_INCREMENT,
            `member_name` varchar(255) CHARACTER SET utf8 NOT NULL,
            `member_password` varchar(255) NOT NULL,
            `member_email` varchar(255) CHARACTER SET utf8 NOT NULL UNIQUE,
            PRIMARY KEY (`member_id`)
            ) ;");

        $connection->query("
        CREATE TABLE IF NOT EXISTS `tbl_token_auth` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `member_id` int(11) NOT NULL,
            `password_hash` varchar(255) NOT NULL,
            `selector_hash` varchar(255) NOT NULL,
            `is_expired` int(11) NOT NULL DEFAULT '0',
            `expiry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            );");
        $connection->query("
        CREATE TABLE IF NOT EXISTS `members_passwords` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `member_id` int(11) NOT NULL,
            `type` varchar(255) NOT NULL,
            `website` varchar(255) NOT NULL,
            `website_password` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
            ) ;");
        $connection->close();
    }

    public function query($query) {
        if (!$this->query_closed) {
            $this->query->close();
        }
        if ($this->query = $this->connection->prepare($query)) {
            if (func_num_args() > 1) {
                $x = func_get_args();
                $args = array_slice($x, 1);
                $types = '';
                $args_ref = array();
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types .= $this->_gettype($args[$k][$j]);
                            $args_ref[] = &$a;
                        }
                    } else {
                        $types .= $this->_gettype($args[$k]);
                        $args_ref[] = &$arg;
                    }
                }
                array_unshift($args_ref, $types);
                call_user_func_array(array($this->query, 'bind_param'), $args_ref);
            }
            $this->query->execute();
            if ($this->query->errno) {
                $this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
            }
            $this->query_closed = FALSE;
            $this->query_count++;
        } else {
            $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
        }
        return $this;
    }



    public function fetchAll($callback = null) {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    public function fetchArray() {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            foreach ($row as $key => $val) {
                $result[$key] = $val;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
        return $result;
    }

    public function close() {
        return $this->connection->close();
    }

    public function numRows() {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    public function affectedRows() {
        return $this->query->affected_rows;
    }

    public function lastInsertID() {
        return $this->connection->insert_id;
    }

    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }

    private function _gettype($var) {
        if (is_string($var)) return 's';
        if (is_float($var)) return 'd';
        if (is_int($var)) return 'i';
        return 'b';
    }

}
?>
