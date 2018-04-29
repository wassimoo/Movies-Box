<?php

use AlphaLayout\Renderer;


class Signup
{
    public static function control()
    {
        self::showPage();
    }

    private static function showPage(){
        $renderer = new Renderer(TEMPLATE_DIR);
       $page = $renderer->render("signup.html",["domain" => DOMAIN]);
       echo  $renderer->render("simple.html", ["main" => $page]);
 
    }
}