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
 * Has one validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class HasOne extends AbstractValidator
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

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        if (!is_array($input)) {
            throw new Exception('Error: The evaluated input must be an array.');
        }
        if (empty($this->value)) {
            throw new Exception('Error: The evaluated value cannot be empty.');
        }

        if (!str_contains($this->value, '.')) {
            return (array_key_exists($this->value, $this->input) &&
                is_array($this->input[$this->value]) && count($this->input[$this->value]) > 0);
        } else {
            $value = [];
            self::traverseData($this->value, $this->input, $value);
            return (is_array($value) && (count($value) > 0));
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
        if ($value === null) {
            $value = $this->value;
        }
        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "value") .
            " must contain at least one item" . (($value !== null) ? " of '" . $value . "'." : ".");

        return $this->message;
    }

}
