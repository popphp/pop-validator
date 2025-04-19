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

use Pop\Utils\Arr;
use Pop\Utils\Str;

/**
 * Validator class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class Validator
{

    /**
     * Validators
     * @var array
     */
    protected array $validators = [];

    /**
     * Conditions
     * @var array
     */
    protected array $conditions = [];

    /**
     * Errors
     * @var array
     */
    protected array $errors = [];

    /**
     * Strict flag
     * @var bool
     */
    protected bool $strict = true;

    /**
     * Constructor
     *
     * Instantiate the validator object
     *
     * @param mixed   $validators
     * @param mixed   $conditions
     * @param ?string $field
     * @param bool    $strict
     */
    public function __construct(mixed $validators = null, mixed $conditions = null, ?string $field = null, bool $strict = true)
    {
        if ($validators !== null) {
            if ($field !== null) {
                if (is_array($validators)) {
                    $this->addValidatorsToField($field, $validators);
                } else if ($validators instanceof AbstractValidator) {
                    $this->addValidator($field, $validators);
                }
            } else {
                if (is_array($validators)) {
                    foreach ($validators as $field => $validator) {
                        if (is_array($validator)) {
                            $this->addValidatorsToField($field, $validator);
                        } else if ($validator instanceof AbstractValidator) {
                            $this->addValidator($field, $validator);
                        }
                    }
                } else {
                    throw new \InvalidArgumentException('Error: Validators must be an array or a class with a field parameter.');
                }
            }
        }

        if ($conditions !== null) {
            if ($field !== null) {
                if (is_array($conditions)) {
                    $this->addConditionsToField($field, $conditions);
                } else if ($conditions instanceof Condition) {
                    $this->addCondition($field, $conditions);
                }
            } else {
                if (is_array($conditions)) {
                    foreach ($conditions as $field => $condition) {
                        if (is_array($condition)) {
                            $this->addConditionsToField($field, $condition);
                        } else if ($condition instanceof Condition) {
                            $this->addCondition($field, $condition);
                        }
                    }
                } else {
                    throw new \InvalidArgumentException('Error: Conditions must be an array or a class with a field parameter.');
                }
            }
        }

        $this->setStrict($strict);
    }

    /**
     * Create validator set
     *
     * @param  mixed   $validators
     * @param  mixed   $conditions
     * @param  ?string $field
     * @param  bool    $strict
     * @return Validator
     */
    public static function create(mixed $validators = null, mixed $conditions = null, ?string $field = null, bool $strict = true): Validator
    {
        return new static($validators, $conditions, $field, $strict);
    }

    /**
     * Create validator set from rules
     *
     * @param  array|string $rules
     * @param  bool         $strict
     * @param  string       $prefix
     * @return Validator
     */
    public static function createFromRules(array|string $rules, bool $strict = true, string $prefix = 'Pop\Validator\\'): Validator
    {
        $validator = new static();
        $validator->setStrict($strict);

        $rules = Arr::make($rules);

        foreach ($rules as $rule) {
            ['field' => $field, 'operator' => $operator, 'value' => $value] = Validator::parseRule($rule, $prefix);

            if (class_exists($prefix . $operator)) {
                $class = new $prefix . $operator;
                $validator->addValidator($field, new $class($value));
            }
        }

        return $validator;
    }

    /**
     * Add validator
     *
     * @param  string             $field
     * @param  AbstractValidator $validator
     * @return Validator
     */
    public function addValidator(string $field, AbstractValidator $validator): Validator
    {
        if (!isset($this->validators[$field])) {
            $this->validators[$field] = [];
        }
        $this->validators[$field][] = $validator;
        return $this;
    }

    /**
     * Add validators to specific field
     *
     * @param string $field
     * @param  array $validators
     * @return Validator
     */
    public function addValidatorsToField(string $field, array $validators): Validator
    {
        foreach ($validators as $validator) {
            $this->addValidator($field, $validator);
        }
        return $this;
    }

    /**
     * Add validators to specific field
     *
     * @param  array $validators
     * @return Validator
     */
    public function addValidators(array $validators): Validator
    {
        foreach ($validators as $field => $validator) {
            $this->addValidator($field, $validator);
        }
        return $this;
    }

    /**
     * Get validators
     *
     * @param  ?string $field
     * @return array
     */
    public function getValidators(?string $field = null): array
    {
        if ($field !== null) {
            return (isset($this->validators[$field])) ? $this->validators[$field] : [];
        } else {
            return $this->validators;
        }
    }

    /**
     * Has validators
     *
     * @param  ?string $field
     * @return bool
     */
    public function hasValidators(?string $field = null): bool
    {
        if ($field !== null) {
            return (isset($this->validators[$field]));
        } else {
            return (!empty($this->validators));
        }
    }

    /**
     * Add condition
     *
     * @param  string    $field
     * @param  Condition $condition
     * @return Validator
     */
    public function addCondition(string $field, Condition $condition): Validator
    {
        if (!isset($this->conditions[$field])) {
            $this->conditions[$field] = [];
        }
        $this->conditions[$field][] = $condition;
        return $this;
    }

    /**
     * Add conditions to specific field
     *
     * @param  string $field
     * @param  array $conditions
     * @return Validator
     */
    public function addConditionsToField(string $field, array $conditions): Validator
    {
        foreach ($conditions as $condition) {
            $this->addCondition($field, $condition);
        }
        return $this;
    }

    /**
     * Add conditions to specific field
     *
     * @param  array $conditions
     * @return Validator
     */
    public function addConditions(array $conditions): Validator
    {
        foreach ($conditions as $field => $condition) {
            $this->addCondition($field, $condition);
        }
        return $this;
    }

    /**
     * Get conditions
     *
     * @param  ?string $field
     * @return array
     */
    public function getConditions(?string $field = null): array
    {
        if ($field !== null) {
            return (isset($this->conditions[$field])) ? $this->conditions[$field] : [];
        } else {
            return $this->conditions;
        }
    }

    /**
     * Has conditions
     *
     * @param  ?string $field
     * @return bool
     */
    public function hasConditions(?string $field = null): bool
    {
        if ($field !== null) {
            return (isset($this->conditions[$field]));
        } else {
            return (!empty($this->conditions));
        }
    }

    /**
     * Add error
     *
     * @param  string $field
     * @param  string $error
     * @return Validator
     */
    public function addError(string $field, string $error): Validator
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $error;
        return $this;
    }

    /**
     * Get errors
     *
     * @param  ?string $field
     * @return array
     */
    public function getErrors(?string $field = null): array
    {
        if ($field !== null) {
            return (isset($this->errors[$field])) ? $this->errors[$field] : [];
        } else {
            return $this->errors;
        }
    }

    /**
     * Has errors
     *
     * @param  ?string $field
     * @return bool
     */
    public function hasErrors(?string $field = null): bool
    {
        if ($field !== null) {
            return (isset($this->errors[$field]));
        } else {
            return (!empty($this->errors));
        }
    }

    /**
     * Set strict
     *
     * @param  bool $strict
     * @return Validator
     */
    public function setStrict(bool $strict = true): Validator
    {
        $this->strict = $strict;
        return $this;
    }

    /**
     * Is strict
     *
     * @return bool
     */
    public function isStrict(): bool
    {
        return $this->strict;
    }

    /**
     * Evaluate all validators over the provided input data
     *
     * @param  mixed $input
     * @return bool
     */
    public function evaluate(array $input): bool
    {
        foreach ($this->validators as $field => $validators) {
            foreach ($validators as $validator) {
                $result = (isset($input[$field])) ? $validator->evaluate($input[$field]) : $validator->evaluate();
                if (!$result) {
                    $this->addError($field, $validator->getMessage());
                }
            }
        }

        return (!$this->hasErrors());
    }

    /**
     * Parse rule
     *
     * @param  string  $rule
     * @param  ?string $prefix
     * @throws \InvalidArgumentException
     * @return array
     */
    public static function parseRule(string $rule, ?string $prefix = 'Pop\Validator\\'): array
    {
        $ruleSet = explode(':', $rule);

        if (count($ruleSet) < 2) {
            throw new \InvalidArgumentException('Error: The rule is invalid. It must have at least a field and an operator, e.g. user_id:equals:1.');
        }

        $operator = Str::snakeCaseToTitleCase($ruleSet[1]);
        $value    = $ruleSet[2] ?? null;

        if (str_contains($rule, ',')) {
            $value = array_filter(array_map('trim', explode(',', $value)));
        }

        if (!class_exists($prefix . $operator)) {
            throw new \InvalidArgumentException("Error: The operator class '" . $prefix . $operator . "' does not exist.");
        }

        return [
            'field'    => $ruleSet[0],
            'operator' => $operator,
            'value'    => $value
        ];
    }

    /**
     * Traverse data
     *
     * @param  string  $targetNode
     * @param  mixed   $data
     * @param  array   $nodeValues
     * @param  ?string $currentNode
     * @param  int     $depth
     * @return void
     */
    public static function traverseData(
        string $targetNode, mixed $data, array &$nodeValues = [], ?string &$currentNode = null, int &$depth = 0
    ): void
    {
        if ($targetNode === $currentNode) {
            $nodeValues[] = $data;
        } else if (is_array($data)) {
            foreach ($data as $key => $datum) {
                if (!is_numeric($key)) {
                    $currentNode = ($currentNode !== null) ? $currentNode . '.' . $key : $key;
                }
                $depth++;
                self::traverseData($targetNode, $datum, $nodeValues, $currentNode, $depth);
                $depth--;
                if (str_contains($currentNode, '.') && !is_numeric($key) ||
                    (is_numeric($key) && (($key + 1) == count($data)))) {
                    $currentNode = substr($currentNode, 0, strrpos($currentNode, '.'));
                } else if ($depth == 0) {
                    $currentNode = null;
                }
            }
        }
    }

}
