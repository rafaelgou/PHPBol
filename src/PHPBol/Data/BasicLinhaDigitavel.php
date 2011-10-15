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
 * Avalista Data
 *
 * @package phpbol
 * @subpackage data
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class BasicLinhaDigitavel extends AbstractData
{
    /**
     * Metadata info for the class
     *
     * @return array
     */
    protected function getMetadata()
    {
        return array(
            'completa' => array(
                'required' => false,
                'null'     => false,
                'length'   => 55,
                'type'     => 'string',
                ),
            'barcode' => array(
                'required' => false,
                'null'     => false,
                'length'   => 48,
                'type'     => 'string',
                ),
        );
    }

}