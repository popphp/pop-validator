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
 * Required validator class (alias class)
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
class Required extends AbstractValidator
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
        if (empty($this->value) && (!$this->hasField())) {
            throw new Exception('Error: The evaluated value cannot be empty.');
        }

        if (!str_contains($this->value, '.') && (!$this->hasField())) {
            return (array_key_exists($this->value, $this->input));
        } else if ($this->hasKeyField()) {
            ['key' => $key, 'field' => $field] = $this->getField();
            return (array_key_exists($key, $this->input) && array_key_exists($field, $this->input[$key]));
        } else {
            $parentValues = [];
            $childValues  = [];

            self::traverseData(substr($this->value, 0, strrpos($this->value, '.')), $this->input, $parentValues);
            self::traverseData($this->value, $this->input, $childValues);

            if (isset($parentValues[0][0])) {
                $parentValues = $parentValues[0];
            }
            return (is_array($parentValues) && is_array($childValues) && (count($parentValues) == count($childValues)));
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
        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "input") . " is required.";
        return $this->message;
    }

}
