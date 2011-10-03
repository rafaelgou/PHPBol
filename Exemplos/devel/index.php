<?php

// Carregando autoload PHP 5.3
require_once('../../src/PHPBol.php');
\PHPBol\PHPBol::register();

// Twig
require_once '../../vendor/Twig/lib/Twig/Autoloader.php';
\Twig_Autoloader::register();

// Twig-extensions
require_once '../../vendor/Twig-extensions/lib/Twig/Extensions/Autoloader.php';
\Twig_Extensions_Autoloader::register();

// Zend_Barcode
//require_once '../../vendor/Zend/Loader/Autoloader.php';
//$loader = Zend_Loader_Autoloader::getInstance();


// Definindo dados com array

$cedente = array(
            'nome'     => 'Fulano e Cicrano CIA LTDA',
            'cpfcnpj'  => '01.123.500/0001-45',
            'endereco' => 'Trav Principal, 1500 apto 502',
            'bairro'   => 'Vila Moraes',
            'cidade'   => 'São Paulo',
            'uf'       => 'SP',
            'cep'      => '01555-647',
);

$sacado = array(
    'nome'     => 'Rafael Goulart',
    'endereco' => 'Rua da Feira, s/n',
    'cpfcnpj'  => '555.666.777-88',
    'bairro'   => 'Centro',
    'cidade'   => 'Santana do Livramento',
    'uf'       => 'RS',
    'cep'      => '97000-222',
);

$avalista = array(
    'nome'     => 'Joaquim José da Silva Xavier',
    'cpfcnpj'  => '001.002.003-44',
);

$boletoData = array(
    'valorBoleto' => 532.65,
);



// Carregando namespac
use PHPBol\Boleto\Factory;

// Criando instância e definindo dados
// Utilizando o recurso de chain
$boleto = Factory::create('BB')
        ->setCedente($cedente)
        ->setSacado($sacado)
        ->setAvalista($avalista)
        ->setBoletoData($boletoData)
        ->setDebugOn();

// Outra sintaxe para definir dados
//===================================
//$boleto = Factory::create('BB')
//        ->set('sacado',$sacado)
//        ->set('avalista',$avalista)
//        ->set('cedente',$cedente)
//        ->set('boletoData',$boletoData);



// Algumas saídas de dados! Descomentar e ver
//echo '<pre>';
//echo 'Boleto BB' . PHP_EOL;
//echo '=====================================================================' . PHP_EOL;
//print_r($boleto);
//
//echo '=====================================================================' . PHP_EOL;
//echo 'Warnings' . PHP_EOL;
//echo '=====================================================================' . PHP_EOL;
//print_r($boleto->getWarnings());
//
//
//echo '</pre>';
//
// Buscando valores
// echo $boleto['boletoData']['valorBoleto'];

echo $boleto->render();