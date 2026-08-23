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
 * RegEx validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    5.0.0
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
     * @return static
     */
    public function setNumberToSatisfy(int $numberToSatisfy): static
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
        if (!$this->hasMessage()) {
            $this->generateDefaultMessage();
        }

        $inputValue = ($this->hasKeyField()) ? $this->getKeyFieldValue() : $this->input;

        if (is_array($this->value)) {
            if ((int)$this->numberToSatisfy == 0) {
                $result = true;
                foreach ($this->value as $value) {
                    if (!preg_match($value, (string)$inputValue)) {
                        $result = false;
                        break;
                    }
                }
                return $result;
            } else {
                $satisfied = 0;
                foreach ($this->value as $value) {
                    $satisfied += (int)preg_match($value, (string)$inputValue);
                }
                return ($satisfied >= (int)$this->numberToSatisfy);
            }
        } else {
            return (bool)(preg_match($this->value, (string)$inputValue));
        }
    }

    /**
     * Generate default message

     * @param  mixed $name
     * @param  mixed $value
     * @return string
     */
    public function generateDefaultMessage(mixed $name = null, mixed $value = null): string
    {
        $this->message = "The " . (($name !== null) ? "'" . $name . "'" : "input") . " format is not correct.";
        return $this->message;
    }

}
