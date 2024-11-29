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
 * Validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.1.2
 */
abstract class AbstractValidator implements ValidatorInterface
{

    /**
     * Validator value to test against
     * @var mixed
     */
    protected mixed $value = null;

    /**
     * Input value to test
     * @var mixed
     */
    protected mixed $input = null;

    /**
     * Validator message
     *  - The message provided when the validation fails
     * @var ?string
     */
    protected ?string $message = null;

    /**
     * Validator results
     *  - Optional results to collect post-validation, would be something that was
     *    set by a custom validator in its "evaluate" method
     * @var mixed
     */
    protected mixed $results = null;

    /**
     * Constructor
     *
     * Instantiate the validator object
     *
     * @param  mixed   $value
     * @param  ?string $message
     */
    public function __construct(mixed $value = null, ?string $message = null)
    {
        $this->setValue($value);
        if ($message !== null) {
            $this->setMessage($message);
        }
    }

    /**
     * Get the validator value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Get the validator default message
     *
     * @return string|null
     */
    public function getMessage(): string|null
    {
        return $this->message;
    }

    /**
     * GEt the validator input
     *
     * @return mixed
     */
    public function getInput(): mixed
    {
        return $this->input;
    }

    /**
     * Get the validator results
     *
     * @return mixed
     */
    public function getResults(): mixed
    {
        return $this->results;
    }

    /**
     * Has validator results
     *
     * @return bool
     */
    public function hasResults(): bool
    {
        return !empty($this->results);
    }

    /**
     * Set the validator value
     *
     * @param  mixed $value
     * @return AbstractValidator
     */
    public function setValue(mixed $value): AbstractValidator
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Set the validator condition
     *
     * @param  ?string $message
     * @return AbstractValidator
     */
    public function setMessage(?string $message = null): AbstractValidator
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set the validator input
     *
     * @param  mixed $input
     * @return AbstractValidator
     */
    public function setInput(mixed $input = null): AbstractValidator
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Evaluate

     * @param  mixed $input
     * @return bool
     */
    abstract public function evaluate(mixed $input = null): bool;

}
