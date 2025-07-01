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
            "The 'data_point' must contain one item with the required value.",
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

}
