<?php

use AlphaLayout\Renderer;

class Shows
{
    public static function all()
    {
        self::showPage();
    }
    
    
    private static function showPage(){
        $renderer = new Renderer(TEMPLATE_DIR);
       $page = $renderer->render("tv-show.html",["domain" => DOMAIN]);
       echo  $renderer->render("standard.html", ["main" => $page]);
    }
}