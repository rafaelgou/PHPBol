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
use \ArrayIterator;
/**
 * Basic Template to Boletos
 *
 * @package phpbol
 * @subpackage data
 * @author Francisco Luz <drupalist@naosei.com>
 * @author Rafael Goulart <rafaelgou@gmail.com>
 */
abstract class AbstractData extends ArrayIterator
{
    /**
     * Data definition warnings
     *
     * @var array
     */
    protected $warnings = array();

    /**
     * Flag defining if data is valid or not
     *
     * @var boolean
     */
    protected $valid = false;

    /**
     * Classes for all data
     * @var array
     */
    protected $classes = array();

    /**
     * Constructor
     *
     * @param array $data Array of data
     *
     * @return void
     */
    public function __construct($data=null)
    {
        // If data was sent, so set it
        if (null !== $data) {
            $this->setData($data);
        }
    }

    /**
     * Abstract getMetadata must be implemented by chidren classes
     *
     * @return array
     */
    abstract protected function getMetadata();

    /**
     * Gets metadata for field
     *
     * @param string $fieldName Field name
     *
     * @return mixed array
     */
    protected function getFieldMetadata($fieldName)
    {
        $metadata = $this->getMetadata();
        return (isset($metadata[$fieldName])) ? $metadata[$fieldName] : false;
    }

    /**
     * Set data by an array
     *
     * @param array $data Array with data
     *
     * @return void
     */
    public function setData($data)
    {
        // Data must be an array
        if (!is_array($data)) {
            throw new \Exception('Data do Sacado não são um array');
        }

        // Define valid as true e passing validation
        $this->valid = true;

        // Loop metadata e and validating
        foreach ($this->getMetadata() as $fieldName => $metadata) {
            $value = isset($data[$fieldName]) ? $data[$fieldName] : null;
            $this->validateAndStore($fieldName, $value);
        }
    }

    /**
     * Validate and Store Metadata
     *
     * @param string $fieldName Field Name
     * @param string $value     Value
     *
     * @return type
     */
    protected function validateAndStore($fieldName, $value)
    {
        // Test if the field exist in metadata
        // If not, so ignores, stores warning and returns valid
        if (!$this->getFieldMetadata($fieldName)) {

            $this->warnings[$fieldName] = 'Campo "' . $fieldName . '" com valor ' . $value . ' não é necessário e será ignorado';
            return true;

        } else {

            // Loads metadata
            $metadata = $this->getFieldMetadata($fieldName);

            // If value is null and field is required,
            // stores warning and returns invalid
            if (null === $value && $metadata['required'] === true) {
                $this->warnings[$fieldName] = 'Valor nulo para campo requerido';
                $this->valid = false;
                return false;
            }

            // If field is null and is permited
            // returns valid
            if (null === $value && $metadata['null'] === true) {
                return true;
            }

            // Switch by Type
            switch ($metadata['type']) {

                default:
                case 'int':
                    if (!is_int($value)) {
                        $this->offsetSet($fieldName, (int) $value);
                        $this->warnings[$fieldName] = 'Valor ' . $value . ' não é inteiro, armazenado ' . $this->$fieldName;
                        $this->valid = false;
                    } else {
                        $this->offsetSet($fieldName, (int) $value);
                    }
                    break;

                case 'float':
                    if (!is_float($value)) {
                        $this->offsetSet($fieldName, (float) $value);
                        $this->warnings[$fieldName] = 'Valor ' . $value . ' não é float, armazenado ' . $this->$fieldName;
                        $this->valid = false;
                    } else {
                        $this->offsetSet($fieldName, (float) $value);
                    }
                    break;

                case 'date':
                    if (! $value instanceof \DateTime) {
                        $this->offsetSet($fieldName, null);
                        $this->warnings[$fieldName] = 'Valor ' . $value . ' não é DateTime, armazenado "null"';
                    } else {
                        $this->offsetSet($fieldName, $value);
                    }
                    break;

                case 'array/object':

                    $class = $this->classes[$fieldName];
                    if (is_array($value)) {
                        if (!isset($this->classes[$fieldName])) {
                            $this->warnings[$fieldName] = 'Não existe definição de classe para este campo';
                            $this->valid = false;
                        } else {
                            $obj = new $class;
                            $obj->setData($value);
                            $this->offsetSet($fieldName, $obj);
                        }
                    } else if ($value instanceof $class) {
                        $this->offsetSet($fieldName, $value);
                    } else {
                        $this->warnings[$fieldName] = 'Campo com tipo inválido: ' . gettype($value);
                        $this->valid = false;
                    }

                    if (!$this->offsetGet($fieldName)->isValid()) {
                        $this->warnings[$fieldName] = $this->offsetGet($fieldName)->getWarnings();
                    }

                    break;

                case 'string':
                default:
                    if (!is_string($value)) {
                        $this->offsetSet($fieldName, (string) $value);
                        $this->warnings[$fieldName] = 'Valor ' . $value . ' não é string, armazenado ' . (string) $value;
                        $this->valid = false;
                    } else if (strlen($value) > $metadata['length']) {
                        $this->offsetSet($fieldName, (string) $value);
                        $this->append(array($fieldName => substr($value, 0, $metadata['length']-1)));
                        $this->warnings[$fieldName] = 'Valor ' . $value . ' tem tamanho maior que o definido, armazenado ' . $this->$fieldName;
                    } else {
                        $this->offsetSet($fieldName, (string) $value);
                    }
                    break;
            }
        }

    }

    /**
     * Return if data is valid or not
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Get warnings
     *
     * @return array
     */
    public function getWarnings()
    {
        return $this->warnings;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->getArrayCopy();
    }

    /**
     * offsetSet implemented for ArrayAccess
     *
     * @param string $offset Property
     * @param mixed  $value  Value
     *
     * @return void
     */
//    public function offsetSet($offset, $value)
//    {
//        if (null === $offset) {
//            throw new \Exception('Invalid offset');
//        } else {
//            $this->$offset = $value;
//        }
//    }

    /**
     * offsetExists implemented for ArrayAccess
     *
     * @param string $offset Property
     *
     * @return void
     */
//    public function offsetExists($offset)
//    {
//        return isset($this->$offset);
//    }

    /**
     * offsetUnset implemented for ArrayAccess
     *
     * @param string $offset Property
     *
     * @return void
     */
//    public function offsetUnset($offset)
//    {
//        $this->$offset = null;
//    }

    /**
     * offsetGet implemented for ArrayAccess
     *
     * @param string $offset Property
     *
     * @return void
     */
//    public function offsetGet($offset)
//    {
//        return isset($this->$offset) ? $this->$offset : null;
//    }


}


/*
// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - ITAÚ
$databoleto["agencia"] = "1565"; // Num da agencia, sem digito
$databoleto["conta"] = "13877";	// Num da conta, sem digito
$databoleto["conta_dv"] = "4"; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - ITAÚ
$databoleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109 ou 178

// SEUS DADOS
$databoleto["identificacao"] = "BoletoPhp - Código Aberto de Sistema de Boletos";
$databoleto["cpf_cnpj"] = "";
$databoleto["endereco"] = "Coloque o endereço da sua empresa aqui";
$databoleto["cidade_uf"] = "Cidade / Estado";
$databoleto["cedente"] = "Coloque a Razão Social da sua empresa aqui";
*/