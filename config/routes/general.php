<?php

namespace moviesBox;

use AlphaRouter\Router; 

require_once BASE_PATH . "/vendor/Alpha/router/Router.php" ;

/* Create new router instance with autoResponseMatch flag equals true*/ 
$router = new Router(APP_PATH,"controllers/DefaultController.php",true);

/* Website is developed locally under http://localhost/movies-box/ escape moviesbox to emulate webhost*/
$router->setPrefix("movies-box");

/* not found url */
