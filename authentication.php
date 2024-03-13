<?php
include 'database.php';
class Authentication extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register($username, $password, $role, $message)
    {
        $sql = "INSERT INTO users_table (username, password, roleid) VALUES ('{$username}', '{$password}','{$role}')";

        $exe = $this->connection->query($sql);

        if ($exe) {
            $message->success = true;
            $message->message = "user registered successfully!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }

    public function login($username, $password, $message)
    {
        $sql = "SELECT * FROM users_table WHERE username = '{$username}' AND password = '{$password}' AND status = 1";

        $exe = $this->connection->query($sql);

        if ($exe) {
            $user = $exe->fetch_assoc();

            if ($user["status"] == 1) {
                $updatesql = "UPDATE users_table SET is_logged_in = 1 WHERE userid = '{$user["userid"]}'";
                $exeupdate = $this->connection->query($updatesql);

                $_SESSION["userid"] = $user["userid"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["roleid"] = $user["roleid"];

                $message->success = true;
                $message->message = "Hello {$user["username"]}, you logged in successfully!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            } else if ($user["status"] == 0) {
                http_response_code(400);
                $message->success = false;
                $message->message = "this account have been disabled!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            } else {
                http_response_code(400);
                $message->success = false;
                $message->message = "error desu wa!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            }
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
    public function logout($message)
    {
        $sql = "UPDATE users_table SET is_logged_in = 0 WHERE userid = '{$_SESSION["userid"]}'";

        $exe = $this->connection->query($sql);

        if ($exe == 1) {
            session_destroy();
            $message->success = true;
            $message->message = "logout successfully!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
}
?>