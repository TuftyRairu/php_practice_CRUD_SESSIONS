<?php
require_once 'authentication.php';
require_once 'admincontroller.php';
require_once 'facultycontroller.php';
class Views
{
    public function __construct()
    {
        session_start();

        $auth = new Authentication();
        $admin = new AdminController();
        $faculty = new FacultyController();

        $message = new stdClass();

        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/register") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                $auth->register($json["username"], $json["password"], $json["role"], $message);
            } else {
                $message->success = false;
                $message->message = "error!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/login") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                $auth->login($json["username"], $json["password"], $message);
            } else {
                $message->success = false;
                $message->message = "error!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/createUser") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                $status = 1;

                if ($_SESSION["roleid"] != 1 && $_SESSION["roleid"] != 2) {
                    http_response_code(401);
                    $message->success = false;
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else if ($_SESSION["roleid"] == 1) {
                    $admin->create_user($json["username"], $json["password"], $json["roleid"], $status, $message);
                } else if ($_SESSION["roleid"] == 2) {
                    $faculty->create_student($json["username"], $json["password"], $status, $message);
                } else {
                    http_response_code(500);
                    $message->success = false;
                    $message->message = "error desu wa!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                }
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/createRole") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = false;
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $admin->create_role($json["roleid"], $json["role"], $json["description"], $message);
                }
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/logout") {
            if ($_SESSION["roleid"] != 1 && $_SESSION["roleid"] != 2 && $_SESSION["roleid"] != 3) {
                http_response_code(400);
                $message->success = false;
                $message->message = "you haven't login yet!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            } else {
                $auth->logout($message);
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "GET" && $_SERVER["REQUEST_URI"] == "/getUsers") {
            if ($_SESSION["roleid"] != 1 && $_SESSION["roleid"] != 2) {
                http_response_code(401);
                $message->success = false;
                $message->message = "invalid access!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            } else if ($_SESSION["roleid"] == 1) {
                $admin->get_users($message);
            } else if ($_SESSION["roleid"] == 2) {
                $faculty->get_students($message);
            } else {
                http_response_code(500);
                $message->success = false;
                $message->message = "error desu wa!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "PUT" && $_SERVER["REQUEST_URI"] == "/changeRole") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = false;
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $admin->update_user_role($json["userid"], $json["roleid"], $message);
                }
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "PUT" && $_SERVER["REQUEST_URI"] == "/changePassword") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                if ($_SESSION["roleid"] != 2) {
                    http_response_code(401);
                    $message->success = false;
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $faculty->update_faculty_password($_SESSION["userid"], $json["password"], $json["new-password"], $message);
                }
            }
        }
        foreach ($_GET as $key => $value) {
            if ($_SERVER["REQUEST_METHOD"] == "GET" && $_SERVER["REQUEST_URI"] == "/getUser?{$key}={$value}") {
                if ($_SESSION["roleid"] != 1 && $_SESSION["roleid"] != 2) {
                    http_response_code(401);
                    $message->success = false;
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else if ($_SESSION["roleid"] == 1) {
                    $admin->get_user($key, $value, $message);
                } else if ($_SESSION["roleid"] == 2) {
                    $faculty->get_student($key, $value, $message);
                } else {
                    http_response_code(500);
                    $message->success = false;
                    $message->message = "user id used is not valid to get!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "DELETE" && $_SERVER["REQUEST_URI"] == "/deleteUser?{$key}={$value}") {
                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = false;
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $admin->delete_user($key, $value, $message);
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "DELETE" && $_SERVER["REQUEST_URI"] == "/deleteRole?{$key}={$value}") {
                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = false;
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $admin->delete_role($key, $value, $message);
                }
            }
        }
    }
}
?>