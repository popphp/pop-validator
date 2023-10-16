<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
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
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.0.0
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
     * @param  mixed   $value
     * @param  ?string $message
     * @param  ?int    $numberToSatisfy
     */
    public function __construct(mixed $value = null, ?string $message = null, ?int $numberToSatisfy = null)
    {
        parent::__construct($value, $message);

        if ($numberToSatisfy !== null) {
            $this->setNumberToSatisfy($numberToSatisfy);
        }
    }

    /**
     * Get the number to satisfy
     *
     * @return int|null
     */
    public function getNumberToSatisfy(): int|null
    {
        return $this->numberToSatisfy;
    }

    /**
     * Set the number to satisfy
     *
     * @param  int $numberToSatisfy
     * @return RegEx
     */
    public function setNumberToSatisfy(int $numberToSatisfy): Regex
    {
        $this->numberToSatisfy = $numberToSatisfy;
        return $this;
    }

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
        }

        // Set the default message
        if ($this->message === null) {
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
