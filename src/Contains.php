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
 * Contains validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.1.2
 */
class Contains extends AbstractValidator
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
        if ($this->message === null) {
            $this->message = 'The input must be contained in the value.';
        }

        $result   = false;
        $needle   = $this->value;
        $haystack = $this->input;

        if (is_string($needle) && is_string($haystack)) {
            $result = (str_contains($haystack, $needle));
        } else if (!is_array($needle) && is_array($haystack)) {
            $result = in_array($needle, $haystack);
        } else if (is_array($needle)) {
            $result = true;
            foreach ($needle as $n) {
                if (is_array($haystack)) {
                    if (!in_array($n, $haystack)) {
                        $result = false;
                        break;
                    }
                } else if (!str_contains((string)$haystack, $n)) {
                    $result = false;
                    break;
                }
            }
        }

        return $result;
    }

}
