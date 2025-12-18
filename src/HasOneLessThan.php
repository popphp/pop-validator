<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

/**
 * Has one that is less than validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
class HasOneLessThan extends AbstractValidator
{

    /**
     * Traits
     */
    use TraverseTrait;

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
     * @param  bool  $isDateTime
     * @return bool
     *@throws Exception
     */
    public function evaluate(mixed $input = null, bool $isDateTime = false): bool
    {
        // Set the input, if passed
        if ($input !== null) {
            $this->input = $input;
        }

        if (!is_array($input)) {
            throw new Exception('Error: The evaluated input must be an array.');
        }
        if (!is_array($this->value)) {
            throw new Exception("Error: The evaluated value must be an array of node name and value, e.g. ['node' => 3].");
        }

        $field         = array_key_first($this->value);
        $requiredValue = reset($this->value);

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        if (!str_contains($field, '.') && (!$this->hasField())) {
            if ($isDateTime) {
                return (array_key_exists($field, $this->input) &&
                    !is_array($this->input[$field]) && (strtotime($this->input[$field]) < strtotime($requiredValue)));
            } else {
                return (array_key_exists($field, $this->input) &&
                    !is_array($this->input[$field]) && ($this->input[$field] < $requiredValue));
            }
        } else if ($this->hasKeyField()) {
            ['key' => $key, 'field' => $field] = $this->getField();
            if ($isDateTime) {
                return (array_key_exists($key, $this->input) && array_key_exists($field, $this->input[$key]) &&
                    (strtotime($this->input[$key][$field]) < strtotime($requiredValue)));
            } else {
                return (array_key_exists($key, $this->input) && array_key_exists($field, $this->input[$key]) &&
                    ($this->input[$key][$field] < $requiredValue));
            }
        } else {
            $value = [];
            self::traverseData($field, $this->input, $value);

            if (is_array($value)) {
                foreach ($value as $val) {
                    if ($isDateTime) {
                        if (strtotime($val) < strtotime($requiredValue)) {
                            return true;
                        }
                    } else {
                        if ($val < $requiredValue) {
                            return true;
                        }
                    }
                }
                return false;
            } else {
                return ($isDateTime) ? (strtotime($value) < strtotime($requiredValue)) : ($value < $requiredValue);
            }
        }
    }

    /**
     * Generate default message

     * @param  mixed $name
     * @param  mixed $value
     * @return string
     */
    public function generateDefaultMessage(mixed $name = null, mixed $value = null): string
    {
        $field = null;

        if (($value !== null) && is_array($value)) {
            $field = array_key_first($value);
        } else if ($this->value !== null) {
            $field = array_key_first($this->value);
        }

        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "input") .
            " must contain one item" . (($this->value !== null) ? " of '" . $field . "'" : "") .
            " that is less than the required value.";

        return $this->message;
    }

}
