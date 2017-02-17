<?php

namespace Pop\Validator\Test;

use Pop\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function testAlpha()
    {
        $validator = new Validator\Alpha(null, 'This is not a alphabetical string.');
        $this->assertInstanceOf('Pop\Validator\AbstractValidator', $validator);
        $this->assertTrue($validator->evaluate('hello'));
        $this->assertFalse($validator->evaluate(123456));
        $this->assertEquals('This is not a alphabetical string.', $validator->getMessage());


        $validator = new Validator\Alpha();
        $this->assertFalse($validator->evaluate(123456));
        $this->assertEquals('The value must only contain characters of the alphabet.', $validator->getMessage());
    }

    public function testAlphaNum()
    {
        $validator = new Validator\AlphaNumeric();
        $this->assertTrue($validator->evaluate('hello123'));
        $this->assertFalse($validator->evaluate('$%^#ascx'));
    }

    public function testBetween()
    {
        $validator = new Validator\Between([1, 10]);
        $this->assertTrue($validator->evaluate(5));
        $this->assertFalse($validator->evaluate(15));
    }

    public function testBetweenNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\Between('bad');
        $this->assertTrue($validator->evaluate(5));
    }

    public function testBetweenTooManyValuesException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\Between([1, 10, 15]);
        $this->assertTrue($validator->evaluate(5));
    }

    public function testBetweenInclude()
    {
        $validator = new Validator\BetweenInclude([1, 10]);
        $this->assertTrue($validator->evaluate(1));
        $this->assertFalse($validator->evaluate(15));
    }

    public function testBetweenIncludeNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\BetweenInclude('bad');
        $this->assertTrue($validator->evaluate(5));
    }

    public function testBetweenIncludeTooManyValuesException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\BetweenInclude([1, 10, 15]);
        $this->assertTrue($validator->evaluate(5));
    }

    public function testCreditCard()
    {
        $validator = new Validator\CreditCard();
        $this->assertTrue($validator->evaluate('4111111111111111'));
        $this->assertTrue($validator->evaluate('4111-1111-1111-1111'));
        $this->assertTrue($validator->evaluate('4111 1111 1111 1111'));
        $this->assertFalse($validator->evaluate('123456789'));
    }

    public function testEmail()
    {
        $validator = new Validator\Email();
        $this->assertTrue($validator->evaluate('test@test.com'));
        $this->assertFalse($validator->evaluate('bademail'));
    }

    public function testEqual()
    {
        $validator = new Validator\Equal(10);
        $this->assertTrue($validator->evaluate(10));
        $this->assertFalse($validator->evaluate(15));
    }

    public function testNotContains()
    {
        $validator = new Validator\NotContains('hel');
        $this->assertFalse($validator->evaluate('hello'));
        $this->assertTrue($validator->evaluate('qz'));

        $validator = new Validator\NotContains([2, 3]);
        $this->assertFalse($validator->evaluate([1, 2, 3]));

        $validator = new Validator\NotContains([4, 5]);
        $this->assertTrue($validator->evaluate([1, 2, 3]));

        $validator = new Validator\NotContains(1);
        $this->assertFalse($validator->evaluate([1]));
        $this->assertTrue($validator->evaluate([2]));
    }

    public function testGreaterThan()
    {
        $validator = new Validator\GreaterThan(10);
        $this->assertTrue($validator->evaluate(12));
        $this->assertFalse($validator->evaluate(9));
    }

    public function testGreaterThanEqual()
    {
        $validator = new Validator\GreaterThanEqual(10);
        $this->assertTrue($validator->evaluate(10));
        $this->assertFalse($validator->evaluate(9));
    }

    public function testContains()
    {
        $validator = new Validator\Contains('hel');
        $this->assertTrue($validator->evaluate('hello'));
        $this->assertFalse($validator->evaluate('qz'));

        $validator = new Validator\Contains([2, 3]);
        $this->assertTrue($validator->evaluate([1, 2, 3]));

        $validator = new Validator\Contains([4, 5]);
        $this->assertFalse($validator->evaluate([1, 2, 3]));

        $validator = new Validator\Contains(1);
        $this->assertTrue($validator->evaluate([1]));
        $this->assertFalse($validator->evaluate([2]));
    }

    public function testIpv4()
    {
        $validator = new Validator\Ipv4();
        $this->assertTrue($validator->evaluate('192.168.1.10'));
        $this->assertFalse($validator->evaluate('384.400.500.678'));
    }

    public function testIpv6()
    {
        $validator = new Validator\Ipv6();
        $this->assertTrue($validator->evaluate('fe80::21a:70ff:fe10:ab13'));
        $this->assertFalse($validator->evaluate('badipv6'));
    }

    public function testIsSubnetOf()
    {
        $validator = new Validator\IsSubnetOf('192.168.1');
        $this->assertTrue($validator->evaluate('192.168.1.10'));
        $this->assertFalse($validator->evaluate('192.168.2.10'));
    }

    public function testIsSubnetOfException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\IsSubnetOf('192.168.1');
        $this->assertTrue($validator->evaluate('badipv4'));
    }

    public function testLength()
    {
        $validator = new Validator\Length(5);
        $this->assertTrue($validator->evaluate('hello'));
        $this->assertFalse($validator->evaluate('goodbye'));
    }

    public function testLengthBetween()
    {
        $validator = new Validator\LengthBetween([5, 10]);
        $this->assertTrue($validator->evaluate('hello you'));
        $this->assertFalse($validator->evaluate('no'));
    }

    public function testLengthBetweenNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\LengthBetween('bad');
        $this->assertTrue($validator->evaluate('no'));
    }

    public function testLengthBetweenTooManyValuesException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\LengthBetween([1, 10, 15]);
        $this->assertTrue($validator->evaluate('no'));
    }

    public function testLengthBetweenInclude()
    {
        $validator = new Validator\LengthBetweenInclude([5, 10]);
        $this->assertTrue($validator->evaluate('hello'));
        $this->assertFalse($validator->evaluate('no'));
    }

    public function testLengthBetweenIncludeNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\LengthBetweenInclude('bad');
        $this->assertTrue($validator->evaluate('no'));
    }

    public function testLengthBetweenIncludeTooManyValuesException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\LengthBetweenInclude([1, 10, 15]);
        $this->assertTrue($validator->evaluate('no'));
    }

    public function testLengthGt()
    {
        $validator = new Validator\LengthGt(5);
        $this->assertTrue($validator->evaluate('hello you'));
        $this->assertFalse($validator->evaluate('no'));
    }

    public function testLengthGte()
    {
        $validator = new Validator\LengthGte(5);
        $this->assertTrue($validator->evaluate('hello'));
        $this->assertFalse($validator->evaluate('no'));
    }

    public function testLengthLt()
    {
        $validator = new Validator\LengthLt(5);
        $this->assertTrue($validator->evaluate('no'));
        $this->assertFalse($validator->evaluate('hello you'));
    }

    public function testLengthLte()
    {
        $validator = new Validator\LengthLte(5);
        $this->assertTrue($validator->evaluate('hello'));
        $this->assertFalse($validator->evaluate('hello world'));
    }

    public function testLessThan()
    {
        $validator = new Validator\LessThan(10);
        $this->assertTrue($validator->evaluate(3));
        $this->assertEquals(3, $validator->getInput());
        $this->assertFalse($validator->evaluate(15));
        $this->assertEquals(15, $validator->getInput());
        $this->assertEquals(10, $validator->getValue());
        $validator->setInput(20);
        $this->assertFalse($validator->evaluate());
        $this->assertEquals(20, $validator->getInput());
    }

    public function testLessThanEqual()
    {
        $validator = new Validator\LessThanEqual(10);
        $this->assertTrue($validator->evaluate(10));
        $this->assertFalse($validator->evaluate(15));
    }

    public function testNotEmpty()
    {
        $var1 = '123';
        $var2 = '';
        $validator = new Validator\NotEmpty();
        $this->assertTrue($validator->evaluate($var1));
        $this->assertFalse($validator->evaluate($var2));
    }

    public function testNotEqual()
    {
        $validator = new Validator\NotEqual(10);
        $this->assertTrue($validator->evaluate(11));
        $this->assertFalse($validator->evaluate(10));
    }

    public function testNumeric()
    {
        $validator = new Validator\Numeric();
        $this->assertTrue($validator->evaluate('12345'));
        $this->assertFalse($validator->evaluate('hello'));
    }

    public function testRegEx()
    {
        $validator = new Validator\RegEx('/^\w+$/');
        $this->assertTrue($validator->evaluate('hello123'));
        $this->assertFalse($validator->evaluate('$%^#ascx'));
    }

    public function testSubnet()
    {
        $validator = new Validator\Subnet();
        $this->assertTrue($validator->evaluate('192.168.1'));
        $this->assertFalse($validator->evaluate('192.168'));
    }

    public function testUrl()
    {
        $validator = new Validator\Url();
        $this->assertTrue($validator->evaluate('http://www.google.com'));
        $this->assertFalse($validator->evaluate('nourl'));
    }

}
