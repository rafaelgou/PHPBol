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
 * Banco Data
 *
 * @package phpbol
 * @subpackage data
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class BasicBanco extends AbstractData
{
    /**
     * Lista de campos obrigatórios a serem definidos via array
     *
     * @return array
     */
    protected function getMetadata()
    {
        return array(
            'nome' => array(
                'required' => true,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
            'logo' => array(
                'required' => true,
                'null'     => true,
                'length'   => 100,
                'type'     => 'string',
                ),
            'codigo' => array(
                'required' => true,
                'null'     => false,
                'length'   => 3,
                'type'     => 'string',
                ),
            'codigoDv' => array(
                'required' => false,
                'null'     => false,
                'length'   => 1,
                'type'     => 'string',
                ),
            'agencia' => array(
                'required' => true,
                'null'     => false,
                'length'   => 4,
                'type'     => 'string',
                ),
            'agenciaDv' => array(
                'required' => false,
                'null'     => false,
                'length'   => 1,
                'type'     => 'string',
                ),
            'conta' => array(
                'required' => true,
                'null'     => false,
                'length'   => 10,
                'type'     => 'string',
                ),
            'contaDv' => array(
                'required' => false,
                'null'     => false,
                'length'   => 2,
                'type'     => 'string',
                ),
            'codigoCedente' => array(
                'required' => true,
                'null'     => true,
                'length'   => 20,
                'type'     => 'string',
                ),
            'carteira' => array(
                'required' => true,
                'null'     => false,
                'length'   => 3,
                'type'     => 'string',
                ),
        );
    }

}