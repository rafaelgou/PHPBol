<?php
/*
 * This file is part of PHPBol.
 *
 * (c) 2011 Francisco Luz & Rafael Goulart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPBol\Boleto;

/**
 * Abstração para Boletos
 * Contém funções elementares para todos Templates
 *
 * @package phpbol
 * @subpackage boleto
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class Factory
{

    /**
     * Constructor
     *
     * set private to avoid directly instatiation to implement
     * Factory Design Pattern
     **/
    private function __construct()
    {
    }

    /**
     * Factory
     * Permite a criação do boleto com definição apenas do layout
     *
     * @param string $layout O layout bancário para instanciar
     *                       somente a parte específica, será instanciado
     *                       \PHPBol\Boleto\Boleto$layout
     * @param mixed  $dados  Array com dados ou null
     *
     * @return PHPBol\Boleto\AbstractBoleto
     */
    static public function create($layout, $dados=null)
    {
        // Criando nome da classe
        $classe = "\PHPBol\Boleto\Boleto{$layout}";
        return new $classe($dados);
    }
}