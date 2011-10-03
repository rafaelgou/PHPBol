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
 * Gloval Data
 *
 * @package phpbol
 * @subpackage data
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class BasicGlobal extends AbstractData
{

    /**
     * Metadata info for the class
     *
     * @return array
     */
    protected function getMetadata()
    {
        return array(
            'titulo' => array(
                'required' => true,
                'null'     => true,
                'length'   => 200,
                'type'     => 'string',
                ),
            'logo' => array(
                'required' => false,
                'null'     => true,
                'length'   => 200,
                'type'     => 'string',
                ),
        );
    }

}