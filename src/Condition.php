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
 * Has one that equals validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class Condition
{

    /**
     * Field
     * @var ?string
     */
    protected ?string $field = null;

    /**
     * Validator
     * @var ?string
     */
    protected ?string $validator = null;

    /**
     * Value
     * @var mixed
     */
    protected mixed $value = null;

    /**
     * Prefix
     * @var ?string
     */
    protected ?string $prefix = null;

    /**
     * Constructor
     *
     * Instantiate the condition object
     *
     * @param ?string $field
     * @param ?string $validator
     * @param mixed   $value
     */
    public function __construct(?string $field = null, ?string $validator = null, mixed $value = null, string $prefix = 'Pop\Validator\\')
    {
        if ($field !== null) {
            $this->setField($field);
        }
        if ($validator !== null) {
            $this->setValidator($validator);
        }
        if ($value !== null) {
            $this->setValue($value);
        }
        if ($prefix !== null) {
            $this->setPrefix($prefix);
        }
    }

    /**
     * Create condition from rule
     *
     * @param  string $rule
     * @return Condition
     */
    public static function createFromRule(string $rule, string $prefix = 'Pop\Validator\\'): Condition
    {
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Rule::parse($rule, $prefix);
        return new static($field, $validator, $value, $prefix);
    }

    /**
     * Create condition
     *
     * @param  ?string $field
     * @param  ?string $validator
     * @param  mixed $value
     * @return Condition
     */
    public static function create(
        ?string $field = null, ?string $validator = null, mixed $value = null, string $prefix = 'Pop\Validator\\'
    ): Condition
    {
        return new static($field, $validator, $value);
    }

    /**
     * Set the condition field
     *
     * @param  ?string $field
     * @return static
     */
    public function setField(?string $field = null): static
    {
        $this->field = $field;
        return $this;
    }

    /**
     * Set the condition validator
     *
     * @param  ?string $validator
     * @return static
     */
    public function setValidator(?string $validator = null): static
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Set the condition value
     *
     * @param  mixed $value
     * @return static
     */
    public function setValue(mixed $value = null): static
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Set the condition prefix
     *
     * @param  ?string $prefix
     * @return static
     */
    public function setPrefix(?string $prefix = null): static
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Get the condition field
     *
     * @return ?string
     */
    public function getField(): ?string
    {
        return $this->field;
    }

    /**
     * Get the condition validator
     *
     * @return ?string
     */
    public function getValidator(): ?string
    {
        return $this->validator;
    }

    /**
     * Get the condition value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Get the condition prefix
     *
     * @return ?string
     */
    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * Has the condition field
     *
     * @return bool
     */
    public function hasField(): bool
    {
        return ($this->field !== null);
    }

    /**
     * Has the condition validator
     *
     * @return bool
     */
    public function hasValidator(): bool
    {
        return ($this->validator !== null);
    }

    /**
     * Has the condition value
     *
     * @return bool
     */
    public function hasValue(): bool
    {
        return ($this->value !== null);
    }

    /**
     * Has the condition prefix
     *
     * @return bool
     */
    public function hasPrefix(): bool
    {
        return ($this->prefix !== null);
    }

    /**
     * Evaluate the condition
     *
     * @param  mixed $input
     * @throws Exception
     * @return bool
     */
    public function evaluate(array $input): bool
    {
        if (!class_exists($this->prefix . $this->validator)) {
            throw new Exception('Error: The condition class does not exist.');
        }

        if (!str_contains($this->field, '.') && !array_key_exists($this->field, $input))  {
            throw new Exception("Error: The input data does not contain a '" . $this->field . "' field value.");
        }

        // If the value references a value in the input array
        if (is_string($this->value) && array_key_exists($this->value, $input)) {
            $this->value = $input[$this->value];
        }

        $class     = $this->prefix . $this->validator;
        $validator = (str_starts_with($this->validator, 'Has')) ? new $class([$this->field => $this->value]) : new $class($this->value);
        $value     = (str_contains($this->field, '.')) ? $input : $input[$this->field];

        return $validator->evaluate($value);
    }

}
