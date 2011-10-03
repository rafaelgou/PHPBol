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
 * Cedente Data
 *
 * @package phpbol
 * @subpackage data
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class BasicCedente extends AbstractData
{
    /**
     * Metadata info for the class
     *
     * @return array
     */
    protected function getMetadata()
    {
        return array(
            'nome' => array(
                'required' => true,
                'null'     => false,
                'length'   => 70,
                'type'     => 'string',
                ),
            'cpfcnpj' => array(
                'required' => false,
                'null'     => true,
                'length'   => 18,
                'type'     => 'string',
                ),
            'endereco' => array(
                'required' => true,
                'null'     => false,
                'length'   => 70,
                'type'     => 'string',
                ),
            'bairro' => array(
                'required' => true,
                'null'     => false,
                'length'   => 30,
                'type'     => 'string',
                ),
            'cidade' => array(
                'required' => true,
                'null'     => false,
                'length'   => 50,
                'type'     => 'string',
                ),
            'uf' => array(
                'required' => true,
                'null'     => false,
                'length'   => 2,
                'type'     => 'string',
                ),
            'cep' => array(
                'required' => false,
                'null'     => false,
                'length'   => 9,
                'type'     => 'string',
                ),
        );
    }

}