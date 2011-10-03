<?php

//require_once('../../src/autoload.php');

// Carregando autoload PHP 5.3
require_once('../../src/PHPBol.php');
\PHPBol\PHPBol::register();

// Twig
require_once '../../vendor/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// Zend_Barcode
//$zendPath = dirname(__FILE__) . '/../../vendor/';
//set_include_path(get_include_path() . PATH_SEPARATOR . $zendPath);
//require_once 'Zend/Loader/Autoloader.php';
//$loader = Zend_Loader_Autoloader::getInstance();

$zendPath = '/var/www/scripts/library/';
set_include_path(get_include_path() . PATH_SEPARATOR . $zendPath);
require_once 'Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();


$barcodeOptions = array('text' => '104927277414990200040000000114296508600000012609');
$rendererOptions = array();
$renderer = Zend_Barcode::factory(
    'Code25interleaved', 'image', $barcodeOptions, $rendererOptions
);

//$renderer->draw();
$renderer->render();