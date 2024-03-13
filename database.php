<?php
class Connection
{
    public $dbhost;
    public $dbuser;
    public $dbpass;
    public $dbname;
    public $connection;
    public function __construct()
    {
        $this->OpenConn();
    }
    public function OpenConn()
    {
        $this->dbhost = 'localhost';
        $this->dbuser = 'root';
        $this->dbpass = 'password';
        $this->dbname = 'test_php';

        if (!isset($this->connection)) {
            $this->connection = new mysqli($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);

            if (!$this->connection) {
                echo "database connection error!";

                exit;
            }
        }

        return $this->connection;
    }
}
?>