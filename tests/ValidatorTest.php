<?php

namespace Pop\Validator\Test;

use Pop\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    public function testAlpha()
    {
        $validator = new Validator\Alpha(null, 'This is not a alphabetical string.', 'An alphabetical validator', 'This validator checks if a string is alphabetical.');
        $this->assertInstanceOf('Pop\Validator\AbstractValidator', $validator);
        $this->assertTrue($validator->evaluate('hello'));
        $this->assertFalse($validator->evaluate(123456));
        $this->assertEquals('This is not a alphabetical string.', $validator->getMessage());
        $this->assertTrue($validator->hasInput());
        $this->assertFalse($validator->hasValue());
        $this->assertTrue($validator->hasMessage());
        $this->assertTrue($validator->hasName());
        $this->assertTrue($validator->hasDescription());
        $this->assertEquals('An alphabetical validator', $validator->getName());
        $this->assertEquals('This validator checks if a string is alphabetical.', $validator->getDescription());

        $validator = new Validator\Alpha();
        $this->assertFalse($validator->evaluate(123456));
        $this->assertEquals('The value must only contain characters of the alphabet.', $validator->getMessage());
    }

    public function testAlphaArrayInput()
    {
        $validator = new Validator\Alpha();
        $this->assertFalse($validator->evaluate(['x', 'y']));
    }

    public function testAlphaNum()
    {
        $validator = new Validator\AlphaNumeric();
        $this->assertTrue($validator->evaluate('hello123'));
        $this->assertFalse($validator->evaluate('$%^#ascx'));
        $this->assertFalse($validator->hasResults());
        $this->assertNull($validator->getResults());
    }

    public function testAlphaNumNonStringInput()
    {
        $validator = new Validator\AlphaNumeric();
        $this->assertTrue($validator->evaluate(123456));
    }

    public function testAlphaNumBracketField()
    {
        $validator = new Validator\AlphaNumeric();
        $validator->setField('user[username]');
        $this->assertTrue($validator->evaluate(['user' => ['username' => 'hello123']]));
        $this->assertFalse($validator->evaluate(['user' => ['username' => '$%^#ascx']]));
    }

    public function testAlphaNumArrayInput()
    {
        $validator = new Validator\AlphaNumeric();
        $this->assertFalse($validator->evaluate(['x', 'y']));
    }

    public function testAccepted()
    {
        $validator = new Validator\Accepted();
        $this->assertTrue($validator->evaluate(1));
        $this->assertTrue($validator->evaluate(true));
        $this->assertTrue($validator->evaluate('true'));
        $this->assertTrue($validator->evaluate('1'));
        $this->assertTrue($validator->evaluate('yes'));
        $this->assertFalse($validator->evaluate(0));
        $this->assertFalse($validator->evaluate('0'));
        $this->assertFalse($validator->evaluate(false));
        $this->assertFalse($validator->evaluate('false'));
        $this->assertFalse($validator->evaluate('no'));
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

    public function testBoolean()
    {
        $validator = new Validator\Boolean();
        $this->assertTrue($validator->evaluate(1));
        $this->assertTrue($validator->evaluate(true));
        $this->assertTrue($validator->evaluate('1'));
        $this->assertTrue($validator->evaluate(0));
        $this->assertTrue($validator->evaluate('0'));
        $this->assertTrue($validator->evaluate(false));
    }

    public function testCountEqual()
    {
        $validator = new Validator\CountEqual(2);
        $this->assertTrue($validator->evaluate([1, 2]));
        $this->assertFalse($validator->evaluate([1, 2, 3]));
    }

    public function testCountEqualException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\CountEqual(2);
        $this->assertFalse($validator->evaluate(1));
    }

    public function testCountEqualWithSetInput()
    {
        $validator = new Validator\CountEqual(2);
        $validator->setInput([1, 2]);
        $this->assertTrue($validator->evaluate());
    }

    public function testCountGreaterThan()
    {
        $validator = new Validator\CountGreaterThan(2);
        $this->assertTrue($validator->evaluate([1, 2, 3]));
        $this->assertFalse($validator->evaluate([1]));
    }

    public function testCountGreaterThanException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\CountGreaterThan(2);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testCountGreaterThanEqual()
    {
        $validator = new Validator\CountGreaterThanEqual(2);
        $this->assertTrue($validator->evaluate([1, 2, 3]));
        $this->assertTrue($validator->evaluate([1, 2]));
        $this->assertFalse($validator->evaluate([1]));
    }

    public function testCountGreaterThanEqualException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\CountGreaterThanEqual(2);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testCountLessThan()
    {
        $validator = new Validator\CountLessThan(4);
        $this->assertTrue($validator->evaluate([1, 2, 3]));
        $this->assertFalse($validator->evaluate([1, 2, 3, 4, 5]));
    }

    public function testCountLessThanException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\CountLessThan(2);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testCountLessThanEqual()
    {
        $validator = new Validator\CountLessThanEqual(4);
        $this->assertTrue($validator->evaluate([1, 2, 3]));
        $this->assertTrue($validator->evaluate([1, 2, 3, 4]));
        $this->assertFalse($validator->evaluate([1, 2, 3, 4, 5]));
    }

    public function testCountLessThanEqualException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\CountLessThanEqual(2);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testCountNotEqual()
    {
        $validator = new Validator\CountNotEqual(2);
        $this->assertTrue($validator->evaluate([1, 2, 3]));
        $this->assertFalse($validator->evaluate([1, 2]));
    }

    public function testCountNotEqualException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\CountNotEqual(2);
        $this->assertFalse($validator->evaluate(1));
    }

    public function testHasAtLeast()
    {
        $validator = new Validator\HasAtLeast(2);
        $this->assertTrue($validator->evaluate([1, 2, 3]));
        $this->assertFalse($validator->evaluate([1]));
    }

    public function testHasAtMost()
    {
        $validator = new Validator\HasAtMost(2);
        $this->assertTrue($validator->evaluate([1, 2]));
        $this->assertFalse($validator->evaluate([1, 2, 3]));
    }

    public function testCreditCard()
    {
        $validator = new Validator\CreditCard();
        $this->assertTrue($validator->evaluate('4111111111111111'));
        $this->assertTrue($validator->evaluate('4111-1111-1111-1111'));
        $this->assertTrue($validator->evaluate('4111 1111 1111 1111'));
        $this->assertFalse($validator->evaluate('123456789'));
        $this->assertFalse($validator->evaluate('not-a-number'));
    }

    public function testCreditCardBracketField()
    {
        $validator = new Validator\CreditCard();
        $validator->setField('card[number]');
        $this->assertTrue($validator->evaluate(['card' => ['number' => '4111 1111 1111 1111']]));
        $this->assertTrue($validator->evaluate(['card' => ['number' => '4111-1111-1111-1111']]));
        $this->assertFalse($validator->evaluate(['card' => ['number' => 'not-a-number']]));
    }

    public function testDateTimeBetween()
    {
        $validator = new Validator\DateTimeBetween(['2025-11-01', '2025-11-30']);
        $this->assertTrue($validator->evaluate('2025-11-15'));
        $this->assertFalse($validator->evaluate('2025-12-01'));
        $this->assertCount(2, $validator->getValue());
    }

    public function testDateTimeBetweenInclude()
    {
        $validator = new Validator\DateTimeBetweenInclude(['2025-11-01', '2025-11-30']);
        $this->assertTrue($validator->evaluate('2025-11-30'));
        $this->assertFalse($validator->evaluate('2025-12-01'));
        $this->assertCount(2, $validator->getValue());
    }

    public function testDateTimeEqual()
    {
        $validator = new Validator\DateTimeEqual('2025-11-30');
        $this->assertTrue($validator->evaluate('2025-11-30'));
        $this->assertFalse($validator->evaluate('2025-11-29'));
        $this->assertEquals('2025-11-30', $validator->getValue());
    }

    public function testDateTimeGreaterThan()
    {
        $validator = new Validator\DateTimeGreaterThan('2025-11-30');
        $this->assertTrue($validator->evaluate('2025-12-01'));
        $this->assertFalse($validator->evaluate('2025-11-29'));
        $this->assertEquals('2025-11-30', $validator->getValue());
    }

    public function testDateTimeGreaterThanEqual()
    {
        $validator = new Validator\DateTimeGreaterThanEqual('2025-11-30');
        $this->assertTrue($validator->evaluate('2025-11-30'));
        $this->assertFalse($validator->evaluate('2025-11-29'));
        $this->assertEquals('2025-11-30', $validator->getValue());
    }

    public function testDateTimeLessThan()
    {
        $validator = new Validator\DateTimeLessThan('2025-11-30');
        $this->assertTrue($validator->evaluate('2025-11-01'));
        $this->assertFalse($validator->evaluate('2025-12-29'));
        $this->assertEquals('2025-11-30', $validator->getValue());
    }

    public function testDateTimeLessThanEqual()
    {
        $validator = new Validator\DateTimeLessThanEqual('2025-11-30');
        $this->assertTrue($validator->evaluate('2025-11-30'));
        $this->assertFalse($validator->evaluate('2025-12-29'));
        $this->assertEquals('2025-11-30', $validator->getValue());
    }

    public function testDateTimeNotEqual()
    {
        $validator = new Validator\DateTimeNotEqual('2025-11-30');
        $this->assertTrue($validator->evaluate('2025-11-29'));
        $this->assertFalse($validator->evaluate('2025-11-30'));
        $this->assertEquals('2025-11-30', $validator->getValue());
    }

    public function testDateTimeBadValue()
    {
        $validator = new Validator\DateTimeGreaterThan('123456789');
        $this->assertEquals('123456789', $validator->getValue());
    }

    public function testDateTimeUnrecognizedFormatFallsBackToStrtotime()
    {
        $validator = new Validator\DateTimeGreaterThan('November 1, 2025');
        $this->assertIsInt($validator->getValue());
        $this->assertTrue($validator->evaluate('November 15, 2025'));
        $this->assertFalse($validator->evaluate('October 15, 2025'));
    }

    public function testDeclined()
    {
        $validator = new Validator\Declined();
        $this->assertFalse($validator->evaluate(1));
        $this->assertFalse($validator->evaluate(true));
        $this->assertFalse($validator->evaluate('true'));
        $this->assertFalse($validator->evaluate('1'));
        $this->assertFalse($validator->evaluate('yes'));
        $this->assertTrue($validator->evaluate(0));
        $this->assertTrue($validator->evaluate('0'));
        $this->assertTrue($validator->evaluate(false));
        $this->assertTrue($validator->evaluate('false'));
        $this->assertTrue($validator->evaluate('no'));
    }

    public function testEmail()
    {
        $validator = new Validator\Email();
        $this->assertTrue($validator->evaluate('test@test.com'));
        $this->assertTrue($validator->evaluate('test@test.technology'));
        $this->assertFalse($validator->evaluate('bademail'));
        $this->assertFalse($validator->evaluate('this is not an email but has foo@bar.co embedded in it'));
    }

    public function testEndsWith()
    {
        $validator = new Validator\EndsWith('xyz');
        $this->assertTrue($validator->evaluate('qrstuvwxyz'));
        $this->assertFalse($validator->evaluate('abcdefghi'));
    }

    public function testEndsWithNonStringInput()
    {
        $validator = new Validator\EndsWith(123);
        $this->assertTrue($validator->evaluate(45123));
        $this->assertFalse($validator->evaluate(456));
    }

    public function testEndsWithArrayInput()
    {
        $validator = new Validator\EndsWith('Array');
        $this->assertFalse($validator->evaluate(['x', 'y']));
    }

    public function testNotEndsWith()
    {
        $validator = new Validator\NotEndsWith('xyz');
        $this->assertFalse($validator->evaluate('qrstuvwxyz'));
        $this->assertTrue($validator->evaluate('abcdefghi'));
    }

    public function testNotEndsWithNonStringInput()
    {
        $validator = new Validator\NotEndsWith(123);
        $this->assertFalse($validator->evaluate(45123));
        $this->assertTrue($validator->evaluate(456));
    }

    public function testNotEndsWithArrayInput()
    {
        $validator = new Validator\NotEndsWith('Array');
        $this->assertTrue($validator->evaluate(['x', 'y']));
    }

    public function testEqual()
    {
        $validator = new Validator\Equal(10);
        $this->assertTrue($validator->evaluate(10));
        $this->assertFalse($validator->evaluate(15));
    }

    public function testEqualPlainField()
    {
        $validator = new Validator\Equal(1);
        $validator->setField('active');
        $this->assertEquals('active', $validator->getField());
        $this->assertFalse($validator->hasKeyField());
    }

    public function testEqualBracketField()
    {
        $validator = new Validator\Equal(1);
        $validator->setField('user[active]');
        $this->assertTrue($validator->hasField());
        $this->assertTrue($validator->evaluate(['user' => ['active' => 1]]));
        $this->assertTrue($validator->hasKeyField());
        $this->assertEquals(['key' => 'user', 'field' => 'active'], $validator->getField());
        $this->assertEquals(1, $validator->getKeyFieldValue());
        $this->assertFalse($validator->evaluate(['user' => ['active' => 0]]));
    }

    public function testHasCountEqual1()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $data2 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com']
            ]
        ];
        $validator = new Validator\HasCountEqual(['users' => 2]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasCountEqual2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $validator = new Validator\HasCountEqual(['website_data.users' => 2]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasCountEqualException1()
    {

        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountEqual(['users' => 2]);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testHasCountEqualException2()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountEqual(2);
        $this->assertTrue($validator->evaluate($data1));
    }

    public function testHasCountEqualBracketField()
    {
        $validator = new Validator\HasCountEqual(['users' => 2]);
        $validator->setField('group[users]');
        $this->assertTrue($validator->evaluate(['group' => ['users' => [1, 2]]]));
        $this->assertFalse($validator->evaluate(['group' => ['users' => [1]]]));
    }

    public function testHasCountEqualWithSetInput()
    {
        $data = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $validator = new Validator\HasCountEqual(['users' => 2]);
        $validator->setInput($data);
        $this->assertTrue($validator->evaluate());
    }

    public function testHasCountEqualScalarLeaf()
    {
        $validator = new Validator\HasCountEqual(['group.name' => 2]);
        $this->assertFalse($validator->evaluate(['group' => ['name' => 'bob']]));
    }

    public function testHasCountNotEqual1()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $data2 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com']
            ]
        ];
        $validator = new Validator\HasCountNotEqual(['users' => 2]);
        $this->assertFalse($validator->evaluate($data1));
        $this->assertTrue($validator->evaluate($data2));
    }

    public function testHasCountNotEqual2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $validator = new Validator\HasCountNotEqual(['website_data.users' => 2]);
        $this->assertFalse($validator->evaluate($data1));
        $this->assertTrue($validator->evaluate($data2));
    }

    public function testHasCountNotEqualException1()
    {

        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountNotEqual(['users' => 2]);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testHasCountNotEqualException2()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountNotEqual(2);
        $this->assertTrue($validator->evaluate($data1));
    }

    public function testHasCountNotEqualBracketField()
    {
        $validator = new Validator\HasCountNotEqual(['users' => 2]);
        $validator->setField('group[users]');
        $this->assertTrue($validator->evaluate(['group' => ['users' => [1, 2, 3]]]));
        $this->assertFalse($validator->evaluate(['group' => ['users' => [1, 2]]]));
    }

    public function testHasCountGreaterThan1()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $data2 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com']
            ]
        ];
        $validator = new Validator\HasCountGreaterThan(['users' => 1]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasCountGreaterThan2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $validator = new Validator\HasCountGreaterThan(['website_data.users' => 1]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasCountGreaterThanException1()
    {

        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountGreaterThan(['users' => 1]);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testHasCountGreaterThanException2()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountGreaterThan(1);
        $this->assertTrue($validator->evaluate($data1));
    }

    public function testHasCountGreaterThanBracketField()
    {
        $validator = new Validator\HasCountGreaterThan(['users' => 1]);
        $validator->setField('group[users]');
        $this->assertTrue($validator->evaluate(['group' => ['users' => [1, 2]]]));
        $this->assertFalse($validator->evaluate(['group' => ['users' => [1]]]));
    }

    public function testHasCountGreaterThanEqual1()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $data2 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com']
            ]
        ];
        $validator = new Validator\HasCountGreaterThanEqual(['users' => 2]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasCountGreaterThanEqual2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $validator = new Validator\HasCountGreaterThanEqual(['website_data.users' => 2]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasCountGreaterThanEqualException1()
    {

        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountGreaterThanEqual(['users' => 2]);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testHasCountGreaterThanEqualException2()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountGreaterThanEqual(1);
        $this->assertTrue($validator->evaluate($data1));
    }

    public function testHasCountGreaterThanEqualBracketField()
    {
        $validator = new Validator\HasCountGreaterThanEqual(['users' => 2]);
        $validator->setField('group[users]');
        $this->assertTrue($validator->evaluate(['group' => ['users' => [1, 2]]]));
        $this->assertFalse($validator->evaluate(['group' => ['users' => [1]]]));
    }

    public function testHasCountLessThan1()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $data2 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com']
            ]
        ];
        $validator = new Validator\HasCountLessThan(['users' => 2]);
        $this->assertFalse($validator->evaluate($data1));
        $this->assertTrue($validator->evaluate($data2));
    }

    public function testHasCountLessThan2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $validator = new Validator\HasCountLessThan(['website_data.users' => 2]);
        $this->assertFalse($validator->evaluate($data1));
        $this->assertTrue($validator->evaluate($data2));
    }

    public function testHasCountLessThanException1()
    {

        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountLessThan(['users' => 1]);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testHasCountLessThanException2()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountLessThan(1);
        $this->assertTrue($validator->evaluate($data1));
    }

    public function testHasCountLessThanBracketField()
    {
        $validator = new Validator\HasCountLessThan(['users' => 3]);
        $validator->setField('group[users]');
        $this->assertTrue($validator->evaluate(['group' => ['users' => [1, 2]]]));
        $this->assertFalse($validator->evaluate(['group' => ['users' => [1, 2, 3]]]));
    }

    public function testHasCountLessThanEqual1()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $data2 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'John Doe', 'email' => 'john@doe.com']
            ]
        ];
        $validator = new Validator\HasCountLessThanEqual(['users' => 2]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasCountLessThanEqual2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $validator = new Validator\HasCountLessThanEqual(['website_data.users' => 2]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasCountLessThanEqualException1()
    {

        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountLessThanEqual(['users' => 2]);
        $this->assertTrue($validator->evaluate(1));
    }

    public function testHasCountLessThanEqualException2()
    {
        $data1 = [
            'users' => [
                ['name' => 'John Doe', 'email' => 'john@doe.com'],
                ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
            ]
        ];
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasCountLessThanEqual(1);
        $this->assertTrue($validator->evaluate($data1));
    }

    public function testHasCountLessThanEqualBracketField()
    {
        $validator = new Validator\HasCountLessThanEqual(['users' => 2]);
        $validator->setField('group[users]');
        $this->assertTrue($validator->evaluate(['group' => ['users' => [1, 2]]]));
        $this->assertFalse($validator->evaluate(['group' => ['users' => [1, 2, 3]]]));
    }

    public function testHasOne1()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com']
            ]
        ];
        $data2 = [
            'not_users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com'],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com']
            ]
        ];
        $validator = new Validator\HasOne('users');
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOne2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'Jane Doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'not_users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];

        $validator = new Validator\HasOne('website_data.users');
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneException1()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOne('users');
        $this->assertTrue($validator->evaluate(2));
    }

    public function testHasOneException2()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOne('');
        $this->assertTrue($validator->evaluate([1]));
    }

    public function testHasOneBracketField()
    {
        $validator = new Validator\HasOne('users');
        $validator->setField('group[users]');
        $this->assertTrue($validator->evaluate(['group' => ['users' => [1, 2]]]));
        $this->assertFalse($validator->evaluate(['group' => ['users' => []]]));
    }

    public function testHasOneWithSetInput()
    {
        $validator = new Validator\HasOne('users');
        $validator->setInput(['users' => [['username' => 'someuser']]]);
        $this->assertTrue($validator->evaluate());
    }

    public function testHasOneThatEquals1()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com']
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com'],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com']
            ]
        ];
        $validator = new Validator\HasOneThatEquals(['users.username' => 'john_doe']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneThatEquals2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['username' => 'john_doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['username' => 'bob_doe', 'email' => 'bob@doe.com'],
                        ['username' => 'jane_doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];

        $validator = new Validator\HasOneThatEquals(['website_data.users.username' => 'john_doe']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneThatEquals3()
    {
        $data1 = [
            'title'      => 'My Website',
            'user_count' => 3
        ];
        $data2 = [
            'title'      => 'My Website',
            'user_count' => 4
        ];
        $validator = new Validator\HasOneThatEquals(['user_count' => 3]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneThatEqualsException1()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOneThatEquals('users');
        $this->assertTrue($validator->evaluate(2));
    }

    public function testHasOneThatEqualsException2()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOneThatEquals('');
        $this->assertTrue($validator->evaluate([1]));
    }

    public function testHasOneThatEqualsBracketField()
    {
        $validator = new Validator\HasOneThatEquals(['username' => 'john_doe']);
        $validator->setField('users[username]');
        $this->assertTrue($validator->evaluate(['users' => ['username' => 'john_doe']]));
        $this->assertFalse($validator->evaluate(['users' => ['username' => 'jane_doe']]));
    }

    public function testHasOneIn()
    {
        $validator = new Validator\HasOneIn(['username' => 'john_doe']);
        $this->assertTrue($validator->evaluate(['username' => 'john_doe']));
        $this->assertFalse($validator->evaluate(['username' => 'jane_doe']));
    }

    public function testHasOneDateTimeThatEquals()
    {
        $data1 = ['users' => [['username' => 'john_doe', 'joined_at' => '2025-11-10']]];
        $data2 = ['users' => [['username' => 'bob_doe', 'joined_at' => '2025-11-01']]];
        $validator = new Validator\HasOneDateTimeThatEquals(['users.joined_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOne1()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com']
            ]
        ];
        $data2 = [
            'not_users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com'],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com']
            ]
        ];
        $validator = new Validator\HasOnlyOne('users');
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOne2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'not_users' => [
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'John Doe', 'email' => 'john@doe.com'],
                        ['name' => 'John Doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];

        $validator = new Validator\HasOnlyOne('website_data.users');
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneException1()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOne('users');
        $this->assertTrue($validator->evaluate(2));
    }

    public function testHasOnlyOneException2()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOne('');
        $this->assertTrue($validator->evaluate([1]));
    }

    public function testHasOnlyOneBracketField()
    {
        $validator = new Validator\HasOnlyOne('users');
        $validator->setField('group[users]');
        $this->assertTrue($validator->evaluate(['group' => ['users' => [1]]]));
        $this->assertFalse($validator->evaluate(['group' => ['users' => [1, 2]]]));
    }

    public function testHasOnlyOneThatEquals1()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com']
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com'],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com']
            ]
        ];
        $validator = new Validator\HasOnlyOneThatEquals(['users.username' => 'john_doe']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneThatEquals2()
    {
        $data1 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['username' => 'john_doe', 'email' => 'john@doe.com']
                    ]
                ]
            ]
        ];
        $data2 = [
            [
                'website_data' => [
                    'title' => 'My Website',
                    'users' => [
                        ['username' => 'bob_doe', 'email' => 'bob@doe.com'],
                        ['username' => 'jane_doe', 'email' => 'jane@doe.com']
                    ]
                ]
            ]
        ];

        $validator = new Validator\HasOnlyOneThatEquals(['website_data.users.username' => 'john_doe']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneThatEqualsException1()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOneThatEquals('users');
        $this->assertTrue($validator->evaluate(2));
    }

    public function testHasOnlyOneThatEqualsException2()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOneThatEquals('');
        $this->assertTrue($validator->evaluate([1]));
    }

    public function testHasOnlyOneThatEqualsException3()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOneThatEquals(['user_count' => 3]);
        $this->assertTrue($validator->evaluate([1]));
    }

    public function testHasOneGreaterThan()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com', 'logins' => 10]
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com', 'logins' => 5],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com', 'logins' => 0]
            ]
        ];
        $validator = new Validator\HasOneGreaterThan(['users.logins' => 5]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneGreaterThanFlatField()
    {
        $validator = new Validator\HasOneGreaterThan(['logins' => 5]);
        $this->assertTrue($validator->evaluate(['logins' => 10]));
        $this->assertFalse($validator->evaluate(['logins' => 4]));
    }

    public function testHasOneGreaterThanBracketField()
    {
        $validator = new Validator\HasOneGreaterThan(['logins' => 5]);
        $validator->setField('user[logins]');
        $this->assertTrue($validator->evaluate(['user' => ['logins' => 10]]));
        $this->assertFalse($validator->evaluate(['user' => ['logins' => 4]]));
    }

    public function testHasOneGreaterThanNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOneGreaterThan(['logins' => 5]);
        $validator->evaluate(1);
    }

    public function testHasOneDateTimeGreaterThan()
    {
        $data1 = ['events' => [['name' => 'A', 'occurred_at' => '2025-11-15']]];
        $data2 = ['events' => [['name' => 'A', 'occurred_at' => '2025-11-01']]];
        $validator = new Validator\HasOneDateTimeGreaterThan(['events.occurred_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneGreaterThanEqual()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com', 'logins' => 5]
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com', 'logins' => 4],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com', 'logins' => 0]
            ]
        ];
        $validator = new Validator\HasOneGreaterThanEqual(['users.logins' => 5]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneGreaterThanEqualFlatField()
    {
        $validator = new Validator\HasOneGreaterThanEqual(['logins' => 5]);
        $this->assertTrue($validator->evaluate(['logins' => 5]));
        $this->assertFalse($validator->evaluate(['logins' => 4]));
    }

    public function testHasOneGreaterThanEqualBracketField()
    {
        $validator = new Validator\HasOneGreaterThanEqual(['logins' => 5]);
        $validator->setField('user[logins]');
        $this->assertTrue($validator->evaluate(['user' => ['logins' => 5]]));
        $this->assertFalse($validator->evaluate(['user' => ['logins' => 4]]));
    }

    public function testHasOneGreaterThanEqualNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOneGreaterThanEqual(['logins' => 5]);
        $validator->evaluate(1);
    }

    public function testHasOneDateTimeGreaterThanEqual()
    {
        $data1 = ['events' => [['name' => 'A', 'occurred_at' => '2025-11-10']]];
        $data2 = ['events' => [['name' => 'A', 'occurred_at' => '2025-11-01']]];
        $validator = new Validator\HasOneDateTimeGreaterThanEqual(['events.occurred_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneLessThan()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com', 'logins' => 4]
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com', 'logins' => 5],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com', 'logins' => 6]
            ]
        ];
        $validator = new Validator\HasOneLessThan(['users.logins' => 5]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneLessThanFlatField()
    {
        $validator = new Validator\HasOneLessThan(['logins' => 5]);
        $this->assertTrue($validator->evaluate(['logins' => 4]));
        $this->assertFalse($validator->evaluate(['logins' => 6]));
    }

    public function testHasOneLessThanBracketField()
    {
        $validator = new Validator\HasOneLessThan(['logins' => 5]);
        $validator->setField('user[logins]');
        $this->assertTrue($validator->evaluate(['user' => ['logins' => 4]]));
        $this->assertFalse($validator->evaluate(['user' => ['logins' => 6]]));
    }

    public function testHasOneLessThanNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOneLessThan(['logins' => 5]);
        $validator->evaluate(1);
    }

    public function testHasOneDateTimeLessThan()
    {
        $data1 = ['events' => [['name' => 'A', 'occurred_at' => '2025-11-01']]];
        $data2 = ['events' => [['name' => 'A', 'occurred_at' => '2025-11-15']]];
        $validator = new Validator\HasOneDateTimeLessThan(['events.occurred_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneLessThanEqual()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com', 'logins' => 5]
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com', 'logins' => 7],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com', 'logins' => 6]
            ]
        ];
        $validator = new Validator\HasOneLessThanEqual(['users.logins' => 5]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneLessThanEqualFlatField()
    {
        $validator = new Validator\HasOneLessThanEqual(['logins' => 5]);
        $this->assertTrue($validator->evaluate(['logins' => 5]));
        $this->assertFalse($validator->evaluate(['logins' => 6]));
    }

    public function testHasOneLessThanEqualBracketField()
    {
        $validator = new Validator\HasOneLessThanEqual(['logins' => 5]);
        $validator->setField('user[logins]');
        $this->assertTrue($validator->evaluate(['user' => ['logins' => 5]]));
        $this->assertFalse($validator->evaluate(['user' => ['logins' => 6]]));
    }

    public function testHasOneLessThanEqualNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOneLessThanEqual(['logins' => 5]);
        $validator->evaluate(1);
    }

    public function testHasOneDateTimeLessThanEqual()
    {
        $data1 = ['events' => [['name' => 'A', 'occurred_at' => '2025-11-10']]];
        $data2 = ['events' => [['name' => 'A', 'occurred_at' => '2025-11-15']]];
        $validator = new Validator\HasOneDateTimeLessThanEqual(['events.occurred_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneGreaterThan()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com', 'logins' => 10],
                ['username' => 'john_doe2', 'email' => 'john@doe2.com', 'logins' => 4]
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com', 'logins' => 6],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com', 'logins' => 7]
            ]
        ];
        $validator = new Validator\HasOnlyOneGreaterThan(['users.logins' => 5]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneGreaterThanNoDotException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOneGreaterThan(['logins' => 5]);
        $validator->evaluate(['logins' => 10]);
    }

    public function testHasOnlyOneDateTimeGreaterThan()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'joined_at' => '2025-11-15'],
                ['username' => 'john_doe2', 'joined_at' => '2025-10-01']
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'joined_at' => '2025-11-12'],
                ['username' => 'jane_doe', 'joined_at' => '2025-11-20']
            ]
        ];
        $validator = new Validator\HasOnlyOneDateTimeGreaterThan(['users.joined_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneGreaterThanEqual()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com', 'logins' => 5],
                ['username' => 'john_doe2', 'email' => 'john@doe2.com', 'logins' => 4]
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com', 'logins' => 6],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com', 'logins' => 7]
            ]
        ];
        $validator = new Validator\HasOnlyOneGreaterThanEqual(['users.logins' => 5]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneGreaterThanEqualNoDotException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOneGreaterThanEqual(['logins' => 5]);
        $validator->evaluate(['logins' => 10]);
    }

    public function testHasOnlyOneDateTimeGreaterThanEqual()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'joined_at' => '2025-11-10'],
                ['username' => 'john_doe2', 'joined_at' => '2025-10-01']
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'joined_at' => '2025-11-10'],
                ['username' => 'jane_doe', 'joined_at' => '2025-11-20']
            ]
        ];
        $validator = new Validator\HasOnlyOneDateTimeGreaterThanEqual(['users.joined_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneLessThan()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com', 'logins' => 10],
                ['username' => 'john_doe2', 'email' => 'john@doe2.com', 'logins' => 4]
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com', 'logins' => 6],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com', 'logins' => 7]
            ]
        ];
        $validator = new Validator\HasOnlyOneLessThan(['users.logins' => 5]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneLessThanNoDotException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOneLessThan(['logins' => 5]);
        $validator->evaluate(['logins' => 4]);
    }

    public function testHasOnlyOneDateTimeLessThan()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'joined_at' => '2025-10-01'],
                ['username' => 'john_doe2', 'joined_at' => '2025-11-15']
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'joined_at' => '2025-11-01'],
                ['username' => 'jane_doe', 'joined_at' => '2025-11-05']
            ]
        ];
        $validator = new Validator\HasOnlyOneDateTimeLessThan(['users.joined_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneLessThanEqual()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'email' => 'john@doe.com', 'logins' => 10],
                ['username' => 'john_doe2', 'email' => 'john@doe2.com', 'logins' => 5]
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'email' => 'bob@doe.com', 'logins' => 6],
                ['username' => 'jane_doe', 'email' => 'jane@doe.com', 'logins' => 7]
            ]
        ];
        $validator = new Validator\HasOnlyOneLessThanEqual(['users.logins' => 5]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneLessThanEqualNoDotException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOneLessThanEqual(['logins' => 5]);
        $validator->evaluate(['logins' => 4]);
    }

    public function testHasOnlyOneDateTimeLessThanEqual()
    {
        $data1 = [
            'users' => [
                ['username' => 'john_doe', 'joined_at' => '2025-11-15'],
                ['username' => 'john_doe2', 'joined_at' => '2025-11-10']
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'joined_at' => '2025-11-01'],
                ['username' => 'jane_doe', 'joined_at' => '2025-11-05']
            ]
        ];
        $validator = new Validator\HasOnlyOneDateTimeLessThanEqual(['users.joined_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneDateTimeThatEquals()
    {
        $data1 = ['users' => [['username' => 'john_doe', 'joined_at' => '2025-11-10']]];
        $data2 = [
            'users' => [
                ['username' => 'bob_doe', 'joined_at' => '2025-11-10'],
                ['username' => 'jane_doe', 'joined_at' => '2025-11-10']
            ]
        ];
        $validator = new Validator\HasOnlyOneDateTimeThatEquals(['users.joined_at' => '2025-11-10']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneNotEmpty()
    {
        $data1 = [
            'client_services' => [
                ['service_id' => null, 'name' => 'Two Owner'],
                ['service_id' => 1, 'name' => 'Two'],
            ],
        ];
        $data2 = [
            'client_services' => [
                ['service_id' => null, 'name' => 'Two Owner'],
                ['service_id' => null, 'name' => 'Two'],
            ],
        ];
        $validator = new Validator\HasOneNotEmpty('client_services.service_id');
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneNotEmptyFlatField()
    {
        $validator = new Validator\HasOneNotEmpty('notes');
        $this->assertTrue($validator->evaluate(['notes' => 'has content']));
        $this->assertFalse($validator->evaluate(['notes' => '']));
    }

    public function testHasOneNotEmptyBracketField()
    {
        $validator = new Validator\HasOneNotEmpty('notes');
        $validator->setField('group[notes]');
        $this->assertTrue($validator->evaluate(['group' => ['notes' => 'has content']]));
        $this->assertFalse($validator->evaluate(['group' => ['notes' => '']]));
    }

    public function testHasOneNotEmptyNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOneNotEmpty('notes');
        $validator->evaluate(1);
    }

    public function testHasOneThatContains()
    {
        $data1 = [
            'client_services' => [
                ['service_id' => null, 'name' => 'Two Owner'],
                ['service_id' => 1, 'name' => 'Current Owner'],
            ],
        ];
        $data2 = [
            'client_services' => [
                ['service_id' => null, 'name' => 'Current Owner'],
                ['service_id' => null, 'name' => 'Update'],
            ],
        ];
        $validator = new Validator\HasOneThatContains(['client_services.name' => 'Two']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneThatContainsFlatField()
    {
        $validator = new Validator\HasOneThatContains(['description' => 'red']);
        $this->assertTrue($validator->evaluate(['description' => 'the quick red fox']));
        $this->assertFalse($validator->evaluate(['description' => 'the quick brown fox']));
    }

    public function testHasOneThatContainsNonStringValue()
    {
        $validator = new Validator\HasOneThatContains(['service_id' => 1]);
        $this->assertTrue($validator->evaluate(['service_id' => 12345]));
        $this->assertFalse($validator->evaluate(['service_id' => 999]));

        $data = [
            'client_services' => [
                ['service_id' => 555],
                ['service_id' => 12345],
            ],
        ];
        $validator = new Validator\HasOneThatContains(['client_services.service_id' => 1]);
        $this->assertTrue($validator->evaluate($data));
    }

    public function testHasOneThatContainsBracketField()
    {
        $validator = new Validator\HasOneThatContains(['description' => 'red']);
        $validator->setField('group[description]');
        $this->assertTrue($validator->evaluate(['group' => ['description' => 'the quick red fox']]));
        $this->assertFalse($validator->evaluate(['group' => ['description' => 'the quick brown fox']]));
    }

    public function testHasOneThatContainsArrayNeedle()
    {
        $data1 = [
            'client_services' => [
                ['service_id' => null, 'name' => 'Owner Two'],
                ['service_id' => 1, 'name' => 'Current Manager'],
            ],
        ];
        $data2 = [
            'client_services' => [
                ['service_id' => null, 'name' => 'Owner One'],
                ['service_id' => null, 'name' => 'Owner Two'],
            ],
        ];
        $validator = new Validator\HasOneThatContains(['client_services.name' => ['Manager', 'Director']]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOneThatContainsNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOneThatContains(['description' => 'red']);
        $validator->evaluate(1);
    }

    public function testHasOnlyOneThatContains()
    {
        $data1 = [
            'client_services' => [
                ['service_id' => null, 'name' => 'Two Owner'],
                ['service_id' => 1, 'name' => 'Current Owner'],
            ],
        ];
        $data2 = [
            'client_services' => [
                ['service_id' => null, 'name' => 'Two Owner'],
                ['service_id' => null, 'name' => 'Two Owner'],
            ],
        ];
        $validator = new Validator\HasOnlyOneThatContains(['client_services.name' => 'Two']);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneThatContainsFlatField()
    {
        $validator = new Validator\HasOnlyOneThatContains(['description' => 'red']);
        $this->assertTrue($validator->evaluate(['description' => 'the quick red fox']));
        $this->assertFalse($validator->evaluate(['description' => 'the quick brown fox']));
    }

    public function testHasOnlyOneThatContainsNonStringValue()
    {
        $validator = new Validator\HasOnlyOneThatContains(['service_id' => 1]);
        $this->assertTrue($validator->evaluate(['service_id' => 12345]));
        $this->assertFalse($validator->evaluate(['service_id' => 999]));

        $data = [
            'client_services' => [
                ['service_id' => 555],
                ['service_id' => 12345],
            ],
        ];
        $validator = new Validator\HasOnlyOneThatContains(['client_services.service_id' => 1]);
        $this->assertTrue($validator->evaluate($data));
    }

    public function testHasOnlyOneThatContainsArrayNeedle()
    {
        $data1 = [
            'client_services' => [
                ['name' => 'Owner Two'],
                ['name' => 'Current Manager'],
            ],
        ];
        $data2 = [
            'client_services' => [
                ['name' => 'Current Manager'],
                ['name' => 'Acting Manager'],
            ],
        ];
        $validator = new Validator\HasOnlyOneThatContains(['client_services.name' => ['Manager', 'Director']]);
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testHasOnlyOneThatContainsNotAnArrayException()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\HasOnlyOneThatContains(['description' => 'red']);
        $validator->evaluate(1);
    }

    public function testIsArray()
    {
        $validator = new Validator\IsArray();
        $this->assertTrue($validator->evaluate([1]));
        $this->assertFalse($validator->evaluate(15));
    }

    public function testIsEmpty()
    {
        $validator = new Validator\IsEmpty();
        $this->assertTrue($validator->evaluate([]));
        $this->assertFalse($validator->evaluate(15));
    }

    public function testIsJson()
    {
        $validator = new Validator\IsJson();
        $this->assertTrue($validator->evaluate('{"foo": "bar"}'));
        $this->assertFalse($validator->evaluate('{"foo": "bar"'));
    }

    public function testIsNotEmpty()
    {
        $validator = new Validator\IsNotEmpty();
        $this->assertTrue($validator->evaluate([1]));
        $this->assertFalse($validator->evaluate(0));
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

        $validator = new Validator\NotContains(['$', '?']);
        $this->assertFalse($validator->evaluate('test$ing'));
        $this->assertFalse($validator->evaluate('test?ing'));
        $this->assertTrue($validator->evaluate('testing'));
    }

    public function testNotContainsNonStringInput()
    {
        $validator = new Validator\NotContains(23);
        $this->assertFalse($validator->evaluate(9123));
        $this->assertTrue($validator->evaluate(456));
    }

    public function testNotInArray()
    {
        $validator = new Validator\NotInArray([2, 3]);
        $this->assertFalse($validator->evaluate([1, 2, 3]));
        $this->assertTrue($validator->evaluate([4, 5]));
    }

    public function testNotIn()
    {
        $validator = new Validator\NotIn('hello');
        $this->assertFalse($validator->evaluate('hel'));
        $this->assertTrue($validator->evaluate('qz'));

        $validator = new Validator\NotIn([1, 2, 3]);
        $this->assertFalse($validator->evaluate([2, 3]));

        $validator = new Validator\NotIn([1, 2, 3]);
        $this->assertTrue($validator->evaluate([4, 5]));

        $validator = new Validator\NotIn([1]);
        $this->assertFalse($validator->evaluate(1));
        $this->assertTrue($validator->evaluate(2));

        $validator = new Validator\NotIn('test$ing');
        $this->assertFalse($validator->evaluate(['$', '?']));
        $this->assertTrue($validator->evaluate(['?', 'testing']));
        $this->assertTrue($validator->evaluate('testing'));
    }

    public function testNotInNonStringInput()
    {
        $validator = new Validator\NotIn(9123);
        $this->assertFalse($validator->evaluate(23));
        $this->assertTrue($validator->evaluate(456));
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

        $validator = new Validator\Contains(['$', '?']);
        $this->assertTrue($validator->evaluate('test$i?ng'));
        $this->assertFalse($validator->evaluate('testing'));
    }

    public function testContainsNonStringInput()
    {
        $validator = new Validator\Contains(23);
        $this->assertTrue($validator->evaluate(9123));
        $this->assertFalse($validator->evaluate(456));
    }

    public function testInArray()
    {
        $validator = new Validator\InArray([2, 3]);
        $this->assertTrue($validator->evaluate([1, 2, 3]));
        $this->assertFalse($validator->evaluate([4, 5]));
    }

    public function testIn()
    {
        $validator = new Validator\In('hello');
        $this->assertTrue($validator->evaluate('hel'));
        $this->assertFalse($validator->evaluate('qz'));

        $validator = new Validator\In([1, 2, 3]);
        $this->assertTrue($validator->evaluate([2, 3]));

        $validator = new Validator\In([1, 2, 3]);
        $this->assertFalse($validator->evaluate([4, 5]));

        $validator = new Validator\In([1]);
        $this->assertTrue($validator->evaluate(1));
        $this->assertFalse($validator->evaluate(2));

        $validator = new Validator\In('test$i?ng');
        $this->assertTrue($validator->evaluate(['$', '?']));
        $this->assertFalse($validator->evaluate(['$', '?', 'testing']));
        $this->assertFalse($validator->evaluate('testing'));
    }

    public function testInNonStringInput()
    {
        $validator = new Validator\In(9123);
        $this->assertTrue($validator->evaluate(23));
        $this->assertFalse($validator->evaluate(456));
    }

    public function testIpv4()
    {
        $validator = new Validator\Ipv4();
        $this->assertTrue($validator->evaluate('192.168.1.10'));
        $this->assertFalse($validator->evaluate('384.400.500.678'));
        $this->assertFalse($validator->evaluate('not an ip but 192.168.1.1 is in here'));
        $this->assertFalse($validator->evaluate('1.2.3.4.5'));
    }

    public function testIpv4NonStringInput()
    {
        $validator = new Validator\Ipv4();
        $this->assertFalse($validator->evaluate(19216811));
    }

    public function testIpv6()
    {
        $validator = new Validator\Ipv6();
        $this->assertTrue($validator->evaluate('fe80::21a:70ff:fe10:ab13'));
        $this->assertFalse($validator->evaluate('badipv6'));
    }

    public function testIpv6NonStringInput()
    {
        $validator = new Validator\Ipv6();
        $this->assertFalse($validator->evaluate(123456));
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

    public function testLengthGreaterThan()
    {
        $validator = new Validator\LengthGreaterThan(5);
        $this->assertTrue($validator->evaluate('hello you'));
        $this->assertFalse($validator->evaluate('no'));
    }

    public function testLengthGreaterThanEqual()
    {
        $validator = new Validator\LengthGreaterThanEqual(5);
        $this->assertTrue($validator->evaluate('hello'));
        $this->assertFalse($validator->evaluate('no'));
    }

    public function testLengthLessThan()
    {
        $validator = new Validator\LengthLessThan(5);
        $this->assertTrue($validator->evaluate('no'));
        $this->assertFalse($validator->evaluate('hello you'));
    }

    public function testLengthLessThanEqual()
    {
        $validator = new Validator\LengthLessThanEqual(5);
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

    public function testIsNotNull()
    {
        $notNull = 'not null';
        $null    = null;
        $validator = new Validator\IsNotNull();
        $this->assertTrue($validator->evaluate($notNull));
        $this->assertFalse($validator->evaluate($null));
    }

    public function testIsNull()
    {
        $notNull = 'not null';
        $null    = null;
        $validator = new Validator\IsNull();
        $this->assertTrue($validator->evaluate($null));
        $this->assertFalse($validator->evaluate($notNull));
    }

    public function testNumeric()
    {
        $validator = new Validator\Numeric();
        $this->assertTrue($validator->evaluate('12345'));
        $this->assertFalse($validator->evaluate('hello'));
    }

    public function testRegExNonStringInput()
    {
        $validator = new Validator\RegEx('/^\d+$/');
        $this->assertTrue($validator->evaluate(123456));

        $validator = new Validator\RegEx(['/[0-9]/', '/^\d+$/']);
        $this->assertTrue($validator->evaluate(123456));

        $validator = new Validator\RegEx(['/[0-9]/', '/[a-z]/'], null, 1);
        $this->assertTrue($validator->evaluate(123456));
    }

    public function testRegEx()
    {
        $validator = new Validator\RegEx('/^\w+$/');
        $this->assertTrue($validator->evaluate('hello123'));
        $this->assertFalse($validator->evaluate('$%^#ascx'));

        $regex1 = new Validator\RegEx([
            '/[A-Z]/',
            '/[a-z]/',
            '/[0-9]/',
            '/[\$|\?|\!|\_|\-|\#|\%|\&|\@]/'  // $ ? ! _ - # % & @
        ]);

        $this->assertTrue($regex1->evaluate('Hello123$'));
        $this->assertFalse($regex1->evaluate('hello'));

        $regex1 = new Validator\RegEx([
            '/[A-Z]/',
            '/[a-z]/',
            '/[0-9]/',
            '/[\$|\?|\!|\_|\-|\#|\%|\&|\@]/'  // $ ? ! _ - # % & @
        ], "You didn't satisfy the requirements", 3);

        $this->assertEquals(3, $regex1->getNumberToSatisfy());
        $this->assertTrue($regex1->evaluate('Hello123'));
        $this->assertFalse($regex1->evaluate('hello'));
    }

    public function testRequired1()
    {
        $validator = new Validator\Required('username');
        $this->assertTrue($validator->evaluate(['username' => 'someuser']));
        $this->assertFalse($validator->evaluate([]));
    }

    public function testRequired2()
    {
        $data1 = [
            'users' => [
                ['username' => 'someuser1'],
                ['username' => 'someuser2'],
            ]
        ];
        $data2 = [
            'users' => [
                ['username' => 'someuser1'],
                ['name' => 'someuser2'],
            ]
        ];
        $validator = new Validator\Required('users.username');
        $this->assertTrue($validator->evaluate($data1));
        $this->assertFalse($validator->evaluate($data2));
    }

    public function testRequiredBracketField()
    {
        $validator = new Validator\Required('username');
        $validator->setField('user[username]');
        $this->assertTrue($validator->evaluate(['user' => ['username' => 'someuser']]));
        $this->assertFalse($validator->evaluate(['user' => []]));
    }

    public function testRequiredWithSetInput()
    {
        $validator = new Validator\Required('username');
        $validator->setInput(['username' => 'someuser']);
        $this->assertTrue($validator->evaluate());
    }

    public function testRequiredNullValueWithNonBracketField()
    {
        $validator = new Validator\Required();
        $validator->setField('username');
        $this->assertFalse($validator->evaluate(['username' => 'someuser']));
    }

    public function testRequiredException1()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\Required('users');
        $this->assertTrue($validator->evaluate(2));
    }

    public function testRequiredException2()
    {
        $this->expectException('Pop\Validator\Exception');
        $validator = new Validator\Required('');
        $this->assertTrue($validator->evaluate([1]));
    }

    public function testStartsWith()
    {
        $validator = new Validator\StartsWith('abc');
        $this->assertTrue($validator->evaluate('abcdefghi'));
        $this->assertFalse($validator->evaluate('qrstuvwxyz'));
    }

    public function testStartsWithNonStringInput()
    {
        $validator = new Validator\StartsWith(123);
        $this->assertTrue($validator->evaluate(123456));
        $this->assertFalse($validator->evaluate(456));
    }

    public function testStartsWithArrayInput()
    {
        $validator = new Validator\StartsWith('Array');
        $this->assertFalse($validator->evaluate(['x', 'y']));
    }

    public function testNotStartsWith()
    {
        $validator = new Validator\NotStartsWith('abc');
        $this->assertFalse($validator->evaluate('abcdefghi'));
        $this->assertTrue($validator->evaluate('qrstuvwxyz'));
    }

    public function testNotStartsWithNonStringInput()
    {
        $validator = new Validator\NotStartsWith(123);
        $this->assertFalse($validator->evaluate(123456));
        $this->assertTrue($validator->evaluate(456));
    }

    public function testNotStartsWithArrayInput()
    {
        $validator = new Validator\NotStartsWith('Array');
        $this->assertTrue($validator->evaluate(['x', 'y']));
    }

    public function testSubnet()
    {
        $validator = new Validator\Subnet();
        $this->assertTrue($validator->evaluate('192.168.1'));
        $this->assertFalse($validator->evaluate('192.168'));
        $this->assertFalse($validator->evaluate('garbage 192.168.1 more garbage'));
    }

    public function testSubnetNonStringInput()
    {
        $validator = new Validator\Subnet();
        $this->assertFalse($validator->evaluate(192168));
    }

    public function testUrl()
    {
        $validator = new Validator\Url();
        $this->assertTrue($validator->evaluate('http://www.google.com'));
        $this->assertFalse($validator->evaluate('nourl'));
    }

    public function testHasOneTraverseTopLevelNumericListNoMatch()
    {
        $validator = new Validator\HasOne('a.b');
        $this->assertFalse($validator->evaluate([1, 2, 3]));
    }

    public function testHasOneTraverseSkipsPastSiblingNumericList()
    {
        $data = [
            'a'  => ['x', 'y', 'z'],
            'a2' => ['b' => 'target_value'],
        ];
        $validator = new Validator\HasOne('a2.b');
        $this->assertTrue($validator->evaluate($data));
    }

}
