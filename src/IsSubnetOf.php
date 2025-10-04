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
 * Is subnet of validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class IsSubnetOf extends AbstractValidator
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
        // Check to make sure the input is a valid Ipv4 address.
        if (!(new Ipv4())->evaluate($input)) {
            throw new Exception('The IP address must be a valid IPv4 address.');
        }

        // Set the input, if passed
        if ($input !== null) {
            $this->input = $input;
        }

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        return (substr((string)$this->input, 0, strrpos((string)$this->input, '.')) == $this->value);
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
            " must be part of the subnet '" . ($value ?? $this->value) . "'.";

        return $this->message;
    }

}
