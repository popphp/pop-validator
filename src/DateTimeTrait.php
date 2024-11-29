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

use Pop\Utils;

/**
 * Date-time greater than validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.1.2
 */
trait DateTimeTrait
{

    use Utils\DateTimeTrait;

    /**
     * Get the validator value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        if (!empty($this->dateTimeFormat)) {
            if (is_array($this->value)) {
                $values = $this->value;
                foreach ($values as $key => $value) {
                    $values[$key] = date($this->dateTimeFormat, $value);
                }
                return $values;
            } else {
                return date($this->dateTimeFormat, $this->value);
            }
        } else {
            return $this->value;
        }
    }

    /**
     * Set the validator value
     *
     * @param  mixed $value
     * @return static
     */
    public function setValue(mixed $value): static
    {
        if ($value !== null) {
            if (is_array($value)) {
                $this->detectFormat($value[0]);
                $value = array_map('strtotime', $value);
            } else {
                $this->detectFormat($value);
                if ($this->dateTimeFormat !== null) {
                    $value = strtotime($value);
                }
            }
        }
        return parent::setValue($value);
    }

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
        return parent::evaluate($input);
    }

}
