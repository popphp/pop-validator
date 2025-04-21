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
use Pop\Utils\CallableObject;
use Pop\Utils\Str;

/**
 * Validator set class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class ValidatorSet
{

    /**
     * Validators
     * @var array
     */
    protected array $validators = [];

    /**
     * Load validators
     * @var array
     */
    protected array $loaded = [];

    /**
     * Evaluated validators
     * @var array
     */
    protected array $evaluated = [];

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
     * Add validators
     *
     * @param  array|string $validators
     * @param  ?string      $field
     * @param  bool         $strict
     * @return ValidatorSet
     */
    public static function add(array|string $validators, ?string $field = null, bool $strict = true): ValidatorSet
    {
        $validatorSet = new static();
        $validatorSet->setStrict($strict);

        $validators = Arr::make($validators);

        if ($field !== null) {
            $validatorSet->addValidatorsToField($field, $validators);
        } else {
            $validatorSet->addValidators($validators);
        }

        return $validatorSet;
    }

    /**
     * Load validator objects directly
     *
     * @param  array|string $validators
     * @param  ?string      $field
     * @param  bool         $strict
     * @return ValidatorSet
     */
    public static function load(array|string $validators, ?string $field = null, bool $strict = true): ValidatorSet
    {
        $validatorSet = new static();
        $validatorSet->setStrict($strict);

        $validators = Arr::make($validators);

        if ($field !== null) {
            $validatorSet->loadValidatorsToField($field, $validators);
        } else {
            $validatorSet->loadValidators($validators);
        }

        return $validatorSet;
    }

    /**
     * Create validator set from rules
     *
     * @param  array|string $rules
     * @param  bool         $strict
     * @param  string       $prefix
     * @return ValidatorSet
     */
    public static function createFromRules(
        array|string $rules, bool $strict = true, string $prefix = 'Pop\Validator\\'
    ): ValidatorSet
    {
        $validatorSet = new static();
        $validatorSet->setStrict($strict);

        $rules = Arr::make($rules);

        foreach ($rules as $rule) {
            ['field' => $field, 'validator' => $validator, 'value' => $value] = ValidatorSet::parseRule($rule, $prefix);
            $validatorSet->addValidator($field, $validator, $value, $prefix);
        }

        return $validatorSet;
    }

    /**
     * Add validator
     *
     * @param  string $field
     * @param  string $validator
     * @param  mixed  $value
     * @param  string $prefix
     * @return ValidatorSet
     */
    public function addValidator(string $field, string $validator, mixed $value = null, string $prefix = 'Pop\Validator\\'): ValidatorSet
    {
        if (!isset($this->validators[$field])) {
            $this->validators[$field] = [];
        }
        if (!class_exists($prefix . $validator)) {
            throw new \InvalidArgumentException("Error: The validator class '" . $prefix . $validator . "' does not exist.");
        }
        $this->validators[$field][] = new CallableObject($prefix . $validator, $value);
        return $this;
    }

    /**
     * Add validators to specific field
     *
     * @param  string $field
     * @param  array  $validators
     * @param  string $prefix
     * @return ValidatorSet
     */
    public function addValidatorsToField(string $field, array $validators, string $prefix = 'Pop\Validator\\'): ValidatorSet
    {
        foreach ($validators as $validator => $value) {
            $this->addValidator($field, $validator, $value, $prefix);
        }
        return $this;
    }

    /**
     * Add validators to specific field
     *
     * @param  array  $validators
     * @param  string $prefix
     * @return ValidatorSet
     */
    public function addValidators(array $validators, string $prefix = 'Pop\Validator\\'): ValidatorSet
    {
        foreach ($validators as $field => $validator) {
            if (is_array($validator)) {
                foreach ($validator as $val => $value) {
                    $this->addValidator($field, $val, $value, $prefix);
                }
            } else {
                $this->addValidator($field, $validator, null, $prefix);
            }
        }
        return $this;
    }

    /**
     * Load validator
     *
     * @param  string            $field
     * @param  AbstractValidator $validator
     * @return ValidatorSet
     */
    public function loadValidator(string $field, AbstractValidator $validator): ValidatorSet
    {
        if (!isset($this->loaded[$field])) {
            $this->loaded[$field] = [];
        }
        $this->loaded[$field][] = $validator;
        return $this;
    }

    /**
     * Load validators to specific field
     *
     * @param string $field
     * @param  array $validators
     * @return ValidatorSet
     */
    public function loadValidatorsToField(string $field, array $validators): ValidatorSet
    {
        foreach ($validators as $validator) {
            if ($validator instanceof CallableObject) {
                $validator = $validator->call();
            }
            $this->loadValidator($field, $validator);
        }
        return $this;
    }

    /**
     * Load validators to specific field
     *
     * @param  ?array $validators
     * @return ValidatorSet
     */
    public function loadValidators(?array $validators = null): ValidatorSet
    {
        if ($validators === null) {
            $validators = $this->validators;
        }
        foreach ($validators as $field => $validator) {
            foreach ($validator as $val) {
                if ($val instanceof CallableObject) {
                    $val = $val->call();
                }
                $this->loadValidator($field, $val);
            }
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
     * Is loaded
     *
     * @param  ?string $field
     * @return bool
     */
    public function isLoaded(?string $field = null): bool
    {
        if ($field !== null) {
            return (isset($this->loaded[$field]));
        } else {
            return (!empty($this->loaded));
        }
    }

    /**
     * Add condition
     *
     * @param  string    $field
     * @param  Condition $condition
     * @return ValidatorSet
     */
    public function addCondition(string $field, Condition $condition): ValidatorSet
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
     * @return ValidatorSet
     */
    public function addConditionsToField(string $field, array $conditions): ValidatorSet
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
     * @return ValidatorSet
     */
    public function addConditions(array $conditions): ValidatorSet
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
     * @return ValidatorSet
     */
    public function addError(string $field, string $error): ValidatorSet
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
     * Get evaluated
     *
     * @param  ?string $field
     * @return mixed
     */
    public function getEvaluated(?string $field = null): mixed
    {
        if ($field !== null) {
            return (isset($this->evaluated[$field])) ? $this->evaluated[$field] : null;
        } else {
            return $this->evaluated;
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
     * Is evaluated
     *
     * @param  ?string $field
     * @return bool
     */
    public function isEvaluated(?string $field = null): bool
    {
        if ($field !== null) {
            return (array_key_exists($field, $this->evaluated));
        } else {
            return (!empty($this->evaluated));
        }
    }

    /**
     * Set strict
     *
     * @param  bool $strict
     * @return ValidatorSet
     */
    public function setStrict(bool $strict = true): ValidatorSet
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
        if (!$this->isLoaded()) {
            $this->loadValidators();
        }

        foreach ($this->loaded as $field => $validators) {
            foreach ($validators as $validator) {
                $result = (isset($input[$field])) ? $validator->evaluate($input[$field]) : $validator->evaluate();
                if (!$result) {
                    $this->addError($field, $validator->getMessage());
                }
            }
            $this->evaluated[$field] = $result;
        }

        // Catch any validators that were added after a load call
        foreach ($this->validators as $field => $validators) {
            if (!$this->isEvaluated($field)) {
                $this->loadValidatorsToField($field, $validators);

                if ($this->isLoaded($field)) {
                    foreach ($this->loaded[$field] as $validator) {
                        $result = (isset($input[$field])) ? $validator->evaluate($input[$field]) : $validator->evaluate();
                        if (!$result) {
                            $this->addError($field, $validator->getMessage());
                        }
                    }
                    $this->evaluated[$field] = $result;
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
            throw new \InvalidArgumentException(
                'Error: The rule is invalid. It must have at least a field and a validator, e.g. username:not_empty.'
            );
        }

        $validator = Str::snakeCaseToTitleCase($ruleSet[1]);
        $value     = $ruleSet[2] ?? null;

        if (str_contains($rule, ',')) {
            $value = array_filter(array_map('trim', explode(',', $value)));
        }

        if (!class_exists($prefix . $validator)) {
            throw new \InvalidArgumentException("Error: The validator class '" . $prefix . $validator . "' does not exist.");
        }

        return [
            'field'     => $ruleSet[0],
            'validator' => $validator,
            'value'     => $value
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
