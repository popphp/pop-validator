<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

/**
 * Between validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.0.0
 */
class Between extends AbstractValidator
{

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
     * @throws Exception
     * @return bool
     */
    public function evaluate(mixed $input = null): bool
    {
        if (!is_array($this->value)) {
            throw new Exception('The value must be an array.');
        } else if (count($this->value) != 2) {
            throw new Exception('The value must be an array that contains 2 values.');
        }

        // Set the input, if passed
        if ($input !== null) {
            $this->input = $input;
        }

        // Set the default message
        if ($this->message === null) {
            $this->message = 'The value must be between ' . $this->value[0] . ' and ' . $this->value[1] . '.';
        }

        return (($this->input > $this->value[0]) && ($this->input < $this->value[1]));
    }

}
