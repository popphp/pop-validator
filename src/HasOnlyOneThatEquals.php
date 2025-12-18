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
 * Has one that equals validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
class HasOnlyOneThatEquals extends AbstractValidator
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

        $result = false;

        if (!is_array($input)) {
            throw new Exception('Error: The evaluated input must be an array.');
        }
        if (!is_array($this->value)) {
            throw new Exception("Error: The evaluated value must be an array of node name and value, e.g. ['node.name' => 3].");
        }

        $field         = array_key_first($this->value);
        $requiredValue = reset($this->value);

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        if (!str_contains($field, '.')) {
            throw new Exception("Error: The evaluated value must been an array with a column name, e.g. 'users.username'.");
        }

        $parent = substr($field, 0, strrpos($field, '.'));
        $child  = substr($field, (strrpos($field, '.') + 1));
        $value  = [];
        $count  = 0;
        self::traverseData($parent, $this->input, $value);

        foreach ($value as $val) {
            if (is_array($val)) {
                foreach ($val as $item) {
                    if (is_array($item) && isset($item[$child])) {
                        if ($isDateTime) {
                            if (strtotime($item[$child]) == strtotime($requiredValue)) {
                                $count++;
                            }
                        } else {
                            if ($item[$child] == $requiredValue) {
                                $count++;
                            }
                        }
                    }
                }
            }
        }

        return ($count == 1);
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
            " must contain only one item" . (($this->value !== null) ? " of '" . $field . "'" : "") .
            " with the required value.";

        return $this->message;
    }

}
