<?php

use AlphaLayout\Layout;

require_once CONFIG_PATH . "/cache/cache.php"; //cache configurations
require_once BASE_PATH . "/vendor/Alpha/layout/Layout.php"; //Layout component
require_once CONFIG_PATH . "/templateLoader.php"; //load user registered template and related configurations
require_once TEMPLATE_LAYOUTS_FILE; //template layouts and its correspondent view bags

loadTemplateLayout($bigShow, $bigShowViewBag, $writeExtern);
verifyBaseLayouts($bigShow, $bigShowViewBag, $writeExtern);

/**
 * Makes sure layouts are saved to cache.
 * otherwise render them again.
 *
 * @param array $template
 * @param array $templateViewBag
 * @param array $writeExtern
 * @return bool
 */
function verifyBaseLayouts(array $template, array $templateViewBag, array $writeExtern)
{
    try {
        $layout = new Layout(TEMPLATE_SHARED_FOLDER, CACHE_FOLDER . "/", TEMPLATE_NAME);
        if (($unrendered = $layout->verifyLayoutArray(array_keys($template))) != null) {
            foreach ($unrendered as $view) {
                $layout->renderLayout($view, $template[$view], $templateViewBag[$view]);
            }
        }else{
            return true; //already rendered no need to write to extern file
        }

    } catch (\Exception $ex) {
        //TODO : handle base layout load failure . ( render again ? ) .
        //must add exception class in both Cache and Renderer to identify exception type.
        return false;
    }


    foreach ($writeExtern as $name => $file) {
        $layout->loadLayout($name, true, $file);
    }
}
