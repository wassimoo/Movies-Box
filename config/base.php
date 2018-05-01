<?php

namespace MoviesBox;

/**
 * Project base path
 */
define("BASE_PATH", rtrim(__DIR__ , "/config"));

/**
 * app path ; where MVC exists
 */
define("APP_PATH", BASE_PATH . "/app");

/**
 * Website Configuration files path
 */
define("CONFIG_PATH", BASE_PATH . "/config");

/**
 * Templates path
 */
define("VIEWS_PATH", BASE_PATH . "/app/views");


/**
 * default template folder basename 
 */
define("DEFAULT_TEMPLATE" , "bigshow");

/**
 * shared layout (resides inside template) folder name
 */
define("SHARED_FOLDER_NAME" , "shared");

/**
 * domain name in case-sensitive
 */
 define("DOMAIN","http://localhost/Movies-Box/");

/**
 * Composer autoloader
 */
require_once BASE_PATH . "/vendor/autoload.php";

/**
 * Alpha Renderer 
 */

require_once BASE_PATH . "/vendor/Alpha/layout/engine/Renderer.php";

/**
 * Prepare layouts 
 */
require_once CONFIG_PATH . "/layouts/layouts.php";

/**
 * Database connection 
 */
require_once APP_PATH . "/models/db_layer/Oracle.php";


/**
 * Database configuration
 */
require_once CONFIG_PATH . "/database.php";


/**
 * Session handler class
 */
require_once BASE_PATH . "/vendor/Alpha/session/session.php";