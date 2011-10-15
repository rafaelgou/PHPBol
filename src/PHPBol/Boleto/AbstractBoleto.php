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
     * Debug active or not
     * @var boolean
     */
    protected $debug = false;

    /**
     * Use Cache active or not
     * @var boolean
     */
    protected $useCache = false;

    /**
     * Classes for all object data
     * @var array
     */
    protected $classes = array(
        'banco'          => '\PHPBol\Data\BasicBanco',
        'global'         => '\PHPBol\Data\BasicGlobal',
        'cedente'        => '\PHPBol\Data\BasicCedente',
        'sacado'         => '\PHPBol\Data\BasicSacado',
        'avalista'       => '\PHPBol\Data\BasicAvalista',
        'boletoData'     => '\PHPBol\Data\BasicBoletoData',
        'linhaDigitavel' => '\PHPBol\Data\BasicLinhaDigitavel',
    );

    /**
     * The templates classes to render the boleto
     * By format
     * @var string
     */
    protected $templateClasses = array(
        'default' => 'DefaultBoletoTemplate',
        'carne'   => 'CarneBoletoTemplate',
        'fatura'  => 'FaturaBoletoTemplate',
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
                'type'     => 'object',
                ),
            'sacado' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                ),
            'avalista' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                ),
            'banco' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                ),
            'global' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                ),
            'boletoData' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                ),
            'linhaDigitavel' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                ),
        );
    }

    /**
     * Set Cedente
     *
     * @param mixed $cedente Array or \PHPBol\Data\BasicCedente value
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setCedente($cedente)
    {
        $this->validateAndStore('cedente', $cedente);
        return $this;
    }

    /**
     * Set Sacado
     *
     * @param mixed $sacado Array or \PHPBol\Data\BasicSacado value
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setSacado($sacado)
    {
        $this->validateAndStore('sacado', $sacado);
        return $this;
    }

    /**
     * Set Avalista
     *
     * @param mixed $avalista Array or \PHPBol\Data\BasicAvalista value
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setAvalista($avalista)
    {
        $this->validateAndStore('avalista', $avalista);
        return $this;
    }

    /**
     * Set BoletoData
     *
     * @param mixed $boletoData Array or \PHPBol\Data\BasicAvalista value
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setBoletoData($boletoData)
    {
        $this->validateAndStore('boletoData', $boletoData);
        return $this;
    }

    /**
     * Set Banco
     *
     * @param mixed $banco Array or \PHPBol\Data\BasicSacado value
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setBanco($banco)
    {
        $this->validateAndStore('banco', $banco);
        return $this;
    }

    /**
     * Set Global
     *
     * @param mixed $global Array or \PHPBol\Data\BasicSacado value
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setGlobal($global)
    {
        $this->validateAndStore('global', $global);
        return $this;
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
        $template->setDebug($this->debug);
        return $template->render(array(
            'isValid'    => $this->isValid(),
            'global'     => $this->offsetExists('global') ? $this->offsetGet('global')->getData() : false,
            'banco'      => $this->offsetExists('banco') ? $this->offsetGet('banco')->getData() : false,
            'cedente'    => $this->offsetExists('cedente') ? $this->offsetGet('cedente')->getData() : false,
            'sacado'     => $this->offsetExists('sacado') ? $this->offsetGet('sacado')->getData() : false,
            'avalista'   => $this->offsetExists('avalista') ? $this->offsetGet('avalista')->getData() : false,
            'boletoData' => $this->offsetExists('boletoData') ? $this->offsetGet('boletoData')->getData() : false,
            'warnings'   => $this->getWarnings(),
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
        $template->setDebug($this->debug);
        return $template->render(array(
            'isValid'    => $this->isValid(),
            'global'     => $this->offsetExists('global') ? $this->offsetGet('global')->getData() : false,
            'banco'      => $this->offsetExists('banco') ? $this->offsetGet('banco')->getData() : false,
            'cedente'    => $this->offsetExists('cedente') ? $this->offsetGet('cedente')->getData() : false,
            'sacado'     => $this->offsetExists('sacado') ? $this->offsetGet('sacado')->getData() : false,
            'avalista'   => $this->offsetExists('avalista') ? $this->offsetGet('avalista')->getData() : false,
            'boletoData' => $this->offsetExists('boletoData') ? $this->offsetGet('boletoData')->getData() : false,
            'warnings'   => $this->getWarnings(),
            ));
    }

    /**
     * Render the Default Template
     * proxy method to renderDefault
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function render()
    {
        return $this->renderDefault();
    }

    /**
     * Set debug
     *
     * @param boolean $debug True or false
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setDebug($debug=true)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Set debug on
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setDebugOn()
    {
        return $this->setDebug(true);
    }

    /**
     * Set debug off
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setDebugOff()
    {
        return $this->setDebug(false);
    }

    /**
     * Get debug
     *
     * @return boolean
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Set useCache
     *
     * @param boolean $useCache True or false
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setUseCache($useCache=true)
    {
        $this->useCache = $useCache;
        return $this;
    }

    /**
     * Set useCache on
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setUseCacheOn()
    {
        return $this->setUseCache(true);
    }

    /**
     * Set useCache off
     *
     * @return \PHPBol\Boleto\AbstractBoleto
     */
    public function setUseCacheOff()
    {
        return $this->setUseCache(false);
    }

    /**
     * Get UseCache
     *
     * @return boolean
     */
    public function getUseCache()
    {
        return $this->UseCache;
    }


}