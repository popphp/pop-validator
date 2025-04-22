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

    public function testAlphaNum()
    {
        $validator = new Validator\AlphaNumeric();
        $this->assertTrue($validator->evaluate('hello123'));
        $this->assertFalse($validator->evaluate('$%^#ascx'));
        $this->assertFalse($validator->hasResults());
        $this->assertNull($validator->getResults());
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

    public function testCreditCard()
    {
        $validator = new Validator\CreditCard();
        $this->assertTrue($validator->evaluate('4111111111111111'));
        $this->assertTrue($validator->evaluate('4111-1111-1111-1111'));
        $this->assertTrue($validator->evaluate('4111 1111 1111 1111'));
        $this->assertFalse($validator->evaluate('123456789'));
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
        $this->assertFalse($validator->evaluate('bademail'));
    }

    public function testEndsWith()
    {
        $validator = new Validator\EndsWith('xyz');
        $this->assertTrue($validator->evaluate('qrstuvwxyz'));
        $this->assertFalse($validator->evaluate('abcdefghi'));
    }

    public function testNotEndsWith()
    {
        $validator = new Validator\NotEndsWith('xyz');
        $this->assertFalse($validator->evaluate('qrstuvwxyz'));
        $this->assertTrue($validator->evaluate('abcdefghi'));
    }

    public function testEqual()
    {
        $validator = new Validator\Equal(10);
        $this->assertTrue($validator->evaluate(10));
        $this->assertFalse($validator->evaluate(15));
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

//    public function testHasOnlyOneThatEquals3()
//    {
//        $data1 = [
//            'title'      => 'My Website',
//            'user_count' => 3
//        ];
//        $data2 = [
//            'title'      => 'My Website',
//            'user_count' => 4
//        ];
//        $validator = new Validator\HasOnlyOneThatEquals(['user_count' => 3]);
//        $this->assertTrue($validator->evaluate($data1));
//        $this->assertFalse($validator->evaluate($data2));
//    }
//
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

    public function testStartsWith()
    {
        $validator = new Validator\StartsWith('abc');
        $this->assertTrue($validator->evaluate('abcdefghi'));
        $this->assertFalse($validator->evaluate('qrstuvwxyz'));
    }

    public function testNotStartsWith()
    {
        $validator = new Validator\NotStartsWith('abc');
        $this->assertFalse($validator->evaluate('abcdefghi'));
        $this->assertTrue($validator->evaluate('qrstuvwxyz'));
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
