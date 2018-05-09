<?php

use AlphaLayout\Renderer;
use AlphaSession\Session;
use MoviesBox\DBCException;
use MoviesBox\User;

require APP_PATH . "/models/User.php";

class Signup
{
    public static function control()
    {
        session_start();
        self::showPage();
    }

    private static function showPage(){
        if(isset($_SESSION["info"]) && $_SESSION["info"]->validate()){
            header("Location:" . DOMAIN);
        }
        $renderer = new Renderer(TEMPLATE_DIR);
       $page = $renderer->render("signup.html",["domain" => DOMAIN]);
       echo  $renderer->render("simple.html", ["main" => $page]);
 
    }

    public static function performSignup(){
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];

        $user = new User($username, $first_name, $last_name, $email, $password);
        try{
            if($user->register()){
                $_SESSION["info"] = new Session();
                $_SESSION["info"]->data = ["user" => $user];
                echo "true";
            }
        }catch(DBCException $ex){
            echo "can't establish connection to server";

        }
    }
}