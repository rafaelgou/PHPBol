<?php
/*
 * This file is part of PHPBol.
 *
 * (c) 2011 Francisco Luz & Rafael Goulart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// Carregando autoload PHP 5.3
require_once dirname(__FILE__) . '/PHPBol.php';
\PHPBol\PHPBol::register();

// Twig
require_once dirname(__FILE__) . '/../vendor/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// Zend_Barcode
require_once dirname(__FILE__) . '/../vendor/Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
