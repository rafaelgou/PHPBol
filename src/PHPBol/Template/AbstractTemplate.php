<?php
/*
 * This file is part of PHPBol.
 *
 * (c) 2011 Francisco Luz & Rafael Goulart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPBol\Template;

/**
 * Abstração para Templates para Boletos
 * Contém funções elementares para todos Templates
 *
 * @package phpbol
 * @subpackage templates
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class AbstractTemplate
{
    /**
     * Debug active or not
     * @var boolean
     */
    protected $debug = false;

    /**
     * Use Cache active or not
     * @var boolean
     */
    protected $useCache = false;

    /**
     *
     * @var type
     */
    protected $twig         = null;
    /**
     * Twig Loader Filesystem
     * @var Twig_Loader_Filesystem
     */
    protected $twigLoader   = null;

    /**
     * The Templates Path
     * @var string
     */
    protected $templatePath = null;
    /**
     * The Cache Path
     * @var string
     */
    protected $cachePath    = null;

    /**
     * The Template to render
     * @var string
     */
    protected $template     = null;

    /**
     * Construtor da Classe
     *
     * @return void
     */
    public function __construct($useCache=false, $debug=false, $templatePath=null, $cachePath=null)
    {
        $this->setCachePath($cachePath)
                ->setDebug($debug)
                ->setTemplatePath($templatePath)
                ->setCachePath($cachePath)
                ->loadTwig();
    }

    protected function loadTwig()
    {
        $this->twigLoader = new \Twig_Loader_Filesystem($this->templatePath);

        $envOptions = array();
        if ($this->useCache) {
            $envOptions['cache'] = $this->cachePath;
        }
        if ($this->debug) {
            $envOptions['debug'] = $this->debug;
        }
        $this->twig = new \Twig_Environment($this->twigLoader, $envOptions);
        $this->twig->addExtension(new \Twig_Extensions_Extension_Debug());
    }

    /**
     * Set templatePath
     *
     * @param strint $debug The template Path
     *
     * @return \PHPBol\Template\AbstractTemplate
     */
    public function setTemplatePath($templatePath)
    {
        if (null === $templatePath) {
            $templatePath = dirname(__FILE__) . '/../../../Resources/views';
        }
        $this->templatePath = $templatePath;
        if (null !== $this->twig)
        {
            $this->loadTwig();
        }
        return $this;
    }

    /**
     * Get templatePath
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return $this->templatePath;
    }

    /**
     * Set cachePath
     *
     * @param strint $debug The cache Path
     *
     * @return \PHPBol\Template\AbstractTemplate
     */
    public function setCachePath($cachePath)
    {
        if (null === $cachePath) {
            $cachePath = dirname(__FILE__) . '/../../../cache';
        }
        $this->cachePath = $cachePath;
        if (null !== $this->twig)
        {
            $this->loadTwig();
        }
        return $this;
    }

    /**
     * Get cachePath
     *
     * @return string
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * Set debug
     *
     * @param boolean $debug True or false
     *
     * @return \PHPBol\Template\AbstractTemplate
     */
    public function setDebug($debug=true)
    {
        $this->debug = $debug;
        if (null !== $this->twig)
        {
            $this->loadTwig();
        }
        return $this;
    }

    /**
     * Set debug on
     *
     * @return \PHPBol\Template\AbstractTemplate
     */
    public function setDebugOn()
    {
        return $this->setDebug(true);
    }

    /**
     * Set debug off
     *
     * @return \PHPBol\Template\AbstractTemplate
     */
    public function setDebugOff()
    {
        return $this->setDebug(false);
    }

    /**
     * Get debug
     *
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Set useCache
     *
     * @param boolean $useCache True or false
     *
     * @return \PHPBol\Template\AbstractTemplate
     */
    public function setUseCache($useCache=true)
    {
        $this->useCache = $useCache;
        if (null !== $this->twig)
        {
            $this->loadTwig();
        }
        return $this;
    }

    /**
     * Set useCache on
     *
     * @return \PHPBol\Template\AbstractTemplate
     */
    public function setUseCacheOn()
    {
        return $this->setUseCache(true);
    }

    /**
     * Set useCache off
     *
     * @return \PHPBol\Template\AbstractTemplate
     */
    public function setUseCacheOff()
    {
        return $this->setUseCache(false);
    }

    /**
     * Get UseCache
     *
     * @return boolean
     */
    public function getUseCache()
    {
        return $this->UseCache;
    }

    /**
     * Render the actual configured template
     *
     * @return string
     */
    public function render($data)
    {
        if (null === $this->template) {
            throw new Exception('No template defined - use ::setTemplate method');
        }
        return $this->displayTemplate($this->template, $data);
    }

    /**
     * Load a Twig Template
     *
     * @param string $name The Template Name
     *
     * @return Twig_TemplateInterface
     */
    public function loadTemplate($name)
    {
        $this->twig->loadTemplate($name);
    }

    /**
     * Display a template
     * @param string $name The Template Name
     * @param mixed  $data Array/Object with data to merge
     *
     * @return string
     */
    public function displayTemplate($name, $data=array())
    {
        if ($this->debug) {
            $data = array_merge($data, array('debug'=>true));
        }
        return $this->twig->render($name, $data);
    }

}