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
     * Operator
     * @var ?string
     */
    protected ?string $operator = null;

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
     * @param ?string $operator
     * @param mixed   $value
     */
    public function __construct(?string $field = null, ?string $operator = null, mixed $value = null, string $prefix = 'Pop\Validator\\')
    {
        if ($field !== null) {
            $this->setField($field);
        }
        if ($operator !== null) {
            $this->setOperator($operator);
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
        ['field' => $field, 'operator' => $operator, 'value' => $value] = Validator::parseRule($rule, $prefix);
        return new static($field, $operator, $value, $prefix);
    }

    /**
     * Create condition
     *
     * @param  ?string $field
     * @param  ?string $operator
     * @param  mixed $value
     * @return Condition
     */
    public static function create(?string $field = null, ?string $operator = null, mixed $value = null, string $prefix = 'Pop\Validator\\'): Condition
    {
        return new static($field, $operator, $value);
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
     * Set the condition operator
     *
     * @param  ?string $operator
     * @return static
     */
    public function setOperator(?string $operator = null): static
    {
        $this->operator = $operator;
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
     * Get the condition operator
     *
     * @return ?string
     */
    public function getOperator(): ?string
    {
        return $this->operator;
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
     * Has the condition operator
     *
     * @return bool
     */
    public function hasOperator(): bool
    {
        return ($this->operator !== null);
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
     * @return bool
     */
    public function evaluate(array $input): bool
    {
        if (!class_exists($this->prefix . $this->operator)) {
            throw new Exception('Error: The condition class does not exist.');
        }

        $result   = false;
        $class     = $this->prefix . $this->operator;
        $validator = new $class($this->value);
        if (!str_contains($this->field, '.') && isset($input[$this->field])) {
            $result    = $validator->evaluate($input[$this->field]);
        } else {
            $value = [];
            Validator::traverseData($this->field, $input, $value);
            $result  = $validator->evaluate($value);
        }

        return $result;
    }

}
