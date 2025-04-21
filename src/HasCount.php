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
 * Has count validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class HasCount extends AbstractValidator
{

    /**
     * Traits
     */
    use HasTrait;

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

        $result = false;

        // Simple array value count
        if (is_array($this->input)) {
            if (is_numeric($this->value)) {
                // Set the default message
                if ($this->message === null) {
                    $this->message = 'The array must have ' . $this->value . ' item(s).';
                }

                if (!is_array($input)) {
                    throw new Exception('Error: The evaluated input must be an array.');
                }

                $result = (count($this->input) == $this->value);
            // Check node of array
            } else if (is_array($this->value)) {
                $field = array_key_first($this->value);
                $count = reset($this->value);

                if ($this->message === null) {
                    $this->message = "The array must have a field '" . $field . "' with " . $count . " item(s).";
                }

                if (!str_contains($field, '.')) {
                    $result = (array_key_exists($field, $this->input) &&
                        is_array($this->input[$field]) && count($this->input[$field]) == $count);
                } else {
                    $value = [];
                    self::traverseData($field, $this->input, $value);

                    $result = (is_array($value) && (isset($value[0])) && (count($value[0]) == $count));
                }
            }
        }

        return $result;
    }

}
