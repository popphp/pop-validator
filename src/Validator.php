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
     * @param ?string $field
     * @param bool    $strict
     */
    public function __construct(mixed $validators = null, ?string $field = null, bool $strict = true)
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

        $this->setStrict($strict);
    }

    /**
     * Create validator set from the rules
     *
     * @param  array  $rules
     * @param  bool   $strict
     * @param  string $prefix
     * @return Validator
     */
    public static function create(array $rules, bool $strict = true, string $prefix = 'Pop\Validator\\'): Validator
    {
        $validator = new static();
        $validator->setStrict($strict);

        foreach ($rules as $field => $rule) {
            if (str_contains($rule, ':')) {
                $ruleset = explode(':', $rule);
                $class   = $prefix . array_shift($ruleset);
            } else {
                $ruleset = null;
                $class   = $prefix . $rule;
            }

            if (class_exists($class)) {
                $validatorObject = new $class();
                $validator->addValidator($field, $validatorObject);
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
