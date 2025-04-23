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

use Pop\Utils\Str;

/**
 * Validator rule class
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    http://www.popphp.org/license     New BSD License
 * @version    4.5.0
 */
class Rule
{

    /**
     * "Has" classes
     * @var array
     */
    protected static array $hasClasses = [
        'HasOneThatEquals', 'HasOnlyOneThatEquals', 'HasCountEqual', 'HasCountGreaterThanEqual',
        'HasCountGreaterThan', 'HasCountLessThanEqual', 'HasCountLessThan', 'HasCountNotEqual'
    ];

    /**
     * "Has one" classes
     * @var array
     */
    protected static array $hasOneClasses = [
        'HasOne', 'HasOnlyOne'
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

        if (in_array($validator, self::$hasClasses)) {
            $value = [$field => $value];
        } else if (in_array($validator, self::$hasOneClasses)) {
            $value = $field;
        }

        return [
            'field'     => $field,
            'validator' => $validator,
            'value'     => $value,
            'message'   => $message,
        ];
    }

}
