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

}
