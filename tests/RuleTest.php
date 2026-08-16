<?php

namespace Pop\Validator\Test;

use Pop\Validator;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{

    public function testRuleParse1()
    {
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Validator\Rule::parse('user_id:equal:1');
        $this->assertEquals($field, 'user_id');
        $this->assertEquals($validator, 'Equal');
        $this->assertEquals($value, '1');
    }

    public function testRuleParse2()
    {
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Validator\Rule::parse('user_id:contains:1,2,3');
        $this->assertEquals($field, 'user_id');
        $this->assertEquals($validator, 'Contains');
        $this->assertEquals($value, ['1', '2', '3']);
    }

    public function testRuleParse3()
    {
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Validator\Rule::parse('users:has_one:');
        $this->assertEquals($field, 'users');
        $this->assertEquals($validator, 'HasOne');
        $this->assertEquals($value, 'users');
    }

    public function testRuleParse4()
    {
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Validator\Rule::parse('users:has_one_that_equals:1');
        $this->assertEquals($field, 'users');
        $this->assertEquals($validator, 'HasOneThatEquals');
        $this->assertEquals($value, ['users' => 1]);
    }

    public function testRuleParseException1()
    {
        $this->expectException('InvalidArgumentException');
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Validator\Rule::parse('user_id');
    }

    public function testRuleParseException2()
    {
        $this->expectException('InvalidArgumentException');
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Validator\Rule::parse('user_id:bad:1');
    }

    public function testRuleIsHasOneClass()
    {
        $this->assertTrue(Validator\Rule::isHasOneClass('Pop\Validator\HasOne'));
    }

    public function testRuleIsHasClassNullPrefix()
    {
        $this->assertTrue(Validator\Rule::isHasClass('HasOneThatEquals', false, null));
    }

    public function testRuleIsHasOneClassNullPrefix()
    {
        $this->assertTrue(Validator\Rule::isHasOneClass('HasOne', null));
    }

    public function testRuleParseHasOneGreaterThan()
    {
        ['field' => $field, 'validator' => $validator, 'value' => $value] = Validator\Rule::parse('scores:has_one_greater_than:10');
        $this->assertEquals($field, 'scores');
        $this->assertEquals($validator, 'HasOneGreaterThan');
        $this->assertEquals($value, ['scores' => '10']);
    }

    public function testRuleIsHasClassIncludesGreaterThanFamily()
    {
        $this->assertTrue(Validator\Rule::isHasClass('HasOneGreaterThan'));
        $this->assertTrue(Validator\Rule::isHasClass('HasOnlyOneLessThanEqual'));
        $this->assertTrue(Validator\Rule::isHasClass('HasOneDateTimeThatEquals'));
        $this->assertTrue(Validator\Rule::isHasClass('HasOnlyOneDateTimeGreaterThan'));
        $this->assertTrue(Validator\Rule::isHasClass('HasOneIn'));
    }

}
