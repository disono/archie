<?php

namespace Archie\View;

class DSTwig
{

    private $_cacheFolder = null;
    private $_templateFolder = null;
    private $_hasCaching = false;
    private $_functions = [];
    private $_debug = false;

    public function __construct()
    {
        $this->_config();
    }

    public function dispatch($template, $data)
    {
        $loader = new \Twig_Loader_Filesystem($this->_templateFolder);
        $twig = new \Twig_Environment($loader, [
            'cache' => ($this->_hasCaching === true) ? $this->_cacheFolder : false,
            'debug' => $this->_debug
        ]);

        // set custom functions
        $this->_setFunctions($twig);

        // render the template
        return $twig->render($template . '.twig', $data);
    }

    /**
     * Set all the required config
     */
    private function _config(): void
    {
        $config = config('views');

        $this->_cacheFolder = $config['cache_path'];
        $this->_templateFolder = $config['template_path'];
        $this->_hasCaching = $config['enable_caching'];
        $this->_functions = $config['functions'];
        $this->_debug = $config['debug'];
    }

    /**
     * Custom functions
     *
     * @param $twig
     */
    private function _setFunctions($twig): void
    {
        foreach($this->_functions as $name => $function) {
            $twig->addFunction(new \Twig_Function($name, $function));
        }
    }

}