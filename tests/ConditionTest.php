<?php

namespace Pop\Validator\Test;

use Pop\Validator;
use PHPUnit\Framework\TestCase;

class ConditionTest extends TestCase
{

    public function testCondition()
    {
        $condition = new Validator\Condition();
        $condition->setField('user_id');
        $condition->setValidator('Equal');
        $condition->setValue('1');
        $this->assertTrue($condition->hasField());
        $this->assertTrue($condition->hasValidator());
        $this->assertTrue($condition->hasValue());
        $this->assertTrue($condition->hasPrefix());
        $this->assertFalse($condition->hasValidatorObject());
        $this->assertEquals('user_id', $condition->getField());
        $this->assertEquals('Equal', $condition->getValidator());
        $this->assertEquals('1', $condition->getValue());
        $this->assertEquals('Pop\Validator\\', $condition->getPrefix());
        $this->assertNull($condition->getValidatorObject());
    }

    public function testCreate()
    {
        $condition = Validator\Condition::create('user_id', 'Equal', '1');
        $this->assertEquals('user_id', $condition->getField());
        $this->assertEquals('Equal', $condition->getValidator());
        $this->assertEquals('1', $condition->getValue());
    }

    public function testCreateFromRule()
    {
        $condition = Validator\Condition::createFromRule('user_id:equal:1');
        $this->assertEquals('user_id', $condition->getField());
        $this->assertEquals('Equal', $condition->getValidator());
        $this->assertEquals('1', $condition->getValue());
    }

    public function testEvaluate1()
    {
        $condition = Validator\Condition::createFromRule('user_id:equal:1:User ID must equal 1.');
        $this->assertTrue($condition->evaluate(['user_id' => 1]));
        $this->assertFalse($condition->evaluate(['user_id' => 2]));
        $this->assertTrue($condition->hasMessage());
        $this->assertEquals('User ID must equal 1.', $condition->getMessage());
    }

    public function testEvaluate2()
    {
        $condition = Validator\Condition::createFromRule('value_1:equal:[value_2]');
        $this->assertTrue($condition->evaluate(['value_1' => 1, 'value_2' => 1]));
    }

    public function testEvaluate3()
    {
        $condition = Validator\Condition::createFromRule('value_1:equal:value_2');
        $this->assertFalse($condition->evaluate(['value_1' => 1, 'value_2' => 2]));
    }

    public function testEvaluateException1()
    {
        $this->expectException('Pop\Validator\Exception');
        $condition = new Validator\Condition();
        $condition->setValidator('Bad');
        $this->assertTrue($condition->evaluate(['user_id' => 1]));
    }

    public function testEvaluateException2()
    {
        $this->expectException('Pop\Validator\Exception');
        $condition = Validator\Condition::createFromRule('user_id:equal:1');
        $this->assertTrue($condition->evaluate(['bad' => 1]));
    }

}
