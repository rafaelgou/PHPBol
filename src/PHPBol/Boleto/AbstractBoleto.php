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
     * Dados temporários do banco
     * @var type
     */
    protected $banco = array(
        'nome'     => '',
        'codigo'   => '',
        'codigoDV' => ''
    );

    /**
     * Holds values that went through some sort of processing.
     */
    public $computed = array(
        'codigo_banco_com_dv' => '',
        'valor_cobrado'       => '',
        'dataVencimento'     => '',
        // human readable line.
        'linha_digitavel'     => '',
        'nosso_numero'        => '',
        'bar_code'            => array(
            // final drawn.
            'strips' => '',
            // strip widths for debug checking.
            'widths' => ''
            ),
        );
    /**
     * Holds the values calculated accordingly to the FEBRABAN specification.
     */
    public $febraban = array('1-3'   => '', // Codigo do banco sem o digito.
                             '4-4'   => 9,  // Codigo da Moeda (9-Real).
                             '5-5'   => '', // Dígito verificador do código de barras.
                             '6-9'   => '', // Fator de vencimento.
                             '10-19' => '', // Valor Nominal do Titulo.
                             '20-44' => '', // Campo Livre. Set by child class (issuer bank implementation).
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
                'class'    => '\PHPBol\Data\BasicCedente',
                ),
            'sacado' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                'class'    => '\PHPBol\Data\BasicSacado',
                ),
            'avalista' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                'class'    => '\PHPBol\Data\BasicAvalista',
                ),
            'banco' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                'class'    => '\PHPBol\Data\BasicBanco',
                ),
            'global' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                'class'    => '\PHPBol\Data\BasicGlobal',
                ),
            'boletoData' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                'class'    => '\PHPBol\Data\BasicBoletoData',
                ),
            'linhaDigitavel' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                'class'    => '\PHPBol\Data\BasicLinhaDigitavel',
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
        $this->store('cedente', $cedente);
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
        $this->store('sacado', $sacado);
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
        $this->store('avalista', $avalista);
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
        $this->store('boletoData', $boletoData);
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
        if (is_array($banco)) {
            $banco['nome']     = $this->banco['nome'];
            $banco['codigo']   = $this->banco['codigo'];
            $banco['codigoDv'] = $this->banco['codigoDv'];

        } else {
            $banco->nome     = $this->banco['nome'];
            $banco->codigo   = $this->banco['codigo'];
            $banco->codigoDv = $this->banco['codigoDv'];
        }

        $this->store('banco', $banco);
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
        $this->store('global', $global);
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
            'isValid'        => $this->isValid(),
            'global'         => $this->offsetExists('global') ? $this->offsetGet('global')->getData() : false,
            'banco'          => $this->offsetExists('banco') ? $this->offsetGet('banco')->getData() : false,
            'cedente'        => $this->offsetExists('cedente') ? $this->offsetGet('cedente')->getData() : false,
            'sacado'         => $this->offsetExists('sacado') ? $this->offsetGet('sacado')->getData() : false,
            'avalista'       => $this->offsetExists('avalista') ? $this->offsetGet('avalista')->getData() : false,
            'boletoData'     => $this->offsetExists('boletoData') ? $this->offsetGet('boletoData')->getData() : false,
            'linhaDigitavel' => $this->offsetExists('linhaDigitavel') ? $this->offsetGet('linhaDigitavel')->getData() : false,
            'warnings'       => $this->getWarnings(),
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
            'isValid'        => $this->isValid(),
            'global'         => $this->offsetExists('global') ? $this->offsetGet('global')->getData() : false,
            'banco'          => $this->offsetExists('banco') ? $this->offsetGet('banco')->getData() : false,
            'cedente'        => $this->offsetExists('cedente') ? $this->offsetGet('cedente')->getData() : false,
            'sacado'         => $this->offsetExists('sacado') ? $this->offsetGet('sacado')->getData() : false,
            'avalista'       => $this->offsetExists('avalista') ? $this->offsetGet('avalista')->getData() : false,
            'boletoData'     => $this->offsetExists('boletoData') ? $this->offsetGet('boletoData')->getData() : false,
            'linhaDigitavel' => $this->offsetExists('linhaDigitavel') ? $this->offsetGet('linhaDigitavel')->getData() : false,
            'warnings'       => $this->getWarnings(),
            ));
    }

    /**
     * Render the Fatura Template
     *
     * @return string
     */
    public function renderFatura()
    {
        $class = 'PHPBol\Template\\' . $this->templateClasses['default'];
        $template = new $class();
        $template->setDebug($this->debug);
        return $template->render(array(
            'isValid'        => $this->isValid(),
            'global'         => $this->offsetExists('global') ? $this->offsetGet('global')->getData() : false,
            'banco'          => $this->offsetExists('banco') ? $this->offsetGet('banco')->getData() : false,
            'cedente'        => $this->offsetExists('cedente') ? $this->offsetGet('cedente')->getData() : false,
            'sacado'         => $this->offsetExists('sacado') ? $this->offsetGet('sacado')->getData() : false,
            'avalista'       => $this->offsetExists('avalista') ? $this->offsetGet('avalista')->getData() : false,
            'boletoData'     => $this->offsetExists('boletoData') ? $this->offsetGet('boletoData')->getData() : false,
            'linhaDigitavel' => $this->offsetExists('linhaDigitavel') ? $this->offsetGet('linhaDigitavel')->getData() : false,
            'warnings'       => $this->getWarnings(),
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


    /**
     * Calculates a check digit from any given number based on the modulo 10 specification.
     *
     * @param String $num
     *    The number you wish to calcule the check digit from.
     * @see Documentation at http:// www.febraban.org.br/Acervo1.asp?id_texto=195&id_pagina=173&palavra=
     * @return String
     *    The check digit number.
     */
    public function modulo10($num)
    {
        $numtotal10 = 0;
        $fator      = 2;

        //  Separacao dos numeros.
        for ($i = strlen($num); $i > 0; $i--) {
            //  pega cada numero isoladamente.
            $numeros[$i] = substr($num,$i-1,1);
            //  Efetua multiplicacao do numero pelo (falor 10).
            $temp = $numeros[$i] * $fator;
            $temp0=0;
            foreach (preg_split('// ',$temp,-1,PREG_SPLIT_NO_EMPTY) as $k=>$v){ $temp0+=$v; }
            $parcial10[$i] = $temp0; // $numeros[$i] * $fator;
            //  monta sequencia para soma dos digitos no (modulo 10).
            $numtotal10 += $parcial10[$i];
            if ($fator == 2) {
                $fator = 1;
            } else {
                // intercala fator de multiplicacao (modulo 10).
                $fator = 2;
            }
        }

        $resto  = $numtotal10 % 10;
        $digito = 10 - $resto;

        // make it zero if check digit is 10.
        $digito = ($digito == 10) ? 0 : $digito;

        return $digito;
    }

    /**
     * Calculates a check digit from any given number based on the modulo 11 specification.
     *
     * @param string $num
     *    The number you wish to calcule the check digit from
     * @param string $base
     *    Optional. This is defaulted to 9
     * @see Documentation at http:// www.febraban.org.br/Acervo1.asp?id_texto=195&id_pagina=173&palavra=
     * @return array
     *    The returned array keys are digito and resto
     */
    public function modulo11($num, $base=9)
    {
        $fator = 2;

        $soma  = 0;
        /* Separacao dos numeros */
        for ($i = strlen($num); $i > 0; $i--) {
            //  pega cada numero isoladamente
            $numeros[$i] = substr($num,$i-1,1);
            //  Efetua multiplicacao do numero pelo falor
            $parcial[$i] = $numeros[$i] * $fator;
            //  Soma dos digitos
            $soma += $parcial[$i];
            if ($fator == $base) {
                //  restaura fator de multiplicacao para 2
                $fator = 1;
            }
            $fator++;
        }
        $result = array('digito' => ($soma * 10) % 11,
                        'resto'  => $soma % 11, // Remainder
                        );
        if($result['digito'] == 10){
            $result['digito'] = 0;
        }

        return $result;
    }

    /**
     * Calculation of "Due Date" field
     * Argument values expected: -1             == Cash against document
     *                           Integer Number == Number of days added on top of issuing date
     *                           dd-mm-yyy      == Set date
     *
     * If argument is not present then it adds 5 days on top of issuing date.
     */
    public function dataVencimento()
    {
        /** set defaults**/
        // making sure we got a dash "-" instead of forward slah "/" for vencimento.
        //$this->boletoData->dataVencimento
        $this->computed['dataVencimento'] = str_replace('/','-', $this->arguments['dataVencimento']);
        // adds 5 days on top of issuing date.
        $vencimento            = '+5';
        $vencimentoValue      = date('d-m-Y', strtotime($vencimento.' days'));

        // check if an argument for vencimento has been set.
        if(!empty($this->arguments['dataVencimento'])){
            if(is_numeric($this->arguments['dataVencimento'])){
                // cash against document.
                if($this->arguments['dataVencimento'] == -1){
                    $vencimentoValue = 'Contra Apresenta&ccedil;&atilde;o';
                }else{
                    // for any other integer.
                    $vencimento       = '+'.$this->arguments['dataVencimento'];
                    $vencimentoValue = date('d-m-Y', strtotime($vencimento.' days'));
                }
            }else{
                // an actual date was sent through.
                $vencimentoValue =  $this->computed['dataVencimento'];
                // check if date is in a valide format.
                $date_check = explode('-', $vencimentoValue);
                if(!checkdate($date_check[1], $date_check[0], $date_check[2])){
                    $this->setWarning(array('dataVencimento', 'Invalido. Certifique-se que tenha informado ou -1 ou um numero inteiro ou uma data no formato dd-mm-yyyy.<br>'));
                }
            }
        }
        $this->computed['dataVencimento']    = $vencimentoValue;
    }

    /**
     * Calculates the "Due date" 4 digits factor number.
     * It is the positions from 6 to 9 in the Febraban array.
     *
     * script from http:// phpbrasil.com/articles/print.php/id/1034
     */
    public function fatorVencimento()
    {
        $from = $this->settings['fatorVencimento_base'];
        $to   = $this->computed['dataVencimento'];

        if($this->arguments['dataVencimento'] == -1){
            // "Due Date" is Cash against document.
            $days = '0000';
        }else{
            // Usar DD-MM-AA ou DD-MM-AAAA.
            list($from_day, $from_month, $from_year) = explode("-", $from);
            list($to_day, $to_month, $to_year) = explode("-", $to);
            $from_date = mktime(0, 0, 0, $from_month, $from_day, $from_year);
            $to_date = mktime(0, 0, 0, $to_month, $to_day, $to_year);

            $days = round(($to_date - $from_date) / 86400);
        }
        // assign value to febraban array property.
        $this->febraban['6-9'] = $days;
    }

    /**
     * Calculates the check digit for bank code.
     */
    public function codigo_banco_com_dv() {
        if (!empty($this->arguments['bank_code_cd'])) {
            $this->computed['codigo_banco_com_dv'] = $this->bank_code.'-'.$this->arguments['bank_code_cd'];
        }
        else{
            //  set codigo_banco_com_dv.
            $bank_code_checkDigit = $this->modulo11($this->bank_code);
            $this->computed['codigo_banco_com_dv'] = $this->bank_code.'-'.$bank_code_checkDigit['digito'];

        }
    }

    /**
     * Calculates and construct the FEBRABAN specification.
     *
     * 01-03 (3)  -> Código do banco sem o digito.
     * 04-04 (1)  -> Código da Moeda (9-Real).
     * 05-05 (1)  -> Dígito verificador do código de barras.
     * 06-09 (4)  -> Fator de vencimento.
     * 10-19 (10) -> Valor Nominal do Título.
     * 20-44 (25) -> Campo Livre.
     *                 This is calculated at child's class implementation by febraban20to44().
     *
     * @see Documentation at http://www.febraban.org.br/Acervo1.asp?id_texto=195&id_pagina=173&palavra=
     */
    public function febraban(){
        // Positions 1 to 3.
        $this->febraban['1-3'] = $this->bank_code;
        // Position 4 has a pre set value of 9.
        // Positions 6-9 is done at fatorVencimento().

        // remove decimal separator from valor_cobrado
        $vc   = str_replace('.','', $this->computed['valor_cobrado']);
        // Positions 10 to 19.
        $this->febraban['10-19'] = str_pad($vc, 10, 0, STR_PAD_LEFT);

        if($this->is_implemented) {
            // Check if method is implemented.
            if(in_array('febraban_20to44', $this->methods['child'])){
                // Positions 20 to 44 vary from bank to bank, so we call the child extention.
                $child = 'Banco_'.$this->bank_code;
                call_user_func(array($child, 'febraban_20to44'), $this);
            }
        }
        // Calculate the check digit (position 5) of all 43 number set.
        $checkDigit = '';
        foreach($this->febraban as $value){
            $checkDigit .= $value;
        }
        $checkDigit = $this->modulo11($checkDigit);
        $resto      = $checkDigit['resto'];
        if($resto == 0 || $resto == 1 || $resto == 10){
            $checkDigit['digito'] = 1;
        }else{
            $checkDigit['digito'] = 11 - $resto;
        }
        // Position 5 (check digit for the whole set).
        $this->febraban['5-5'] = $checkDigit['digito'];

        // Check if febraban property is complying with the rules and
        // Create an array of allowed lenghs for each febraban block.
        $rules = array('1-3' => 3, '4-4' => 1, '5-5' => 1, '6-9' => 4, '10-19' => 10, '20-44' => 25);

        foreach($this->febraban as $key => $value){
            $lengh = strlen($value);
            if($lengh != $rules[$key]){
                $this->setWarning(array("febraban[$key]", "possui $lengh digitos enquanto deveria ter $rules[$key]."));
            }
        }

        // Check if child class wants to do any custom stuff before object delivering.
        if($this->is_implemented) {
            if(in_array('custom', $this->methods['child'])){
                // When present, this is the last method to be called in the construction chain.
                $childClass = 'Banco_'.$this->bank_code;
                call_user_func(array($childClass, 'custom'), $this);
            }
        }

    }

    /**
     * Assembles the human readable code set (linha digitavel).
     */
    public function linhaDigitavel()
    {

       // Break down febraban positions 20 to 44 into 3 blocks of 5, 10 and 10 characters each.
       $blocks = array('20-24' => substr($this->febraban['20-44'], 0, 5),
                       '25-34' => substr($this->febraban['20-44'], 5, 10),
                       '35-44' => substr($this->febraban['20-44'], 15, 10),
                       );

       // Concatenates bankCode + currencyCode + first block of 5 characters and
       // calculates its check digit for part1.
       $checkDigit = $this->modulo10($this->bank_code.$this->febraban['4-4'].$blocks['20-24']);

       // Shift in a dot on block 20-24 (5 characters) at its 2nd position.
       $blocks['20-24'] = substr_replace($blocks['20-24'], '.', 1, 0);

       // Concatenates bankCode + currencyCode + first block of 5 characters + checkDigit.
       $part1 = $this->bank_code.$this->febraban['4-4'].$blocks['20-24'].$checkDigit;

       // calculates part2 check digit from 2nd block of 10 characters.
       $checkDigit = $this->modulo10($blocks['25-34']);

       $part2 = $blocks['25-34'].$checkDigit;
       // shift in a dot at its 6th position.
       $part2 = substr_replace($part2, '.', 5, 0);

       // calculates part3 check digit from 3rd block of 10 characters.
       $checkDigit = $this->modulo10($blocks['35-44']);

       // as part2, we do the same process again for part3.
       $part3 = $blocks['35-44'].$checkDigit;
       $part3 = substr_replace($part3, '.', 5, 0);

       // check digit for the human readable number.
       $cd = $this->febraban['5-5'];
       // put part4 together.
       $part4  = $this->febraban['6-9'].$this->febraban['10-19'];

       // now put everything together.
       $this->computed['linhaDigitavel'] = "$part1 $part2 $part3 $cd $part4";

       // now validates the human readable number.
       $lengh = strlen($this->computed['linhaDigitavel']);
       if($lengh != 54){
            $lengh -= 7;
            $this->setWarning(array("linhaDigitavel", "possui $lengh digitos enquanto deveria ter 47."));
       }
    }


    public function configure()
    {
        // calculate valor_cobrado (total amount).
        $subtractions = $this->arguments['desconto_abatimento'] + $this->arguments['outras_deducoes'];
        $additions    = $this->arguments['mora_multa'] + $this->arguments['outros_acrescimos'];
        $this->computed['valor_cobrado'] = ($this->arguments['valor_boleto'] - $subtractions) + $additions;

        $this->boletoData->valorCobrado =
                $this->boletoData->valorBoleto +
                $this->boletoData->moraMulta +
                $this->boletoData->outrosAcrescimos -
                $this->boletoData->descontoAbatimento -
                $this->boletoData->outrasDeducoes;

        return $this;
    }











}