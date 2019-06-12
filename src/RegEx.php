<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

/**
 * RegEx validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2019 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    3.0.2
 */
class RegEx extends AbstractValidator
{

    /**
     * Number of regex's that need to be satisgied
     * @var int
     */
    protected $numberToSatisfy = null;

    /**
     * Constructor
     *
     * Instantiate the validator object
     *
     * @param  mixed  $value
     * @param  string $message
     * @param  int    $numberToSatisfy
     */
    public function __construct($value = null, $message = null, $numberToSatisfy = null)
    {
        parent::__construct($value, $message);

        if (null !== $numberToSatisfy) {
            $this->setNumberToSatisfy($numberToSatisfy);
        }
    }

    /**
     * Get the number to satisfy
     *
     * @return int
     */
    public function getNumberToSatisfy()
    {
        return $this->numberToSatisfy;
    }

    /**
     * Set the number to satisfy
     *
     * @param  int $numberToSatisfy
     * @return RegEx
     */
    public function setNumberToSatisfy($numberToSatisfy)
    {
        $this->numberToSatisfy = (int)$numberToSatisfy;
        return $this;
    }

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
            $this->message = 'The format is not correct.';
        }

        if (is_array($this->value)) {
            if ((int)$this->numberToSatisfy == 0) {
                $result = true;
                foreach ($this->value as $value) {
                    if (!preg_match($value, $this->input)) {
                        $result = false;
                        break;
                    }
                }
                return $result;
            } else {
                $satisfied = 0;
                foreach ($this->value as $value) {
                    $satisfied += (int)preg_match($value, $this->input);
                }
                return ($satisfied >= (int)$this->numberToSatisfy);
            }
        } else {
            return (bool)(preg_match($this->value, $this->input));
        }
    }

}
