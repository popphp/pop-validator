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

/**
 * Has none validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    5.1.0
 */
class HasNone extends HasOne
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
        return !parent::evaluate($input);
    }

    /**
     * Generate default message
     *
     * @param  mixed $name
     * @param  mixed $value
     * @return string
     */
    public function generateDefaultMessage(mixed $name = null, mixed $value = null): string
    {
        if ($value === null) {
            $value = $this->value;
        }
        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "input") .
            " must not contain any items" . (($value !== null) ? " of '" . $value . "'." : ".");

        return $this->message;
    }

}
