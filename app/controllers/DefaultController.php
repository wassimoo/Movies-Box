<?php

use AlphaLayout\Renderer;


class DefaultController
{
    public static function control()
    {
        self::showPage();
    }

    private static function showPage(){
        $renderer = new Renderer(TEMPLATE_DIR);
       $page = file_get_contents(TEMPLATE_DIR . "/index.html");
       echo  $renderer->render("standard.html", ["main" => $page]);

    }
}