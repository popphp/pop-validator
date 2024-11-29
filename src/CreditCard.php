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
 * Credit card validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.1.2
 */
class CreditCard extends AbstractValidator
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
            if (str_contains((string)$this->input, ' ')) {
                $this->input = str_replace(' ', '', $this->input);
            }
            if (str_contains((string)$this->input, '-')) {
                $this->input = str_replace('-', '', $this->input);
            }
        }

        // Set the default message
        if ($this->message === null) {
            $this->message = 'The value must be a valid credit card number.';
        }

        // Evaluate the input against the validator
        $nums   = str_split((string)$this->input);
        $check  = $nums[count($nums) - 1];
        $start  = count($nums) - 2;
        $sum    = 0;
        $double = true;

        for ($i = $start; $i >= 0; $i--) {
            if ($double) {
                $num = $nums[$i] * 2;
                if ($num > 9) {
                    $num = (int)substr($num, 0, 1) + (int)substr($num, 1, 1);
                }
                $sum += $num;
                $double = false;
            } else {
                $sum += $nums[$i];
                $double = true;
            }
        }

        $sum += $check;
        $rem = $sum % 10;

        return ($rem == 0);
    }

}
