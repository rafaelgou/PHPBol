<?php
/*
 * This file is part of PHPBol.
 *
 * (c) 2011 Francisco Luz & Rafael Goulart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPBol\Boleto\Layout;

use PHPBol\Boleto\AbstractBoleto;
/**
 * Abstração para Boletos
 * Contém funções elementares para todos Templates
 *
 * @package phpbol
 * @subpackage boleto
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
class BB extends AbstractBoleto
{
    public function __construct($data = null)
    {
        parent::__construct($data);
        $this->banco = array(
            'nome'     => 'Banco do Brasil S.A.',
            'codigo'   => '001',
            'codigoDv' => '1'
        );
    }

    /**
     * Metadata info for the class
     *
     * @return array
     */
    protected function getMetadata()
    {
        $metadata = array(
            'banco' => array(
                'required' => true,
                'null'     => false,
                'length'   => null,
                'type'     => 'object',
                'class'    => '\PHPBol\Data\BB\BasicBanco',
                ),
        );

        return array_merge(parent::getMetadata(), $metadata);
    }

    public function febraban20to44($boleto)
    {
        //Carteira might come in this format xx-xxx so we gotta break it apart.
        $carteira     = explode('-', $this->banco->carteira);
        if(isset($carteira[1])) {
            $this->banco->carteiraSub = $carteira[1];
            $this->banco->carteira    = $carteira[0];
        } else {
            $this->banco->carteiraSub = '';
        }

        if(isset($carteira[2])) {
            $this->banco->servico = $carteira[2];
        } else {
            $this->banco->servico = '';
        }

        //pre calculate nosso_numero check digit
        $checkDigit = $this->modulo11($this->banco->convenio . $this->boletoData->nossNumero);
        $checkDigit['digito'] = '-' . $checkDigit['digito'];

        $code = '';

        switch($this->banco->carteira) {
            case 18:
                //now we need to know how many digits convenio number has
                $conv_len =  strlen($this->banco->convenio);
                switch($conv_len) {
                    case 8:
                        // 20-33 -> Convenio                   14
                        // 34-42 -> Nosso Número (sem dígito)   9
                        // 43-44 -> Carteira                    2
                        $this->banco->convenio = str_pad($this->banco->convenio, 14, 0, STR_PAD_LEFT);
                        $nossoNumero           = str_pad($this->boletoData->nossoNumero, 9, 0, STR_PAD_LEFT);

                        //25 digits long
                        $code         = $this->banco->convenio . $nossoNumero . $this->banco->carteira;

                        break;

                    case 7:
                        // 20-32 -> Convenio                   13
                        // 33-42 -> Nosso Número (sem dígito)  10
                        // 43-44 -> Carteira                    2
                        $this->banco->convenio = str_pad($this->banco->convenio, 13, 0, STR_PAD_LEFT);
                        $nossoNumero           = str_pad($this->boletoData->nossoNumero, 10, 0, STR_PAD_LEFT);

                        //25 digits long
                        $code  = $this->banco->convenio . $nossoNumero . $this->banco->carteira;

                        //no check digit for nosso_numero
                        $checkDigit['digito'] = '';
                    break;

                    case 6:
                        if($this->banco->servico == 21) {
                            // 20-25 -> Convenio                   6
                            // 26-42 -> Nosso Número (sem dígito)  17
                            // 43-44 -> Servico                    2
                            $this->banco->convenio = str_pad($this->banco->convenio, 6, 0, STR_PAD_LEFT);
                            $nossoNumero           = str_pad($this->boletoData->nossoNumero, 17, 0, STR_PAD_LEFT);

                            //25 digits long code
                            $code  = $this->banco->convenio . $nossoNumero . $this->banco->servico;
                        } else {
                            // 20-25 -> Convenio                   6
                            // 26-30 -> Nosso Número (sem dígito)  5
                            // 31-34 -> Agencia                    4
                            // 35-42 -> Conta                      8
                            // 43-44 -> Carteira                   2
                            $this->banco->convenio = str_pad($this->banco->convenio, 6, 0, STR_PAD_LEFT);
                            $nossoNumero           = str_pad($this->boletoData->nossoNumero, 5, 0, STR_PAD_LEFT);

                            //25 digits long
                            $code  = $this->banco->convenio . $nossoNumero . $this->banco->agencia . $this->banco->conta . $this->banco->carteira;
                        }
                        break;
                    }
                    break;
            }

       //positions 20 to 44
       $boleto->febraban['20-44'] = $code;

       //save nosso_numero
       $this->boletoData->nossoNumero = ltrim($this->banco->convenio, 0) . $nossoNumero . $checkDigit['digito'];

    }
/*
    //customize object to meet specific needs
    function custom($boleto){
        //calculates check digit for branch number
        $boleto->computed['agencia_dv'] = $boleto->arguments['agencia_dv'];
        if(empty($boleto->arguments['agencia_dv']) &&  $boleto->arguments['agencia_dv'] != '0'){
            $agencia_dv = $boleto->modulo_11($boleto->arguments['agencia']);
            $boleto->computed['agencia_dv'] = $agencia_dv['digito'];
        }
    }

    //manipulate output fields before them getting rendered. This method is called by output().
    public function outputValues($boleto){
        $boleto->output['agencia_codigo_cedente'] = $boleto->arguments['agencia'].'-'.$boleto->computed['agencia_dv'].' / '.$boleto->arguments['conta'].'-'.$boleto->arguments['conta_dv'];

        $boleto->output['contrato']   = $boleto->computed['contrato'];
    }
*/
}