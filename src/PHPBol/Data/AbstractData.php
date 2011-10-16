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
     * @param array   $data       Array with data
     *
     * @return void
     */
    public function setData($data)
    {
        // Data must be an array
        if (!is_array($data)) {
            throw new \Exception('Data is not an array');
        }

        // Define valid as true e passing validation
        $this->valid    = true;
        $this->warnings = array();

        // Loop metadata e and validating
        foreach ($this->getMetadata() as $fieldName => $metadata) {
            $value = isset($data[$fieldName]) ? $data[$fieldName] : null;
            $this->store($fieldName, $value);
        }
        return $this;
    }

    /**
     * Validate and Store Metadata
     *
     * @param string $fieldName Field Name
     * @param string $value     Value
     *
     * @return type
     */
    protected function store($fieldName, $value)
    {
        // Test if the field exist in metadata
        // If not, so ignores, stores warning and returns valid
        if (!$this->getFieldMetadata($fieldName)) {

            $this->warnings[$fieldName] = 'Field "' . $fieldName . '" with value ' . $value . ' is not necessary and it was ignored';
            return true;

        } else {

            // Loads metadata
            $metadata = $this->getFieldMetadata($fieldName);

            // If value is null and field is required,
            // stores warning and returns invalid
            if (null === $value && $metadata['required'] === true) {
                $this->warnings[$fieldName] = 'Null value informed for a required field';
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
                        $this->warnings[$fieldName] = 'Value ' . $value . ' is not integer, stored ' . $this->offsetGet($fieldName);
                        $this->valid = false;
                    } else {
                        $this->offsetSet($fieldName, (int) $value);
                    }
                    break;

                case 'float':
                    if (!is_float($value)) {
                        $this->offsetSet($fieldName, (float) $value);
                        $this->warnings[$fieldName] = 'Value ' . $value . ' is not float, stored ' . $this->offsetGet($fieldName);
                        $this->valid = false;
                    } else {
                        $this->offsetSet($fieldName, (float) $value);
                    }
                    break;

                case 'date':
                    if (! $value instanceof \DateTime) {
                        $this->offsetSet($fieldName, null);
                        $this->warnings[$fieldName] = 'Value ' . $value . ' is not DateTime, stored "null"';
                    } else {
                        $this->offsetSet($fieldName, $value);
                    }
                    break;

                case 'object':

                    $class = $metadata['class'];
                    if (is_array($value)) {
                        if (!isset($metadata['class'])) {
                            $this->warnings[$fieldName] = 'There is not a class definition for this field (required for type object)';
                            $this->valid = false;
                        } else {
                            try {
                                $obj = new $class;
                                $obj->setData($value);
                                $this->offsetSet($fieldName, $obj);
                            } catch (\Exception $exc) {
                            $this->warnings[$fieldName] = 'Class does not exist for this field';
                            $this->valid = false;
                            }
                        }
                    } else if ($value instanceof $class) {
                        $this->offsetSet($fieldName, $value);
                    } else {
                        $this->warnings[$fieldName] = 'Field with invalid type: ' . gettype($value);
                        $this->valid = false;
                    }

                    if (!$this->offsetGet($fieldName)->isValid()) {
                        $this->warnings[$fieldName] = $this->offsetGet($fieldName)->getWarnings();
                    }
                    break;

                case 'array':

                    if (is_array($value)) {
                        $this->offsetSet($fieldName, $value);
                    } else {
                        $this->warnings[$fieldName] = 'Field with invalid type: ' . gettype($value);
                        $this->valid = false;
                    }

                    break;

                case 'string':
                default:
                    if (!is_string($value)) {
                        $this->offsetSet($fieldName, (string) $value);
                        $this->warnings[$fieldName] = 'Value ' . $value . ' is not a string, stored \'' . (string) $value . '\'';
                        $this->valid = false;
                    } else if (null !== $metadata['length'] && strlen($value) > $metadata['length']) {
                        $this->offsetSet($fieldName, (string) $value);
                        $this->append(array($fieldName => substr($value, 0, $metadata['length']-1)));
                        $this->warnings[$fieldName] = 'Value ' . $value . ' has length larger than the limit, stored ' . $this->offsetGet($fieldName);
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
     * Validate the all data by reloading
     * returns isValid
     *
     * @return boolean
     */
    public function validate()
    {
        $this->setData($this->getData());
        return $this->isValid();
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
     * Generic Getter
     *
     * @param string $name The attribute name
     *
     * @return mixed
     */
    public function get($fieldName)
    {
        $method = 'get' . ucfirst($fieldName);
        if (method_exists($this, $method))
        {
            return $this->$method($fieldName);
        } else {
            if ($this->offsetExists($fieldName)) {
                return $this->offsetGet($fieldName);
            } else {
                return null;
            }
        }
    }

    /**
     * Generic Setter
     *
     * @param string $name The attribute name
     * @param mixed $value The value
     *
     * @return void
     */
    public function set($fieldName, $value)
    {
        $method = 'set' . ucfirst($fieldName);
        if (method_exists($this, $method))
        {
            $this->$method($fieldName, $value);
        } else {
            $this->store($fieldName, $value);
        }
        return $this;
    }

    /**
     * Generic Magic Getter
     *
     * @param string $name The attribute name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->offsetExists($name)) {
            return $this->offsetGet($name);
        } else {
            return null;
        }
    }

    /**
     * Generic Magic Setter
     *
     * @param string $name The attribute name
     * @param mixed $value The value
     *
     * @return AbstractData
     */
    public function __set($name, $value)
    {
        $this->store($name, $value);
        return $this;
    }

}
