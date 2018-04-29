<?php


require_once BASE_PATH . '/vendor/autoload.php';

function init($template){

    $loader = new Twig_Loader_Filesystem(VIEWS_PATH . "/$template");
    
    $twig = new Twig_Environment($loader, array(
        'cache' => false,
    ));
    return $twig;
}