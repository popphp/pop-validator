<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

/**
 * Has count less than validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class HasCountLessThan extends AbstractValidator
{

    /**
     * Traits
     */
    use TraverseTrait;

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
     * @throws Exception
     * @return bool
     */
    public function evaluate(mixed $input = null): bool
    {
        // Set the input, if passed
        if ($input !== null) {
            $this->input = $input;
        }

        $result = false;

        if (!is_array($input)) {
            throw new Exception('Error: The evaluated input must be an array.');
        }
        if (!is_array($this->value) || !is_numeric(reset($this->value))) {
            throw new Exception("Error: The evaluated value must be an array of node name and count value, e.g. ['node' => 3].");
        }

        $field = array_key_first($this->value);
        $count = reset($this->value);

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        if (!str_contains($field, '.')) {
            return (array_key_exists($field, $this->input) &&
                is_array($this->input[$field]) && count($this->input[$field]) < $count);
        } else {
            $value = [];
            self::traverseData($field, $this->input, $value);

            return (is_array($value) && (isset($value[0])) && (count($value[0]) < $count));
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
        $count = null;

        if (($value !== null) && is_array($value)) {
            $field = array_key_first($value);
            $count = reset($value);
        } else if ($this->value !== null) {
            $field = array_key_first($this->value);
            $count = reset($this->value);
        }

        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "array") .
            " must have a field '" . $field . "' with less than " . $count . " item(s).";

        return $this->message;
    }

}
