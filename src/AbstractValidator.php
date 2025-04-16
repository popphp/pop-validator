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
 * @version    4.1.3
 */
abstract class AbstractValidator implements ValidatorInterface
{

    /**
     * Validator name
     * @var ?string
     */
    protected mixed $name = null;

    /**
     * Validator description
     * @var ?string
     */
    protected mixed $description = null;

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
     * @param  ?string $name
     * @param  ?string $description
     */
    public function __construct(mixed $value = null, ?string $message = null, ?string $name = null, ?string $description = null)
    {
        $this->setValue($value);
        if ($message !== null) {
            $this->setMessage($message);
        }
        if ($name !== null) {
            $this->setName($name);
        }
        if ($description !== null) {
            $this->setDescription($description);
        }
    }

    /**
     * Get the validator name
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the validator description
     *
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
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
     * Has validator name
     *
     * @return bool
     */
    public function hasName(): bool
    {
        return ($this->name !== null);
    }

    /**
     * Has validator description
     *
     * @return bool
     */
    public function hasDescription(): bool
    {
        return ($this->description !== null);
    }

    /**
     * Has validator value
     *
     * @return bool
     */
    public function hasValue(): bool
    {
        return ($this->value !== null);
    }

    /**
     * Has validator message
     *
     * @return bool
     */
    public function hasMessage(): bool
    {
        return ($this->message !== null);
    }

    /**
     * Has validator input
     *
     * @return bool
     */
    public function hasInput(): bool
    {
        return ($this->input !== null);
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
     * Set the validator name
     *
     * @param  ?string $name
     * @return static
     */
    public function setName(?string $name = null): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the validator description
     *
     * @param  ?string $description
     * @return static
     */
    public function setDescription(?string $description = null): static
    {
        $this->description = $description;
        return $this;
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
