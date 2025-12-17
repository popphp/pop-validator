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
 * Has one not empty validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
class HasOneNotEmpty extends AbstractValidator
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

        $field = $this->value;

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        if (!str_contains($field, '.') && (!$this->hasField())) {
            return (array_key_exists($field, $this->input) &&
                !is_array($this->input[$field]) && !empty($this->input[$field]));
        } else if ($this->hasKeyField()) {
            ['key' => $key, 'field' => $field] = $this->getField();
            return (array_key_exists($key, $this->input) && array_key_exists($field, $this->input[$key]) &&
                !empty($this->input[$key][$field]));
        } else {
            $value = [];
            self::traverseData($field, $this->input, $value);

            if (is_array($value)) {
                foreach ($value as $val) {
                    if (!empty($val)) {
                        return true;
                    }
                }
                return false;
            } else {
                return !empty($value);
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
        $field = $value ?? $this->value;

        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "input") .
            " must contain one item" . (($this->value !== null) ? " of '" . $field . "'" : "") .
            " that is not empty.";

        return $this->message;
    }

}
