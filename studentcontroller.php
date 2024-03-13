<?php
class StudentController extends Connection
{
    public function __construct()
    {
        parent::__construct();
    }
    public function update_student_password($userid, $password, $newpassword, $message)
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