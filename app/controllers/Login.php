<?php

use AlphaLayout\Renderer;
use AlphaSession\Session;
use MoviesBox\DBCException;
use MoviesBox\User;

require APP_PATH . "/models/User.php";

class Login
{
    public static function control()
    {
        session_start();
        session_destroy();
        self::showPage();
    }

    private static function showPage()
    {
        if (isset($_SESSION["info"]) && $_SESSION["info"]->validate()) {
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

        try {
            if (($user = User::login($username, $password))== false) {
                echo "false";
            }else{
                $_SESSION["info"] = new Session();
                $_SESSION["info"]->data = ["user" => $user];
                echo "true";
            }
        } catch (DBCException $ex) {
            echo "can't establish connection to server";
            return;
        }

    }
}
