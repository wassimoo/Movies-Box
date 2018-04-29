<?php

namespace AlphaLayout;

use AlphaViewCache\ViewCache as Cache;

require_once __DIR__ . "/engine/Renderer.php";
require_once __DIR__ . "/../cache/ViewCache.php";

class Layout
{

    /**
     * @var Renderer twig rendering engine.
     */
    private $renderEngine;

    /**
     * @var Cache caching engine
     */
    private $cacheEngine;

    /**
     * @var array temporary rendered layouts
     */
    private $uncachedLayouts;

    /**
     * @var string $templatesDir
     * @var string $cacheFolder path to cache folder
     * @var string $cacheId will be used as unique id for cache instance.
     * @throws \Exception
     */
    public function __construct(string $templatesDir, string $cacheFolder, string $cacheId = null)
    {
        if ($cacheId === null) {
            $cacheId = Cache::randHash();
        }
        $this->renderEngine = new Renderer($templatesDir);
        $this->cacheEngine = new Cache($cacheId, $cacheFolder, ".cache");
        $this->uncachedLayouts = [];
    }

    /**
     * May return string evaluating to false use === .
     * @var $name layout Name
     * @return mixed bool|string
     */
    public function loadLayout(string $name, bool $fromCache = true, string $outputFile = null)
    {
        if ($fromCache == false) {
            if(!isset($this->uncachedLayouts[$name]))
                return false;
            else
                $layout = $this->uncachedLayouts[$name];
        } else {
            try {
                $layout = $this->cacheEngine->retrieve($name);
            } catch (\Exception $e) {
                return false;
            }
        }
        if($outputFile !== null)
            file_put_contents($outputFile, $layout);

        return $layout;
    }

    /**
     * Render Layout with it's correpondant registered or cached layouts
     * if not found, original value is kept.
     * @throws \Exception can't access cache file
     */
    public function renderLayout(string $name, string $fileName, array $viewBag = [], bool $saveToCache = true)
    {
        foreach ($viewBag as $viewName => $value) {
            if ($this->cacheEngine->isCached($value)) {
                $viewBag[$viewName] = $this->loadLayout($value, true);
            } else if (($uncached = $this->loadLayout($value, false)) !== false) {
                $viewBag[$viewName] = $uncached;
            }
        }

        $rendered = $this->renderEngine->render($fileName, $viewBag);

        if ($saveToCache) {
            $this->cacheEngine->cache($name, $rendered);
        } else {
            $this->uncachedLayouts[$name] = $rendered;
        }
    }

    public function getCacheId()
    {
        return $this->cacheEngine->getName();
    }

    /**
     * Verify layout is cached or not.
     * @return bool
     */
    public function verifyLayout($layout)
    {
        return $this->cacheEngine->isCached($layout);
    }

    /**
     * Verify layouts are saved to cache
     * @return array of uncached layouts
     */
    public function verifyLayoutArray(array $layouts)
    {

        /**
         * @var array of uncached layouts
         */
        $uncached = array();

        if (empty($layouts)) {
            return false;
        }

        foreach ($layouts as $name) {
            if ($this->verifyLayout($name) == false) {
                array_push($uncached, $name);
            }
        }

        return empty($uncached) ? null : $uncached;
    }
}
