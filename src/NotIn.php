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
 * Not in validator class (alias class)
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class NotIn extends AbstractValidator
{

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
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

        $result   = false;
        $needle   = $this->input;
        $haystack = $this->value;

        if (!is_array($needle) && !is_array($haystack)) {
            $result = (!str_contains($haystack, $needle));
        } else if (!is_array($needle) && is_array($haystack)) {
            $result = (!in_array($needle, $haystack));
        } else if (is_array($needle)) {
            if (is_array($haystack)) {
                $result = (array_intersect($needle, $haystack) != $needle);
            } else {
                $result = true;
                foreach ($needle as $n) {
                    if (str_contains((string)$haystack, $n)) {
                        $result = false;
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Generate default message

     * @param  mixed $name
     * @param  mixed $value
     * @return string
     */
    public function generateDefaultMessage(mixed $name = null, mixed $value = null): string
    {
        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "value") .
            " must not be contained in the value.";

        return $this->message;
    }

}
