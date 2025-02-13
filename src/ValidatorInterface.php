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
 * Validator interface
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.1.3
 */
interface ValidatorInterface
{

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
     * Get the validator results
     *
     * @return mixed
     */
    public function getResults(): mixed;

    /**
     * Has validator results
     *
     * @return bool
     */
    public function hasResults(): bool;

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
     * Evaluate
     *
     * @param  mixed $input
     * @return bool
     */
    public function evaluate(mixed $input = null): bool;

}
