<?php
/*
 * This file is part of PHPBol.
 *
 * (c) 2011 Francisco Luz & Rafael Goulart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPBol\Data\BB;

use PHPBol\Data\BasicBanco as BaseBasicBanco;

/**
 * Banco Data
 *
 * @package phpbol
 * @subpackage data
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class BasicBanco extends BaseBasicBanco
{
    /**
     * Lista de campos obrigatórios a serem definidos via array
     *
     * @return array
     */
    protected function getMetadata()
    {
        $metadata = array(
            'carteiraSub' => array(
                'required' => false,
                'null'     => true,
                'length'   => 3,
                'type'     => 'string',
                ),
            'convenio' => array(
                'required' => true,
                'null'     => false,
                'length'   => 3,
                'type'     => 'string',
                ),
            'contrato' => array(
                'required' => false,
                'null'     => true,
                'length'   => 10,
                'type'     => 'string',
                ),
            'servico' => array(
                'required' => false,
                'null'     => true,
                'length'   => 10,
                'type'     => 'string',
                ),
        );
        return array_merge(parent::getMetadata(), $metadata);

    }

}