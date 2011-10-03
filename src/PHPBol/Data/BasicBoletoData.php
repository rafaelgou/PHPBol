<?php
/*
 * This file is part of PHPBol.
 *
 * (c) 2011 Francisco Luz & Rafael Goulart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPBol\Data;

/**
 * Basic Boleto Data
 *
 * @package phpbol
 * @subpackage data
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class BasicBoletoData extends AbstractData
{

    /**
     * Metadata info for the class
     *
     * @return array
     */
    protected function getMetadata()
    {
        return array(
            'logoEmitente' => array(
                'required' => false,
                'null'     => true,
                'length'   => 200,
                'type'     => 'string',
                ),
            'nossoNumero' => array(
                'required' => true,
                'null'     => true,
                'length'   => 8,
                'type'     => 'string',
                ),
            'numeroDocumento' => array(
                'required' => true,
                'null'     => true,
                'length'   => 10,
                'type'     => 'string',
                ),
            'dataVencimento' => array(
                'required' => true,
                'null'     => true,
                'length'   => null,
                'type'     => 'date',
                ),
            'dataEmissaoDocumento' => array(
                'required' => true,
                'null'     => true,
                'length'   => null,
                'type'     => 'date',
                ),
            'dataProcessamento' => array(
                'required' => false,
                'null'     => true,
                'length'   => null,
                'type'     => 'date',
                ),
            'valorBoleto' => array(
                'required' => true,
                'null'     => true,
                'length'   => false,
                'type'     => 'float',
                ),
            'quantidade' => array(
                'required' => false,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
            'valorUnitario' => array(
                'required' => false,
                'null'     => true,
                'length'   => null,
                'type'     => 'float',
                ),
            'aceite' => array(
                'required' => false,
                'null'     => true,
                'length'   => 10,
                'type'     => 'string',
                ),
            'especie' => array(
                'required' => false,
                'null'     => true,
                'length'   => 10,
                'type'     => 'string',
                ),
            'especieDoc' => array(
                'required' => false,
                'null'     => true,
                'length'   => 10,
                'type'     => 'string',
                ),
            'demonstrativo1' => array(
                'required' => false,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
            'demonstrativo2' => array(
                'required' => false,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
            'demonstrativo3' => array(
                'required' => false,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
            'instrucoes1' => array(
                'required' => false,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
            'instrucoes2' => array(
                'required' => false,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
            'instrucoes3' => array(
                'required' => false,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
        );
    }

}