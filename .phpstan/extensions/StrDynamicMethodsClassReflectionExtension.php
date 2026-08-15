<?php
declare(strict_types=1);

/**
 * Pop\Utils\Str::__callStatic() resolves case-conversion methods dynamically
 * (e.g. snakeCaseToTitleCase(), titleCaseToSnakeCase()) based on the called
 * method name — they don't exist as real methods PHPStan can see via
 * reflection. Plain stub files (see .phpstan/stubs) can't graft new members
 * onto a class PHPStan can already reflect natively through Composer's
 * autoloader, so this registers a proper MethodsClassReflectionExtension
 * instead, matching PHPStan's own documented approach for magic-method
 * classes (e.g. its bundled SoapClient extension).
 *
 * Registered via services.phpstan.neon under the
 * phpstan.broker.methodsClassReflectionExtension tag.
 */
namespace PopValidator\PHPStan;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\MethodsClassReflectionExtension;

final class StrDynamicMethodsClassReflectionExtension implements MethodsClassReflectionExtension
{

    /**
     * Case keywords supported by Pop\Utils\Str::__callStatic(), mirrored
     * from its own $allowedCases property.
     */
    private const ALLOWED_CASES = 'titlecase|camelcase|kebabcase|dash|snakecase|underscore|namespace|path|url|uri';

    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        return $classReflection->is('Pop\Utils\Str')
            && preg_match('/^(' . self::ALLOWED_CASES . ')to(' . self::ALLOWED_CASES . ')$/i', $methodName) === 1;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        return new StrDynamicMethodReflection($classReflection, $methodName);
    }

}
