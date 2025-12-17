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
 * Length between validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
class LengthBetween extends AbstractValidator
{

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
     * @throws Exception
     * @return bool
     */
    public function evaluate(mixed $input = null): bool
    {
        if (!is_array($this->value)) {
            throw new Exception('The value must be an array.');
        } else if (count($this->value) != 2) {
            throw new Exception('The value must be an array that contains 2 values.');
        }

        // Set the input, if passed
        if ($input !== null) {
            $this->input = $input;
        }

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        $inputValue = ($this->hasKeyField()) ? $this->getKeyFieldValue() : $this->input;

        return ((strlen((string)$inputValue) > $this->value[0]) && (strlen((string)$inputValue) < $this->value[1]));
    }

    /**
     * Generate default message

     * @param  mixed $name
     * @param  mixed $value
     * @return string
     */
    public function generateDefaultMessage(mixed $name = null, mixed $value = null): string
    {
        $value1 = null;
        $value2 = null;
        if (($value !== null) && is_array($value) && (count($value) == 2)) {
            $value1 = $value[0];
            $value2 = $value[1];
        } else if (($this->value !== null) && is_array($this->value) && (count($this->value) == 2)) {
            $value1 = $this->value[0];
            $value2 = $this->value[1];
        }

        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "value") .
            " length must be between '" . $value1 . "' and '"  . $value2  . "'.";

        return $this->message;
    }

}
