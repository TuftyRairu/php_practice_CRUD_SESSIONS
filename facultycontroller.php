<?php
class FacultyController extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }
    public function create_student($username, $password, $status, $message)
    {
        $sql = "INSERT INTO users_table (username, password, roleid, status) VALUES ('{$username}', '{$password}', 3, {$status})";

        $exe = $this->connection->query($sql);

        if ($exe == 1) {
            $message->success = true;
            $message->message = "student added successfully";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
    public function get_students($message)
    {
        $sql = "SELECT * FROM users_table WHERE roleid = 3";

        $exe = $this->connection->query($sql);

        if (!empty($exe) && $exe->num_rows != 0) {
            $data = $exe->fetch_all();
            $a = array();
            $n = 0;
            foreach ($data as $row => $value) {
                $num = count($value);
                $users = new stdClass();
                for ($i = 0; $i < $num; $i++) {
                    if ($i == 0) {
                        $users->userid = $value[$i];
                    } else if ($i == 1) {
                        $users->username = $value[$i];
                    } else if ($i == 2) {
                        $users->password = $value[$i];
                    } else if ($i == 3) {
                        $users->roleid = $value[$i];
                    } else if ($i == 4) {
                        $users->status = $value[$i];
                    } else {
                        $users->is_logged_in = $value[$i];
                    }
                }
                $a[$n] = $users;
                $n++;
            }

            $message->success = true;
            $message->message = "user ghetto dazze!";
            $message->users = $a;
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else if ($exe->num_rows == 0) {
            http_response_code(400);
            $message->success = false;
            $message->message = "there's no data in the database yet desu!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }

    public function get_student($key, $value, $message)
    {
        if (is_string($value)) {
            $sql = "SELECT * FROM users_table WHERE {$key} = '{$value}' AND roleid = 3";
        } else {
            $sql = "SELECT * FROM users_table WHERE {$key} = {$value} AND roleid = 3";
        }

        $exe = $this->connection->query($sql);

        if ($exe->num_rows == 1) {
            $data = $exe->fetch_all();
            $a = array();
            $n = 0;
            foreach ($data as $row => $value) {
                $num = count($value);
                $users = new stdClass();
                for ($i = 0; $i < $num; $i++) {
                    if ($i == 0) {
                        $users->userid = $value[$i];
                    } else if ($i == 1) {
                        $users->username = $value[$i];
                    } else if ($i == 2) {
                        $users->password = $value[$i];
                    } else if ($i == 3) {
                        $users->roleid = $value[$i];
                    } else if ($i == 4) {
                        $users->status = $value[$i];
                    } else {
                        $users->is_logged_in = $value[$i];
                    }
                }
                $a[$n] = $users;
                $n++;
            }

            $message->success = true;
            $message->message = "user ghetto dazze!";
            $message->users = $a;
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else if ($exe->num_rows == 0) {
            http_response_code(400);
            $message->success = false;
            $message->message = "student does not exist!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
    public function update_faculty_password($userid, $password, $newpassword, $message)
    {
        $checkexistsql = "SELECT * FROM users_table WHERE userid = {$userid}";
        $checkexe = $this->connection->query($checkexistsql);
        $exist = $checkexe->fetch_assoc();

        if ($exist["password"] == $password) {
            $sql = "UPDATE users_table SET password = '{$newpassword}' WHERE userid = '{$userid}'";

            $exe = $this->connection->query($sql);

            if ($exe == 1) {
                $message->success = true;
                $message->message = "password changed successfully!";
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
            $message->message = "wrong old password!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
}
?>