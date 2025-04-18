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
 * Ends with validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class EndsWith extends AbstractValidator
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
            $this->message = 'The input must start with the value.';
        }

        return str_ends_with($this->input, $this->value);
    }

}
