# PHPBol

VERSÃO ALPHA - Em desenvolvimento

Classe avançada de geração de Boletos/Bloquete de Cobrança em PHP

## Características

* Suporte ao PHP 5.3 (namespaces) e 5.2
* Completamente escrito seguindo padrões
* Gera XHTML validado pela W3C
* Impressão de carnês com quebra de página
* Altamente extensível
* Código desacoplado
* Facilita integração com banco de dados


## Requisitos

* Twig
* Zend_Barcode (incluído no pacote em versão mínima)

## Instalação

    git clone git@github.com:rafaelgou/PHPBol.git

Se quiser utilizar a versão do

    cd PHPBol\vendor\Twig
    git submodule init
    git submodule update

## Autoload PHP 5.3

Autoload do PHPBol

    require_once 'PATH_TO_LIB/PHPBol/src/PHPBol.php';
    \PHPBol\PHPBol::register();

Autoload do Twig (pode ser de outra localização)

    require_once 'PATH_TO_LIB/PHPBol/vendor/Twig/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();

Autoload Zend Framework

    require_once 'PATH_TO_LIB/PHPBol/vendor/Zend/Loader/Autoloader.php';
    $loader = Zend_Loader_Autoloader::getInstance();

Ou todas automaticamente:

    require_once 'PATH_TO_LIB/PHPBol/src/autoload.php';

## PHPDocumentor

Documentação das Classes em `Resorces\phpdoc`

