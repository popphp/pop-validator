<?php
declare(strict_types=1);
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Validator;

use Pop\Utils\Str;

/**
 * Validator rule class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    5.0.0
 */
class Rule
{

    /**
     * "Has" classes
     * @var array
     */
    protected static array $hasClasses = [
        'HasOneThatEquals', 'HasOnlyOneThatEquals', 'HasCountEqual', 'HasCountGreaterThanEqual',
        'HasCountGreaterThan', 'HasCountLessThanEqual', 'HasCountLessThan', 'HasCountNotEqual',
        'HasOneThatContains', 'HasOnlyOneThatContains', 'HasOneIn',
        'HasOneGreaterThan', 'HasOneGreaterThanEqual', 'HasOneLessThan', 'HasOneLessThanEqual',
        'HasOnlyOneGreaterThan', 'HasOnlyOneGreaterThanEqual', 'HasOnlyOneLessThan', 'HasOnlyOneLessThanEqual',
        'HasOneDateTimeThatEquals', 'HasOneDateTimeGreaterThan', 'HasOneDateTimeGreaterThanEqual',
        'HasOneDateTimeLessThan', 'HasOneDateTimeLessThanEqual',
        'HasOnlyOneDateTimeThatEquals', 'HasOnlyOneDateTimeGreaterThan', 'HasOnlyOneDateTimeGreaterThanEqual',
        'HasOnlyOneDateTimeLessThan', 'HasOnlyOneDateTimeLessThanEqual',
    ];

    /**
     * "Has one" classes
     * @var array
     */
    protected static array $hasOneClasses = [
        'HasOne', 'HasOnlyOne', 'HasOneNotEmpty',
    ];

    /**
     * Parse rule
     *
     * @param  string  $rule
     * @param  ?string $prefix
     * @throws \InvalidArgumentException
     * @return array
     */
    public static function parse(string $rule, ?string $prefix = 'Pop\Validator\\'): array
    {
        $ruleSet = array_map('trim', explode(':', $rule));

        if (count($ruleSet) < 2) {
            throw new \InvalidArgumentException(
                'Error: The rule is invalid. It must have at least a field and a validator, e.g. username:not_empty.'
            );
        }

        $field     = $ruleSet[0];
        $validator = Str::snakeCaseToTitleCase($ruleSet[1]);
        $value     = (!empty($ruleSet[2])) ? $ruleSet[2] : null;
        $message   = (!empty($ruleSet[3])) ? $ruleSet[3] : null;

        if (!class_exists($prefix . $validator)) {
            throw new \InvalidArgumentException("Error: The validator class '" . $prefix . $validator . "' does not exist.");
        }

        if (str_contains($rule, ',')) {
            $value = array_filter(array_map('trim', explode(',', $value)));
        }

        if (self::isHasClass($validator, false, $prefix)) {
            $value = [$field => $value];
        } else if (self::isHasOneClass($validator, $prefix)) {
            $value = $field;
        }

        return [
            'field'     => $field,
            'validator' => $validator,
            'value'     => $value,
            'message'   => $message,
        ];
    }

    /**
     * Is a "has" class
     *
     * @param  string  $class
     * @param  bool    $both
     * @param  ?string $prefix
     * @return bool
     */
    public static function isHasClass(string $class, bool $both = false, ?string $prefix = 'Pop\Validator\\'): bool
    {
        $prefix ??= 'Pop\Validator\\';
        if (str_starts_with($class, $prefix)) {
            $class = substr($class, strlen($prefix));
        }

        $hasClasses = ($both) ? array_merge(self::$hasClasses, self::$hasOneClasses) : self::$hasClasses;

        return in_array($class, $hasClasses);
    }

    /**
     * Is a "has" class
     *
     * @param  string  $class
     * @param  ?string $prefix
     * @return bool
     */
    public static function isHasOneClass(string $class, ?string $prefix = 'Pop\Validator\\'): bool
    {
        $prefix ??= 'Pop\Validator\\';
        if (str_starts_with($class, $prefix)) {
            $class = substr($class, strlen($prefix));
        }

        return in_array($class, self::$hasOneClasses);
    }

}
