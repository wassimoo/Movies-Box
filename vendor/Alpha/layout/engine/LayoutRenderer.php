<?php

namespace AlphaLayout;

use AlphaCache;

require_once __DIR__ . "/Renderer.php";

/**
 * Loads shared layouts and stores them to memmory
 * shared layouts will be called multiple times without having to render them again.
 */
class LayoutRenderer extends Renderer
{
    private static $registeredLayouts = [];
    private static $parsedLayouts = [];

    /**
     * @var string $name layout name 
     * @var string $fileName layout filename that resides inside default shared folder.
     */
    public static function registerLayout($name , $fileName){
        self::$registeredLayouts[$name] = $fileName;
    }

    /**
     * @var string $name prepared layout name
     * @return mixed bool if layout is not loaded
     */
    public static function getParsedLayout($name){
        if(! array_key_exists($name,self::$parsedLayouts))
            return false;
        return self::$parsedLayouts[$name];
    }

    /**
     * Parses and loads layout to be rendered along with main page.
     * @var string $name 
     * @var array $viewsBag to be bound to layout
     * @return bool layout loading success/failure .
     */
    public static function parseLayout($name, $viewsBag = array()){ 
        if(array_key_exists($name,self::$registeredLayouts)){
            self::$parsedLayouts[$name] = self::render(SHARED_FOLDER_NAME . "/" . self::$registeredLayouts[$name],$viewsBag);
            return true;
        }
        return false;
    }

    /**
     * it's recommanded to call this methode and clear unused layouts to free up memmory
     * @var string $name layout name 
     */
    public static function unloadLayout($name){
        if(array_key_exists($name,self::$parsedLayouts)){
            unset(self::$parsedLayouts[$name]);
        }
    }

    /**
     * Renders a given page name with the correspondent viewBag
     * 
     * @param string $fileName to be rendered
     * @param array $viewsBag to be bound to file
     * @return rendered layout
     */
    public static function render($fileName , $viewsBag = array()){
        self::$twig = self::$twig ?? init(self::$template);
        return self::$twig->render($fileName,$viewsBag);
    }


}