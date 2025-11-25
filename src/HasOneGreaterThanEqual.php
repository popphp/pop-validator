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
 * Has one that is greater than or equal validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
class HasOneGreaterThanEqual extends AbstractValidator
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

        if (!str_contains($field, '.')) {
            return (array_key_exists($field, $this->input) &&
                !is_array($this->input[$field]) && ($this->input[$field] >= $requiredValue));
        } else {
            $value = [];
            self::traverseData($field, $this->input, $value);

            if (is_array($value)) {
                foreach ($value as $val) {
                    if ($val >= $requiredValue) {
                        return true;
                    }
                }
                return false;
            } else {
                return ($value >= $requiredValue);
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
            " that is greater than or equal to the required value.";

        return $this->message;
    }

}
