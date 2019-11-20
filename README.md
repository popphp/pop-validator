pop-validator
=============

[![Build Status](https://travis-ci.org/popphp/pop-validator.svg?branch=master)](https://travis-ci.org/popphp/pop-validator)
[![Coverage Status](http://cc.popphp.org/coverage.php?comp=pop-validator)](http://cc.popphp.org/pop-validator/)

OVERVIEW
--------
`pop-validator` is a component for validating values and returning the appropriate result messaging.
The component comes with a set of built-in evaluation objects and also the ability to extend the
component and build your own.

`pop-validator` is a component of the [Pop PHP Framework](http://www.popphp.org/).

INSTALL
-------

Install `pop-validator` using Composer.

    composer require popphp/pop-validator

BASIC USAGE
-----------

Here's a list of the available built-in validators:

|                   | Built-in Validators  |               |
|-------------------|----------------------|---------------|
| Alpha             | Ipv4                 | LessThan      |
| AlphaNumeric      | Ipv6                 | LessThanEqual |
| Between           | IsSubnetOf           | NotContains   |
| BetweenInclude    | Length               | NotEmpty      |
| Contains          | LengthBetween        | NotEqual      |
| CreditCard        | LengthBetweenInclude | Numeric       |
| Email             | LengthGt             | RegEx         |
| Equal             | LengthGte            | Subnet        |
| GreaterThan       | LengthLt             | Url           |
| GreaterThanEqual  | LengthLte            |               |

### Check an email value

```php
$validator = new Pop\Validator\Email();

// Returns false
if ($validator->evaluate('bad-email-address')) {
    // Prints out the default message 'The value must be a valid email format.'
    echo $validator->getMessage();
}

// Returns true
if ($validator->evaluate('good@email.com')) {
    // Do something with a valid email address.
}
```

### Validate against a specific value

```php
$validator = new Pop\Validator\LessThan(10);

if ($validator->evaluate(8)) { } // Returns true
```

### Set a custom message

```php
$validator = new Pop\Validator\RegEx(
    '/^.*\.(jpg|jpeg|png|gif)$/i',
    'You must only submit JPG, PNG or GIF images.'
);

// Returns false
if ($validator->evaluate('image.bad')) {
    echo $validator->getMessage();
}
```

Alternatively:

```php
$validator = new Pop\Validator\RegEx('/^.*\.(jpg|jpeg|png|gif)$/i');
$validator->setMessage('You must only submit JPG, PNG or GIF images.');

if ($validator->evaluate('image.jpg')) { } // Returns true
```

