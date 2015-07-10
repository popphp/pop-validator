<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp
 * @category   Pop
 * @package    Pop_Validator
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

/**
 * Included validator class
 *
 * @category   Pop
 * @package    Pop_Validator
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2015 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class Included extends AbstractValidator
{

    /**
     * Method to evaluate the validator
     *
     * @param  mixed $input
     * @return boolean
     */
    public function evaluate($input = null)
    {
        // Set the input, if passed
        if (null !== $input) {
            $this->input = $input;
        }

        // Set the default message
        if (null === $this->message) {
            $this->message = 'The value must be included.';
        }

        // If input check is an array
        if (is_array($this->input)) {
            if (!is_array($this->value)) {
                $this->value = [$this->value];
            }
            $result = true;
            foreach ($this->input as $input) {
                if (!in_array($input, $this->value)) {
                    $result = false;
                }
            }
        // Else, if input check is a string
        } else {
            $result = (is_array($this->value)) ?
                (in_array($this->input, $this->value)) :
                (strpos((string)$this->value, (string)$this->input) !== false);
        }

        return $result;
    }

}
