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
     * Constants
     */
    const PASSED_NONE = 0;
    const PASSED_ALL  = 1;
    const PASSED_SOME = 2;

    const STRICT_NONE             = 0;
    const STRICT_VALIDATIONS_ONLY = 1;
    const STRICT_CONDITIONS_ONLY  = 2;
    const STRICT_BOTH             = 3;

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
     * Evaluated validators and their true/false results
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
     * Validation status
     * @var ?int
     */
    protected ?int $validationStatus = null;

    /**
     * Condition status
     * @var ?int
     */
    protected ?int $conditionStatus = null;

    /**
     * Strict flag
     * @var int
     */
    protected int $strict = 3;

    /**
     * Add validators
     *
     * @param  array|string $validators
     * @param  ?string      $field
     * @param  int          $strict
     * @return ValidatorSet
     */
    public static function add(array|string $validators, ?string $field = null, int $strict = 3): ValidatorSet
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
     * @param  int          $strict
     * @return ValidatorSet
     */
    public static function load(array|string $validators, ?string $field = null, int $strict = 3): ValidatorSet
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
     * @param  int          $strict
     * @param  string       $prefix
     * @return ValidatorSet
     */
    public static function createFromRules(
        array|string $rules, int $strict = 3, string $prefix = 'Pop\Validator\\'
    ): ValidatorSet
    {
        $validatorSet = new static();
        $validatorSet->setStrict($strict);

        $rules = Arr::make($rules);

        foreach ($rules as $rule) {
            ['field' => $field, 'validator' => $validator, 'value' => $value] = Rule::parse($rule, $prefix);
            $validatorSet->addValidator($field, $validator, $value, $prefix);
        }

        return $validatorSet;
    }

    /**
     * Add validator from rule
     *
     * @param  string $rule
     * @param  string $prefix
     * @return ValidatorSet
     */
    public function addValidatorFromRule(string $rule, string $prefix = 'Pop\Validator\\'): ValidatorSet
    {
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Rule::parse($rule, $prefix);
        $this->addValidator($field, $validator, $value, $prefix);
        return $this;
    }

    /**
     * Add validators from rules
     *
     * @param  array $rules
     * @param  string $prefix
     * @return ValidatorSet
     */
    public function addValidatorsFromRules(array $rules, string $prefix = 'Pop\Validator\\'): ValidatorSet
    {
        foreach ($rules as $rule) {
            $this->addValidatorFromRule($rule, $prefix);
        }
        return $this;
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
     * @param  array             $input
     * @return ValidatorSet
     */
    public function loadValidator(string $field, AbstractValidator $validator, array $input = []): ValidatorSet
    {
        if (!isset($this->loaded[$field])) {
            $this->loaded[$field] = [];
        }

        // If the value references a value in the input array
        $value = $validator->getValue();
        if (is_string($value) && !is_numeric($value) && array_key_exists($value, $input)) {
            $validator->setValue($input[$value]);
        }

        $this->loaded[$field][] = $validator;
        return $this;
    }

    /**
     * Load validators to specific field
     *
     * @param  string $field
     * @param  array  $validators
     * @param  array  $input
     * @return ValidatorSet
     */
    public function loadValidatorsToField(string $field, array $validators, array $input = []): ValidatorSet
    {
        foreach ($validators as $validator) {
            if ($validator instanceof CallableObject) {
                $validator = $validator->call();
            }
            $this->loadValidator($field, $validator, $input);
        }
        return $this;
    }

    /**
     * Load validators to specific field
     *
     * @param  ?array $validators
     * @pparam array  $input
     * @return ValidatorSet
     */
    public function loadValidators(?array $validators = null, array $input = []): ValidatorSet
    {
        if ($validators === null) {
            $validators = $this->validators;
        }
        foreach ($validators as $field => $validator) {
            foreach ($validator as $val) {
                if ($val instanceof CallableObject) {
                    $val = $val->call();
                }
                $this->loadValidator($field, $val, $input);
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
     * Get loaded validators
     *
     * @param  ?string $field
     * @return array
     */
    public function getLoadedValidators(?string $field = null): array
    {
        if ($field !== null) {
            return (isset($this->loaded[$field])) ? $this->loaded[$field] : [];
        } else {
            return $this->loaded;
        }
    }

    /**
     * Has loaded validators
     *
     * @param  ?string $field
     * @return bool
     */
    public function hasLoadedValidators(?string $field = null): bool
    {
        if ($field !== null) {
            return (isset($this->loaded[$field]));
        } else {
            return (!empty($this->loaded));
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
     * Add condition from rule
     *
     * @param  string $rule
     * @param  string $prefix
     * @return ValidatorSet
     */
    public function addConditionFromRule(string $rule, string $prefix = 'Pop\Validator\\'): ValidatorSet
    {
        $this->addCondition(Condition::createFromRule($rule, $prefix));
        return $this;
    }

    /**
     * Add conditions from rules
     *
     * @param  array  $rules
     * @param  string $prefix
     * @return ValidatorSet
     */
    public function addConditionsFromRules(array $rules, string $prefix = 'Pop\Validator\\'): ValidatorSet
    {
        foreach ($rules as $rule) {
            $this->addConditionFromRule($rule, $prefix);
        }
        return $this;
    }

    /**
     * Add condition
     *
     * @param  Condition $condition
     * @return ValidatorSet
     */
    public function addCondition(Condition $condition): ValidatorSet
    {
        $this->conditions[] = $condition;
        return $this;
    }

    /**
     * Add conditions
     *
     * @param  array $conditions
     * @return ValidatorSet
     */
    public function addConditions(array $conditions): ValidatorSet
    {
        foreach ($conditions as $condition) {
            $this->addCondition($condition);
        }
        return $this;
    }

    /**
     * Get conditions
     *
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Has conditions
     *
     * @return bool
     */
    public function hasConditions(): bool
    {
        return (!empty($this->conditions));
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
     * Get validation status
     *
     * @return ?int
     */
    public function getValidationStatus(): ?int
    {
        return $this->validationStatus;
    }
    /**
     * Get condition status
     *
     * @return ?int
     */
    public function getConditionStatus(): ?int
    {
        return $this->conditionStatus;
    }

    /**
     * Evaluate status
     *
     * @return ValidatorSet
     */
    public function evaluateStatus(): ValidatorSet
    {
        $numOfValidators = 0;
        $numOfPassed     = 0;

        foreach ($this->evaluated as $field => $evaluated) {
            foreach ($evaluated as $result) {
                $numOfValidators++;
                if ($result) {
                    $numOfPassed++;
                }
            }
        }

        if ($numOfValidators == $numOfPassed) {
            $this->validationStatus = self::PASSED_ALL;
        } else if ($numOfPassed > 0) {
            $this->validationStatus = self::PASSED_SOME;
        } else if ($numOfPassed == 0) {
            $this->validationStatus = self::PASSED_NONE;
        }

        return $this;
    }

    /**
     * Set strict
     *
     * @param  int $strict
     * @return ValidatorSet
     */
    public function setStrict(int $strict): ValidatorSet
    {
        if ($strict < 0 || $strict > 3) {
            throw new \InvalidArgumentException('Error: Strict must be between 0 and 3');
        }
        $this->strict = $strict;
        return $this;
    }

    /**
     * Get strict
     *
     * @return int
     */
    public function getStrict(): int
    {
        return $this->strict;
    }

    /**
     * Is strict
     *
     * @return bool
     */
    public function isStrict(): bool
    {
        return ($this->strict > 0);
    }

    /**
     * Evaluate all conditions over the provided input data
     *
     * @param  mixed $input
     * @return bool
     */
    public function evaluateConditions(array $input): bool
    {
        $numOfConditions = count($this->conditions);
        $numOfPassed     = 0;

        foreach ($this->conditions as $condition) {
            if ($condition->evaluate($input)) {
                $numOfPassed++;
            }
        }

        if ($numOfConditions == $numOfPassed) {
            $this->conditionStatus = self::PASSED_ALL;
        } else if ($numOfPassed > 0) {
            $this->conditionStatus = self::PASSED_SOME;
        } else if ($numOfPassed == 0) {
            $this->conditionStatus = self::PASSED_NONE;
        }

        // Passed all
        if (($this->strict == self::STRICT_BOTH) || ($this->strict == self::STRICT_CONDITIONS_ONLY)) {
            return ($numOfConditions == $numOfPassed);
        // Passed some, or false if none passed
        } else {
            return ($numOfPassed > 0);
        }
    }

    /**
     * Evaluate all validators over the provided input data
     *
     * @param  mixed $input
     * @return bool
     */
    public function evaluate(array $input): bool
    {
        $result = $this->evaluateConditions($input);
        // If conditions are met, or there are no conditions
        if (!($this->hasConditions()) || $this->evaluateConditions($input)) {
            if (!$this->isLoaded()) {
                $this->loadValidators(null, $input);
            }

            foreach ($this->loaded as $field => $validators) {
                foreach ($validators as $validator) {
                    $result = (isset($input[$field])) ? $validator->evaluate($input[$field]) : $validator->evaluate($input);
                    if (!$result) {
                        $this->addError($field, $validator->getMessage());
                    }
                    if (!isset($this->evaluated[$field])) {
                        $this->evaluated[$field] = [];
                    }
                    $this->evaluated[$field][] = $result;
                }
            }

            // Catch any validators that were added after a load call
            foreach ($this->validators as $field => $validators) {
                if (!$this->isEvaluated($field)) {
                    $this->loadValidatorsToField($field, $validators, $input);

                    if ($this->isLoaded($field)) {
                        foreach ($this->loaded[$field] as $validator) {
                            $result = (isset($input[$field])) ? $validator->evaluate($input[$field]) : $validator->evaluate();
                            if (!$result) {
                                $this->addError($field, $validator->getMessage());
                            }
                            if (!isset($this->evaluated[$field])) {
                                $this->evaluated[$field] = [];
                            }
                            $this->evaluated[$field][] = $result;
                        }
                    }
                }
            }

            $this->evaluateStatus();
        }

        // Passed all
        if (($this->strict == self::STRICT_BOTH) || ($this->strict == self::STRICT_VALIDATIONS_ONLY)) {
            return (!$this->hasErrors());
        // Passed some, or false if none passed
        } else {
            return ($this->validationStatus > 0);
        }
    }

}
