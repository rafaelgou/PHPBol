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
    public function __construct($useCache=false, $templatePath=null, $cachePath=null)
    {
        if (null === $templatePath) {
            $templatePath = dirname(__FILE__) . '/../../../Resources/views';
        }

        if (null === $cachePath) {
            $cachePath = dirname(__FILE__) . '/../../../cache';
        }

        $this->twigLoader = new \Twig_Loader_Filesystem($templatePath);

        if ($useCache) {
            $this->twig = new \Twig_Environment($this->twigLoader, array(
              'cache' => $cachePath,
            ));
        } else {
            $this->twig = new \Twig_Environment($this->twigLoader);
        }

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
        return $this->twig->render($name, $data);
    }

}