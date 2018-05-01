<?php

use AlphaLayout\Renderer;
use MoviesBox\DBCException;
use MoviesBox\Oracle;
use AlphaSession\Session;
 
class Login
{
    public static function control()
    {
        session_start();
        //session_destroy();
        self::showPage();
    }

    private static function showPage()
    {
        if(isset($_SESSION["info"]) && $_SESSION["info"]->validate()){
            header("Location:" . DOMAIN);
        }

        $renderer = new Renderer(TEMPLATE_DIR);
        $page = $renderer->render("login.html", ["domain" => DOMAIN]);
        echo $renderer->render("simple.html", ["main" => $page]);
    }

    public static function performLogin()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $db = new Oracle();
        try {
            $db->connect(USERNAME, PASSWORD);
        } catch (DBCException $ex) {
            echo "can't establish connection to server";
            return;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "SELECT password
                    FROM client
                  WHERE username = :username";
        $result = $db->qin($query, ["username" => $username]);

        if ($result["success"] == false) {
            echo "false";
            return;
        } else {
            $result = $result["data"];
        }

        if (password_verify($password, $result[0]["PASSWORD"]) == false) {
            echo "false";
            return;
        }

        $_SESSION["info"] = new Session();
        $_SESSION["info"]->data = ["username" => $username];
        echo "true";
    }
}
