<?php

namespace Pop\Validator\Test;

use Pop\Validator;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{

    public function testHasCountEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' must have a field 'node' with 3 item(s).",
            (new Validator\HasCountEqual())->generateDefaultMessage('data_point', ['node' => 3])
        );
    }

    public function testHasCountNotEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' must have a field 'node' that does not have 3 item(s).",
            (new Validator\HasCountNotEqual())->generateDefaultMessage('data_point', ['node' => 3])
        );
    }

    public function testHasCountGreaterThanMessage()
    {
        $this->assertEquals(
            "The 'data_point' must have a field 'node' with more than 3 item(s).",
            (new Validator\HasCountGreaterThan())->generateDefaultMessage('data_point', ['node' => 3])
        );
    }

    public function testHasCountGreaterThanEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' must have a field 'node' with at least 3 item(s).",
            (new Validator\HasCountGreaterThanEqual())->generateDefaultMessage('data_point', ['node' => 3])
        );
    }

    public function testHasCountLessThanMessage()
    {
        $this->assertEquals(
            "The 'data_point' must have a field 'node' with less than 3 item(s).",
            (new Validator\HasCountLessThan())->generateDefaultMessage('data_point', ['node' => 3])
        );
    }

    public function testHasCountLessThanEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' must have a field 'node' with at most 3 item(s).",
            (new Validator\HasCountLessThanEqual())->generateDefaultMessage('data_point', ['node' => 3])
        );
    }

    public function testHasOneThatEqualsMessage()
    {
        $this->assertEquals(
            "The 'data_point' must contain one item with the required value.",
            (new Validator\HasOneThatEquals())->generateDefaultMessage('data_point', ['node' => 3])
        );
    }

    public function testHasOnlyOneThatEqualsMessage()
    {
        $this->assertEquals(
            "The 'data_point' must contain only one item with the required value.",
            (new Validator\HasOnlyOneThatEquals())->generateDefaultMessage('data_point', ['node' => 3])
        );
    }

    public function testLengthBetweenMessage()
    {
        $this->assertEquals(
            "The 'data_point' length must be between '5' and '10'.",
            (new Validator\LengthBetween())->generateDefaultMessage('data_point', [5, 10])
        );
    }

    public function testLengthBetweenIncludeMessage()
    {
        $this->assertEquals(
            "The 'data_point' length must be between or equal to '5' and '10'.",
            (new Validator\LengthBetweenInclude())->generateDefaultMessage('data_point', [5, 10])
        );
    }

    public function testDateTimeBetweenMessage()
    {
        $this->assertEquals(
            "The 'date_range' must be between '2025-11-01' and '2025-11-30'.",
            (new Validator\DateTimeBetween(['2025-11-01', '2025-11-30']))->generateDefaultMessage('date_range')
        );
    }

    public function testDateTimeBetweenIncludeMessage()
    {
        $this->assertEquals(
            "The 'date_range' must be between or equal to '2025-11-01' and '2025-11-30'.",
            (new Validator\DateTimeBetweenInclude(['2025-11-01', '2025-11-30']))->generateDefaultMessage('date_range')
        );
    }

    public function testEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' must equal '5'.",
            (new Validator\Equal())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testGreaterThanEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' must be greater than or equal to '5'.",
            (new Validator\GreaterThanEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testLessThanMessage()
    {
        $this->assertEquals(
            "The 'data_point' must be less than '5'.",
            (new Validator\LessThan())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testLessThanEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' must be less than or equal to '5'.",
            (new Validator\LessThanEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testNotEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' must not be equal to '5'.",
            (new Validator\NotEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testCountEqualMessage()
    {
        $this->assertEquals(
            "The count of 'data_point' must be equal to '5'.",
            (new Validator\CountEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testCountGreaterThanMessage()
    {
        $this->assertEquals(
            "The count of 'data_point' must be greater than '5'.",
            (new Validator\CountGreaterThan())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testCountGreaterThanEqualMessage()
    {
        $this->assertEquals(
            "The count of 'data_point' must be greater than or equal to '5'.",
            (new Validator\CountGreaterThanEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testCountLessThanMessage()
    {
        $this->assertEquals(
            "The count of 'data_point' must be less than '5'.",
            (new Validator\CountLessThan())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testCountLessThanEqualMessage()
    {
        $this->assertEquals(
            "The count of 'data_point' must be less than or equal to '5'.",
            (new Validator\CountLessThanEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testCountNotEqualMessage()
    {
        $this->assertEquals(
            "The count of 'data_point' must not be equal to '5'.",
            (new Validator\CountNotEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testLengthMessage()
    {
        $this->assertEquals(
            "The 'data_point' length must be equal to '5'.",
            (new Validator\Length())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testLengthGreaterThanMessage()
    {
        $this->assertEquals(
            "The 'data_point' length must be greater than '5'.",
            (new Validator\LengthGreaterThan())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testLengthGreaterThanEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' length must be greater than or equal to '5'.",
            (new Validator\LengthGreaterThanEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testLengthLessThanMessage()
    {
        $this->assertEquals(
            "The 'data_point' length must be less than '5'.",
            (new Validator\LengthLessThan())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testLengthLessThanEqualMessage()
    {
        $this->assertEquals(
            "The 'data_point' length must be less than or equal to  '5'.",
            (new Validator\LengthLessThanEqual())->generateDefaultMessage('data_point', 5)
        );
    }

    public function testEndsWithMessage()
    {
        $this->assertEquals(
            "The 'data_point' must end with 'xyz'.",
            (new Validator\EndsWith())->generateDefaultMessage('data_point', 'xyz')
        );
    }

    public function testStartsWithMessage()
    {
        $this->assertEquals(
            "The 'data_point' must start with 'abc'.",
            (new Validator\StartsWith())->generateDefaultMessage('data_point', 'abc')
        );
    }

    public function testNotEndsWithMessage()
    {
        $this->assertEquals(
            "The 'data_point' must not end with 'xyz'.",
            (new Validator\NotEndsWith())->generateDefaultMessage('data_point', 'xyz')
        );
    }

    public function testNotStartsWithMessage()
    {
        $this->assertEquals(
            "The 'data_point' must not start with 'abc'.",
            (new Validator\NotStartsWith())->generateDefaultMessage('data_point', 'abc')
        );
    }

}
