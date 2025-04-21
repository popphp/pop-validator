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
 * Has one validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class HasOne extends AbstractValidator
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
            $this->message = 'The value must contain at least one item' .
                (($this->value !== null) ? " of '" . $this->value . "'." : '.');
        }

        $result = false;

        // Simple array value count
        if (($this->value === null) && is_array($this->input)) {
            $result = (count($this->input) > 0);
        // Check node of array
        } else if (is_string($this->value) && is_array($this->input)) {
            if (!str_contains($this->value, '.')) {
                $result = (array_key_exists($this->value, $this->input) &&
                    is_array($this->input[$this->value]) && count($this->input[$this->value]) > 0);
            } else {
                $value = [];
                ValidatorSet::traverseData($this->value, $this->input, $value);
                $result = (is_array($value) && (count($value) > 0));
            }
        }

        return $result;
    }

}
