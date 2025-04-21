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
class HasOnlyOneThatEquals extends AbstractValidator
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

        if (is_array($this->value) && is_array($this->input)) {
            $field         = array_key_first($this->value);
            $requiredValue = reset($this->value);

            // Set the default message
            if ($this->message === null) {
                $this->message = 'The value must contain only one item' .
                    (($this->value !== null) ? " of '" . $field . "'" : '') . ' with the required value.';
            }

            if (!str_contains($field, '.')) {
                throw new Exception("Error: The evaluated value must been an array with a column name, e.g. 'users.username'.");
            } else {
                $parent = substr($field, 0, strrpos($field, '.'));
                $child  = substr($field, (strrpos($field, '.') + 1));
                $value  = [];
                $count  = 0;
                self::traverseData($parent, $this->input, $value);

                foreach ($value as $val) {
                    if (is_array($val)) {
                        foreach ($val as $item) {
                            if (is_array($item) && isset($item[$child]) && ($item[$child] === $requiredValue)) {
                                $count++;
                            }
                        }
                    }
                }

                $result = ($count == 1);
            }
        }

        // Set the fallback default message
        if ($this->message === null) {
            $this->message = 'The value must contain only one item with the required value.';
        }

        return $result;
    }

}
