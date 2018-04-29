<?php

namespace AlphaLayout;

class Renderer
{
    /**
     * @var string current used template folder name 
     */
    protected $templatesDir;

    /**
     * @var Twig  object instance.
     */
    protected $twig;

    /**
     * @throws \Exception
     */
    public function __construct(string $templatesDir){
        if($this->setTemplate($templatesDir)){
            $this->initTwig($this->templatesDir);
        }else{
            throw new \Exception("Can't find templates directory");
        }
    }

    /**
     * Choose template to be rendered
     * 
     * @var string $template folder name that resides inside views/
     * @return boolean whether folder exists or not . 
     */
    public function setTemplate($template){
        if(file_exists($template)){
            $this->templatesDir = $template;
            $this->twig = null;
            return true;
        }
        return false;
    }

    /**
     * Create Twig instance $this->twig
     * @param string $templateDir
     */
    public function initTwig(string $templatesDir){
        $loader = new \Twig_Loader_Filesystem($templatesDir);
        
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => false,
        ));
    }
    
    /**
     * Renders a given page name with the correspondent viewBag
     * 
     * @param string $fileName to be rendered
     * @param array $viewsBag to be bound to file
     * @return string renderedPage
     */
    public function render($fileName , $viewsBag = array()){
        return $this->twig->render($fileName,$viewsBag);
    }
}