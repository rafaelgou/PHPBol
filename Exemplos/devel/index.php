<?php

// Carregando autoload PHP 5.3
require_once('../../src/PHPBol.php');
\PHPBol\PHPBol::register();

// Twig
require_once '../../vendor/Twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// Zend_Barcode
require_once '../../vendor/Zend/Loader/Autoloader.php';
$loader = Zend_Loader_Autoloader::getInstance();
//$loader->registerNamespace('App_');


// Utilizando namespaces
use PHPBol\Data\BasicSacado;
use PHPBol\Data\BasicEmitente;
use PHPBol\Boleto\BoletoBB;

$sacado = array(
    'nome'     => 'Rafael Goulart',
    'endereco' => 'Rua da Feira, s/n',
);

$boleto = new BoletoBB;
$boleto->setSacado($sacado);
$boleto->setAvalista(array());
$boleto->setCedente(array());
$boleto->setBoletoData(array('valorBoleto'=> 124.84));

//echo '<pre>';
//echo '=====================================================================' . PHP_EOL;
//echo 'Boleto BB' . PHP_EOL;
//echo '=====================================================================' . PHP_EOL;
//
//print_r($boleto);
//
//echo '=====================================================================' . PHP_EOL;
//echo 'Warnings' . PHP_EOL;
//echo '=====================================================================' . PHP_EOL;
//print_r($boleto->getWarnings());
//
//
//echo '</pre>';
//echo $boleto['boletoData']['valorBoleto'];
echo $boleto->render();













echo '</pre>';
