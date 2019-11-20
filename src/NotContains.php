<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2020 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

/**
 * Does not contain validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2020 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.1.2
 */
class NotContains extends AbstractValidator
{

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
     * @return boolean
     */
    public function evaluate($input = null)
    {
        // Set the input, if passed
        if (null !== $input) {
            $this->input = $input;
        }

        // Set the default message
        if (null === $this->message) {
            $this->message = 'The input must be not contained in the value.';
        }

        $result   = false;
        $needle   = $this->value;
        $haystack = $this->input;

        if (is_string($needle) && is_string($haystack)) {
            $result = (strpos($haystack, $needle) === false);
        } else if (!is_array($needle) && is_array($haystack)) {
            $result = (!in_array($needle, $haystack));
        } else if (is_array($needle)) {
            $result = true;
            foreach ($needle as $n) {
                if (is_array($haystack)) {
                    if (in_array($n, $haystack)) {
                        $result = false;
                        break;
                    }
                } else if (strpos($haystack, $n) !== false) {
                    $result = false;
                    break;
                }
            }
        }

        return $result;
    }

}
