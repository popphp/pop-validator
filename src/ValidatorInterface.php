<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

/**
 * Validator interface
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.6.5
 */
interface ValidatorInterface
{

    /**
     * Get the validator name
     *
     * @return ?string
     */
    public function getName(): ?string;

    /**
     * Get the validator description
     *
     * @return ?string
     */
    public function getDescription(): ?string;

    /**
     * Get the validator value
     *
     * @return mixed
     */
    public function getValue(): mixed;

    /**
     * Get the validator default message
     *
     * @return string|null
     */
    public function getMessage(): string|null;

    /**
     * Get the validator input
     *
     * @return mixed
     */
    public function getInput(): mixed;

    /**
     * Get the validator field
     *
     * @param  bool $parse
     * @return string|array|null
     */
    public function getField(bool $parse = true): string|array|null;

    /**
     * Get the validator key field value
     *
     * @return mixed
     */
    public function getKeyFieldValue(): mixed;

    /**
     * Get the validator results
     *
     * @return mixed
     */
    public function getResults(): mixed;

    /**
     * Has validator name
     *
     * @return bool
     */
    public function hasName(): bool;

    /**
     * Has validator description
     *
     * @return bool
     */
    public function hasDescription(): bool;

    /**
     * Has validator value
     *
     * @return bool
     */
    public function hasValue(): bool;

    /**
     * Has validator message
     *
     * @return bool
     */
    public function hasMessage(): bool;

    /**
     * Has validator input
     *
     * @return bool
     */
    public function hasInput(): bool;

    /**
     * Has validator field
     *
     * @return bool
     */
    public function hasField(): bool;

    /**
     * Has validator key field
     *
     * @return bool
     */
    public function hasKeyField(): bool;

    /**
     * Has validator results
     *
     * @return bool
     */
    public function hasResults(): bool;

    /**
     * Set the validator name
     *
     * @param  ?string $name
     * @return static
     */
    public function setName(?string $name = null): static;

    /**
     * Set the validator description
     *
     * @param  ?string $description
     * @return static
     */
    public function setDescription(?string $description = null): static;

    /**
     * Set the validator value
     *
     * @param  mixed $value
     * @return ValidatorInterface
     */
    public function setValue(mixed $value): ValidatorInterface;

    /**
     * Set the validator default message
     *
     * @param  ?string $message
     * @return ValidatorInterface
     */
    public function setMessage(?string $message = null): ValidatorInterface;

    /**
     * Set the validator input
     *
     * @param  mixed $input
     * @return ValidatorInterface
     */
    public function setInput(mixed $input = null): ValidatorInterface;

    /**
     * Set the validator field
     *
     * @param  ?string $field
     * @return AbstractValidator
     */
    public function setField(?string $field = null): ValidatorInterface;

    /**
     * Evaluate
     *
     * @param  mixed $input
     * @return bool
     */
    public function evaluate(mixed $input = null): bool;

    /**
     * Generate default message

     * @param  mixed $name
     * @param  mixed $value
     * @return string
     */
    public function generateDefaultMessage(mixed $name = null, mixed $value = null): string;

}
