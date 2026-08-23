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

/**
 * Value comparison trait
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    5.0.0
 */
trait ValueComparisonTrait
{

    /**
     * Resolve the input value, honoring bracket key-field notation
     *
     * @return mixed
     */
    protected function resolveInputValue(): mixed
    {
        return ($this->hasKeyField()) ? $this->getKeyFieldValue() : $this->input;
    }

    /**
     * Determine if $needle is contained within $haystack (used by Contains/NotContains)
     *
     * @param  mixed $needle
     * @param  mixed $haystack
     * @return bool
     */
    protected function containsMatch(mixed $needle, mixed $haystack): bool
    {
        if (!is_array($needle) && !is_array($haystack)) {
            return str_contains((string)$haystack, (string)$needle);
        } else if (!is_array($needle)) {
            return in_array($needle, $haystack);
        } else {
            foreach ($needle as $n) {
                if (is_array($haystack)) {
                    if (!in_array($n, $haystack)) {
                        return false;
                    }
                } else if (!str_contains((string)$haystack, $n)) {
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * Determine if none of $needle is contained within $haystack (used by NotContains)
     *
     * Note: not a simple boolean negation of containsMatch() — when $needle is an array
     * compared against a scalar $haystack, this requires that *none* of $needle's elements
     * are found (NOR), whereas containsMatch() requires that *all* of them are found (AND).
     *
     * @param  mixed $needle
     * @param  mixed $haystack
     * @return bool
     */
    protected function containsNoneMatch(mixed $needle, mixed $haystack): bool
    {
        if (!is_array($needle) && !is_array($haystack)) {
            return !str_contains((string)$haystack, (string)$needle);
        } else if (!is_array($needle)) {
            return !in_array($needle, $haystack);
        } else {
            foreach ($needle as $n) {
                if (is_array($haystack)) {
                    if (in_array($n, $haystack)) {
                        return false;
                    }
                } else if (str_contains((string)$haystack, $n)) {
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * Determine if $needle is contained within $haystack (used by In/NotIn)
     *
     * @param  mixed $needle
     * @param  mixed $haystack
     * @return bool
     */
    protected function inMatch(mixed $needle, mixed $haystack): bool
    {
        if (!is_array($needle) && !is_array($haystack)) {
            return str_contains((string)$haystack, (string)$needle);
        } else if (!is_array($needle)) {
            return in_array($needle, $haystack);
        } else if (is_array($haystack)) {
            return (array_intersect($needle, $haystack) == $needle);
        } else {
            foreach ($needle as $n) {
                if (!str_contains((string)$haystack, $n)) {
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * Determine if none of $needle is contained within $haystack (used by NotIn)
     *
     * Note: not a simple boolean negation of inMatch() — when $needle is an array
     * compared against a scalar $haystack, this requires that *none* of $needle's elements
     * are found (NOR), whereas inMatch() requires that *all* of them are found (AND).
     *
     * @param  mixed $needle
     * @param  mixed $haystack
     * @return bool
     */
    protected function inNoneMatch(mixed $needle, mixed $haystack): bool
    {
        if (!is_array($needle) && !is_array($haystack)) {
            return !str_contains((string)$haystack, (string)$needle);
        } else if (!is_array($needle)) {
            return !in_array($needle, $haystack);
        } else if (is_array($haystack)) {
            return (array_intersect($needle, $haystack) != $needle);
        } else {
            foreach ($needle as $n) {
                if (str_contains((string)$haystack, $n)) {
                    return false;
                }
            }
            return true;
        }
    }

    /**
     * Resolve the comparison value string used in default messages
     * (used by StartsWith/NotStartsWith/EndsWith/NotEndsWith)
     *
     * @param  mixed $value
     * @return string
     */
    protected function resolveComparisonValueString(mixed $value): string
    {
        if ($value !== null) {
            return (string)$value;
        } else if (!empty($this->value) && !is_array($this->value)) {
            return (string)$this->value;
        } else {
            return 'the value.';
        }
    }

    /**
     * Determine if $inputValue starts with $comparisonValue (used by StartsWith/NotStartsWith)
     *
     * An array $inputValue is never considered to "start with" anything, guarding against
     * PHP silently coercing the array to the literal string "Array" and matching on that.
     *
     * @param  mixed $inputValue
     * @param  mixed $comparisonValue
     * @return bool
     */
    protected function startsWithMatch(mixed $inputValue, mixed $comparisonValue): bool
    {
        return !is_array($inputValue) && str_starts_with((string)$inputValue, (string)$comparisonValue);
    }

    /**
     * Determine if $inputValue ends with $comparisonValue (used by EndsWith/NotEndsWith)
     *
     * An array $inputValue is never considered to "end with" anything, guarding against
     * PHP silently coercing the array to the literal string "Array" and matching on that.
     *
     * @param  mixed $inputValue
     * @param  mixed $comparisonValue
     * @return bool
     */
    protected function endsWithMatch(mixed $inputValue, mixed $comparisonValue): bool
    {
        return !is_array($inputValue) && str_ends_with((string)$inputValue, (string)$comparisonValue);
    }

}
