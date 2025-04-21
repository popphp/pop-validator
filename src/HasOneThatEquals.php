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
 * Has one that equals validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class HasOneThatEquals extends AbstractValidator
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

        $result = false;

        if (is_array($this->value) && is_array($this->input)) {
            $field         = array_key_first($this->value);
            $requiredValue = reset($this->value);

            // Set the default message
            if ($this->message === null) {
                $this->message = 'The value must contain one item' . (($this->value !== null) ?
                        " of '" . $field . "'" : '') . ' with the required value.';
            }

            if (!str_contains($field, '.')) {
                $result = (array_key_exists($field, $this->input) &&
                    is_array($this->input[$field]) && ($this->input[$field] == $requiredValue));
            } else {
                $value = [];
                ValidatorSet::traverseData($field, $this->input, $value);
                $result = ((is_array($value) && in_array($requiredValue, $value)) || ($value == $requiredValue));
            }
        }

        // Set the fallback default message
        if ($this->message === null) {
            $this->message = 'The value must contain one item with the required value.';
        }

        return $result;
    }

}
