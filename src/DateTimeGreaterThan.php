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
 * Date-time greater than validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    5.0.0
 */
class DateTimeGreaterThan extends GreaterThan
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
            $this->generateDefaultMessage(null, $this->getValue());
        }

        return parent::evaluate($input);
    }

}
