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
 * Date-time between/include validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
class DateTimeBetweenInclude extends BetweenInclude
{

    /**
     * Traits
     */
    use DateTimeTrait;

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
     * @return bool
     */
    public function evaluate(mixed $input = null): bool
    {
        if ($input !== null) {
            $input = strtotime($input);
        }

        // Set the default message
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        return parent::evaluate($input);
    }

    /**
     * Generate default message

     * @param  mixed  $name
     * @param  mixed  $value
     * @return string
     */
    public function generateDefaultMessage(mixed $name = null, mixed $value = null): string
    {
        $value1 = null;
        $value2 = null;

        if (($this->value !== null) && is_array($this->value) && (count($this->value) == 2)) {
            $value1 = $this->value[0];
            $value2 = $this->value[1];

            if (!empty($this->dateTimeFormat)) {
                if (is_numeric($value1)) {
                    $value1 = date($this->dateTimeFormat, $value1);
                }
                if (is_numeric($value2)) {
                    $value1 = date($this->dateTimeFormat, $value2);
                }
            }
        }

        return parent::generateDefaultMessage($name, [$value1, $value2]);
    }

}
