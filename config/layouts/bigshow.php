<?php
/**
 * Created by PhpStorm.
 * User: wassim
 * Date: 28/04/18
 * Time: 23:32
 */

function loadTemplateLayout(&$views, &$viewsBag, &$writeExtern){
    $views = [

        "head" => "head.html",

        "preloader" => "preloader.html",

        "top_header" => "top-header.html",

        "main_header" => "main-header.html",

        "scripts" => "scripts.html",

        "footer" => "footer.html",

        "standard" => "standard.html",

        "simple" => "simple.html"
    ];

    $viewsBag = [
        "head" => ["domain" => DOMAIN],

        "preloader" => [],

        "top_header" => ["domain" => DOMAIN],

        "main_header" => ["domain" => DOMAIN],

        "footer" => ["domain" => DOMAIN],

        "scripts" => ["domain" => DOMAIN],

        "standard" => [
            "head" => "head",
            "preloader" => "preloader",
            "top_header" => "top_header",
            "main_header" => "main_header",
            "main" => "{{main | raw }}",
            "footer" => "footer",
            "scripts" => "scripts"
        ],

        "simple" => [
            "head" => "head",
            "main" => "{{main | raw }}",
            "scripts" => "scripts"
        ]
    ];

    $writeExtern = [
        "standard" => TEMPLATE_DIR . "/standard.html",
        "simple" => TEMPLATE_DIR . "/simple.html"
    ];

}