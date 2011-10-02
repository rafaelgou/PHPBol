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
use PHPBol\Data;

/**
 * Abstract Class to Boletos
 * Contains common methods to all banks
 *
 * @package phpbol
 * @subpackage boleto
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class AbstractBoleto extends Data\AbstractData
{

    /**
     * Cedente
     * @var \PHPBol\Data\BasicCedente
     */
    protected $cedente;

    /**
     * Sacado
     * @var \PHPBol\Data\BasicSacado
     */
    protected $sacado;

    /**
     * Avalista
     * @var \PHPBol\Data\BasicAvalista
     */
    protected $avalista;

    /**
     * BoletoData
     * @var \PHPBol\Data\BasicBoleto
     */
    protected $boletoData;

    /**
     * Classes for all object data
     * @var array
     */
    protected $classes = array(
        'cedente'    => '\PHPBol\Data\BasicCedente',
        'sacado'     => '\PHPBol\Data\BasicSacado',
        'avalista'   => '\PHPBol\Data\BasicAvalista',
        'boletoData' => '\PHPBol\Data\BasicBoletoData',
    );

    /**
     * The templates classes to render the boleto
     * By format
     * @var string
     */
    protected $templateClasses = array(
        'default' => 'DefaultBoletoTemplate',
        'carne'   => 'CarneBoletoTemplate',
    );



    /**
     * Metadata info for the class
     *
     * @return array
     */
    protected function getMetadata()
    {
        return array(
            'cedente' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'array/object',
                ),
            'sacado' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'array/object',
                ),
            'avalista' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'array/object',
                ),
            'boletoData' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'array/object',
                ),
        );
    }

    /**
     * Set Cedente
     *
     * @param mixed $cedente Array or \PHPBol\Data\BasicCedente value
     */
    public function setCedente($cedente)
    {
        $this->validateAndStore('cedente', $cedente);
    }

    /**
     * Set Sacado
     *
     * @param mixed $sacado Array or \PHPBol\Data\BasicSacado value
     */
    public function setSacado($sacado)
    {
        $this->validateAndStore('sacado', $sacado);
    }

    /**
     * Set Avalista
     *
     * @param mixed $avalista Array or \PHPBol\Data\BasicAvalista value
     */
    public function setAvalista($avalista)
    {
        $this->validateAndStore('avalista', $avalista);
    }

    /**
     * Set BoletoData
     *
     * @param mixed $boletoData Array or \PHPBol\Data\BasicAvalista value
     */
    public function setBoletoData($boletoData)
    {
        $this->validateAndStore('boletoData', $boletoData);
    }

    /**
     * Render the Default Template
     *
     * @return string
     */
    public function renderDefault()
    {
        $class = 'PHPBol\Template\\' . $this->templateClasses['default'];
        $template = new $class();
        return $template->render(array(
            'boletoData' => $this->offsetGet('boletoData')->getData(),
            'sacado'     => $this->offsetGet('sacado')->getData(),
            'cedente'    => $this->offsetGet('cedente')->getData(),
            'avalista'   => $this->offsetGet('avalista')->getData(),
            ));
    }

    /**
     * Render the Carne Template
     *
     * @return string
     */
    public function renderCarne()
    {
        $class = 'PHPBol\Template\\' . $this->templateClasses['carne'];
        $template = new $class();
        return $template->render(array(
            'boletoData' => $this->offsetGet('boletoData')->getData(),
            'sacado'     => $this->offsetGet('sacado')->getData(),
            'cedente'    => $this->offsetGet('cedente')->getData(),
            'avalista'   => $this->offsetGet('avalista')->getData(),
            ));
    }

    /**
     * Render the Default Template
     * proxy method to renderDefault
     *
     * @return string
     */
    public function render()
    {
        return $this->renderDefault();
    }


}