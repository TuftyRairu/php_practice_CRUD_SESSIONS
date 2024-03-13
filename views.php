<?php
require_once 'authentication.php';
require_once 'admincontroller.php';
class Views
{
    public function __construct()
    {
        session_start();

        $auth = new Authentication();
        $admin = new AdminController();

        $message = new stdClass();

        if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/register") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                $auth->register($json["username"], $json["password"], $json["role"], $message);
            } else {
                $message->success = "false";
                $message->message = "error!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/login") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                $auth->login($json["username"], $json["password"], $message);
            } else {
                $message->success = "false";
                $message->message = "error!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/createUser") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                $status = 1;

                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = "false";
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $admin->create_user($json["username"], $json["password"], $json["roleid"], $status, $message);
                }
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/createRole") {
            $cred = file_get_contents("php://input");

            $json = json_decode($cred, true);

            if ($json) {
                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = "false";
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $admin->create_role($json["roleid"], $json["role"], $json["description"], $message);
                }
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["REQUEST_URI"] == "/logout") {
            if ($_SESSION["roleid"] != 1 && $_SESSION["roleid"] != 2 && $_SESSION["roleid"] != 3) {
                http_response_code(400);
                $message->success = "false";
                $message->message = "you haven't login yet!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            } else {
                $auth->logout($message);
            }
        } else if ($_SERVER["REQUEST_METHOD"] == "GET" && $_SERVER["REQUEST_URI"] == "/getUsers") {
            if ($_SESSION["roleid"] != 1) {
                http_response_code(401);
                $message->success = "false";
                $message->message = "invalid access!";
                echo json_encode($message, JSON_PRETTY_PRINT);
            } else {
                $admin->get_users($message);
            }
        }
        foreach ($_GET as $key => $value) {
            if ($_SERVER["REQUEST_METHOD"] == "GET" && $_SERVER["REQUEST_URI"] == "/getUser?{$key}={$value}") {
                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = "false";
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $admin->get_user($key, $value, $message);
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "DELETE" && $_SERVER["REQUEST_URI"] == "/deleteUser?{$key}={$value}") {
                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = "false";
                    $message->message = "invalid access!";
                    echo json_encode($message, JSON_PRETTY_PRINT);
                } else {
                    $admin->delete_user($key, $value, $message);
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "DELETE" && $_SERVER["REQUEST_URI"] == "/deleteRole?{$key}={$value}") {
                if ($_SESSION["roleid"] != 1) {
                    http_response_code(401);
                    $message->success = "false";
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