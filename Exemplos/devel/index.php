<?php

// Carregando autoload PHP 5.3
require_once('../../src/PHPBol.php');
\PHPBol\PHPBol::register();

// Twig
require_once '../../vendor/Twig/lib/Twig/Autoloader.php';
\Twig_Autoloader::register();

// Twig-extensions
require_once '../../vendor/Twig-extensions-custom/lib/Twig/Extensions/Autoloader.php';
\Twig_Extensions_Autoloader::register();

// Zend_Barcode
//require_once '../../vendor/Zend/Loader/Autoloader.php';
//$loader = Zend_Loader_Autoloader::getInstance();


// Definindo dados com array
$global = array(
    'titulo'    => 'Boleto Teste',
    'logo'      => '../../Resources/public/images/PHPBol167x50.png',
);

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
    'logo'     => '',
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
    'nossoNumero'          => '001245',
    'numeroDocumento'      => '01',
    'dataVencimento'       => new DateTime('2011-10-30'),
    'dataEmissaoDocumento' => new DateTime('2011-10-01'),
    'dataProcessamento'    => new DateTime('2011-10-01'),
    'aceite'               => '',
    'especie'              => 'R$',
    'especieDoc'           => '',
    'quantidade'           => 1,
    'valorUnitario'        => null,
    'valorBoleto'          => 532.65,
    'descontoAbatimento'   => null,
    'outrasDeducoes'       => null,
    'moraMulta'            => null,
    'outrosAcrescimos'     => null,
    'valorCobrado'         => null,
    'demonstrativo'        => 'Mensalidade 4/2010<br/>'
                            . 'Evite corte de serviços, pague em dia<br/>'
                            . 'Não esqueça da minha calói',
    'instrucoes'           => '- Conceder desconto de pontualidade de R$ 5,00 para pagamento até a data do vencimento.<br/>'
                            . '- Após o vencimento, cobrar juros diário de R$ 0,20.<br/>'
                            . '- 10 dias após o vencimento, cobrar valor fixo de R$ 47,00. (Serviços suspensos até o pagamento).',
);

$banco = array(
    'logo'          => '../../Resources/public/images/logohsbc.jpg',
    'codigoCedente' => '132465798',
    'codigo'        => '399',
    'codigoDv'      => '9',
    'agencia'       => '1340',
    'agenciaDv'     => '',
    'conta'         => '15268',
    'contaDv'       => '44',
    'carteira'      => '9',
    // BB
    'convenio'      => null,
    'servico'       => null,


);


// Carregando namespac
use PHPBol\Boleto\Factory;

// Criando instância e definindo dados
// Utilizando o recurso de chain
$boleto = Factory::create('BB')
        ->setGlobal($global)
        ->setBanco($banco)
        ->setCedente($cedente)
        ->setSacado($sacado)
        ->setAvalista($avalista)
        ->setBoletoData($boletoData)
        ->setDebugOn()
        ->configure();
$boleto->validate();


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