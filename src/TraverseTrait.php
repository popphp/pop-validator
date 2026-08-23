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

use Pop\Utils;

/**
 * Traverse trait
 *
 * @category   Pop
 * @package    Pop\Validator
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    5.0.0
 */
trait TraverseTrait
{

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
                $hasDot = ($currentNode !== null) && str_contains($currentNode, '.');
                if (($hasDot && !is_numeric($key)) ||
                    (is_numeric($key) && (($key + 1) == count($data)))) {
                    $currentNode = $hasDot ? substr($currentNode, 0, strrpos($currentNode, '.')) : null;
                } else if ($depth == 0) {
                    $currentNode = null;
                }
            }
        }
    }

}
