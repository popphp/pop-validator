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
 * Has only one that contains than validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
class HasOnlyOneThatContains extends AbstractValidator
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
        $count         = 0;

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        if (!str_contains($field, '.')) {
            $value = (array_key_exists($field, $this->input) && !is_array($this->input[$field])) ? $this->input[$field] : null;
        } else {
            $value = [];
            self::traverseData($field, $this->input, $value);
        }

        if (is_array($value)) {
            foreach ($value as $val) {
                $needle   = $requiredValue;
                $haystack = $val;

                if (!is_array($needle) && !is_array($haystack) && str_contains($haystack, $needle)) {
                    $count++;
                } else if (!is_array($needle) && is_array($haystack) && in_array($needle, $haystack)) {
                    $count++;
                } else if (is_array($needle)) {
                    foreach ($needle as $n) {
                        if (is_array($haystack)) {
                            if (in_array($n, $haystack)) {
                                $count++;
                            }
                        } else if (str_contains((string)$haystack, $n)) {
                            $count++;
                        }
                    }
                }
            }
        } else {
            $needle   = $requiredValue;
            $haystack = $value;

            if (!is_array($needle) && !is_array($haystack) && str_contains($haystack, $needle)) {
                $count++;
            } else if (!is_array($needle) && is_array($haystack) && in_array($needle, $haystack)) {
                $count++;
            } else if (is_array($needle)) {
                foreach ($needle as $n) {
                    if (is_array($haystack)) {
                        if (in_array($n, $haystack)) {
                            $count++;
                        }
                    } else if (str_contains((string)$haystack, $n)) {
                        $count++;
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
            " must have only one item" . (($this->value !== null) ? " of '" . $field . "'" : "") .
            " that contains the required value.";

        return $this->message;
    }

}
