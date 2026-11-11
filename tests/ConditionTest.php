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

    public function testEvaluateFieldReferenceIsReusable()
    {
        $condition = Validator\Condition::createFromRule('start:date_time_greater_than:[end]');
        $this->assertTrue($condition->evaluate(['start' => '2026-01-10', 'end' => '2026-01-05']));
        $this->assertFalse($condition->evaluate(['start' => '2026-01-10', 'end' => '2026-06-01']));
        $this->assertEquals('[end]', $condition->getValue());
    }

    public function testEvaluateNullFieldException()
    {
        $this->expectException('Pop\Validator\Exception');
        $condition = new Validator\Condition(null, 'Equal', 1);
        $condition->evaluate(['user_id' => 1]);
    }

    public function testEvaluateRequired()
    {
        $this->assertTrue(Validator\Condition::create('effective_date', 'Required', '')->evaluate(['effective_date' => '']));
        $this->assertTrue(Validator\Condition::createFromRule('lender:required')->evaluate(['lender' => 'X']));
    }

    public function testEvaluateRequiredDottedField()
    {
        $condition = Validator\Condition::create('properties.lot', 'Required');
        $this->assertTrue($condition->evaluate(['properties' => [['lot' => '5'], ['lot' => '7']]]));
        $this->assertFalse($condition->evaluate(['properties' => [['lot' => '5'], ['block' => '7']]]));
    }

    public function testEvaluateHasClassOnPlainField()
    {
        $input = ['issues' => [['id' => 1, 'resolved' => 0], ['id' => 2, 'resolved' => 1]]];
        $this->assertTrue(Validator\Condition::create('issues', 'HasCountEqual', 2)->evaluate($input));
        $this->assertFalse(Validator\Condition::create('issues', 'HasCountEqual', 3)->evaluate($input));
        $this->assertTrue(Validator\Condition::createFromRule('issues:has_count_equal:2')->evaluate($input));
    }

    public function testEvaluateHasOneClass()
    {
        $input = ['issues' => [['id' => 1, 'resolved' => 0]], 'notes' => []];
        $this->assertTrue(Validator\Condition::create('issues', 'HasOne')->evaluate($input));
        $this->assertFalse(Validator\Condition::create('notes', 'HasOne')->evaluate($input));
        $this->assertTrue(Validator\Condition::createFromRule('issues:has_one')->evaluate($input));
    }

    public function testEvaluateHasAtLeastAndHasAtMost()
    {
        $input = ['tags' => ['a', 'b']];
        $this->assertTrue(Validator\Condition::create('tags', 'HasAtLeast', 2)->evaluate($input));
        $this->assertFalse(Validator\Condition::create('tags', 'HasAtLeast', 3)->evaluate($input));
        $this->assertFalse(Validator\Condition::create('tags', 'HasAtMost', 1)->evaluate($input));
        $this->assertTrue(Validator\Condition::create('tags', 'HasAtMost', 2)->evaluate($input));
    }

    public function testEvaluateDottedFieldNonHasClassException()
    {
        $this->expectException('Pop\Validator\Exception');
        Validator\Condition::create('properties.lot', 'Equal', '5')->evaluate(['properties' => [['lot' => '5']]]);
    }

    public function testEvaluateHasNone()
    {
        $closed = ['issues' => [['id' => 1, 'resolved' => 1], ['id' => 2, 'resolved' => 1]], 'tags' => []];
        $open   = ['issues' => [['id' => 1, 'resolved' => 0], ['id' => 2, 'resolved' => 1]], 'tags' => ['a']];

        $condition = Validator\Condition::createFromRule('issues.resolved:has_none_that_equals:0');
        $this->assertTrue($condition->evaluate($closed));
        $this->assertFalse($condition->evaluate($open));

        $condition = Validator\Condition::createFromRule('issues.id:has_none_in:3,4');
        $this->assertTrue($condition->evaluate($closed));
        $this->assertFalse(Validator\Condition::createFromRule('issues.id:has_none_in:2,3')->evaluate($closed));

        $condition = Validator\Condition::createFromRule('tags:has_none');
        $this->assertTrue($condition->evaluate($closed));
        $this->assertFalse($condition->evaluate($open));
    }

}
