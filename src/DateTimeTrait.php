<?php
declare(strict_types=1);
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

use Pop\Utils;

/**
 * Date-time trait
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    5.0.0
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
                } else if (strtotime($value) !== false) {
                    $value = strtotime($value);
                }
            }
        }
        return parent::setValue($value);
    }

}
