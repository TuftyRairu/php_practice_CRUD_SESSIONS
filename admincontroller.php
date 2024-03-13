<?php
class AdminController extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create_user($username, $password, $roleid, $status, $message)
    {
        $sql = "INSERT INTO users_table (username, password, roleid, status) VALUES ('{$username}', '{$password}','{$roleid}', '{$status}')";

        $exe = $this->connection->query($sql);

        if ($exe == 1) {
            $message->success = true;
            $message->message = "user created successfully!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
    public function get_users($message)
    {
        $sql = "SELECT * FROM users_table WHERE roleid != 1";

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
    public function get_user($key, $value, $message)
    {
        if (is_string($value)) {
            $sql = "SELECT * FROM users_table WHERE {$key} = '{$value}'";
        } else {
            $sql = "SELECT * FROM users_table WHERE {$key} = {$value}";
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
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }

    public function delete_user($key, $value, $message)
    {
        if (is_string($value)) {
            $sql = "DELETE FROM users_table WHERE {$key} = '{$value}'";
        } else {
            $sql = "DELETE FROM users_table WHERE {$key} = {$value}";
        }

        $exe = $this->connection->query($sql);

        if ($exe == 1) {
            $message->success = true;
            $message->message = "user deleted successfully!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
    public function create_role($roleid, $role, $description, $message)
    {
        $sql = "INSERT INTO roles_table (roleid, role, description) VALUES ('{$roleid}', '{$role}', '{$description}')";

        $exe = $this->connection->query($sql);

        if ($exe == 1) {
            $message->success = true;
            $message->message = "role created successfully!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
    public function delete_role($key, $value, $message)
    {
        if (is_string($value)) {
            $sql = "DELETE FROM roles_table WHERE {$key} = '{$value}'";
        } else {
            $sql = "DELETE FROM roles_table WHERE {$key} = {$value}";
        }

        $exe = $this->connection->query($sql);

        if ($exe == 1) {
            $message->success = true;
            $message->message = "role deleted successfully!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        } else {
            http_response_code(400);
            $message->success = false;
            $message->message = "error desu wa!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }

    public function update_user_role($userid, $roleid, $message)
    {
        $checkexistsql = "SELECT * FROM users_table WHERE userid = {$userid}";
        $checkexe = $this->connection->query($checkexistsql);
        $exist = $checkexe->fetch_assoc();

        if ($exist["roleid"] != 1) {
            $sql = "UPDATE users_table SET roleid = {$roleid} WHERE userid = '{$userid}'";

            $exe = $this->connection->query($sql);

            if ($exe == 1) {
                $message->success = true;
                $message->message = "role changed successfully!";
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
            $message->message = "user id used is not valid to change role!";
            echo json_encode($message, JSON_PRETTY_PRINT);
        }
    }
}
?>