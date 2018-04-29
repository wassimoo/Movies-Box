<?php

use AlphaLayout\Renderer;

class Login
{
    public static function control()
    {
        self::showPage();
    }

    private static function showPage(){
       $renderer = new Renderer(TEMPLATE_DIR);
       $page = $renderer->render("login.html",["domain" => DOMAIN]);
       echo  $renderer->render("simple.html", ["main" => $page]);
    }
}