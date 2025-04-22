pop-validator
=============

[![Build Status](https://github.com/popphp/pop-validator/workflows/phpunit/badge.svg)](https://github.com/popphp/pop-validator/actions)
[![Coverage Status](http://cc.popphp.org/coverage.php?comp=pop-validator)](http://cc.popphp.org/pop-validator/)

[![Join the chat at https://discord.gg/TZjgT74U7E](https://media.popphp.org/img/discord.svg)](https://discord.gg/TZjgT74U7E)

* [Overview](#overview)
* [Install](#install)
* [Quickstart](#quickstart)
* [Validation Sets](#validation-sets)
  - [Conditions](#conditions)
  - [Rules](#rules)

Overview
--------
`pop-validator` is a component for validating values and returning the appropriate result messaging.
The component comes with a set of built-in evaluation objects and also the ability to extend the
component and build your own.

`pop-validator` is a component of the [Pop PHP Framework](http://www.popphp.org/).

[Top](#pop-validator)

Install
-------

Install `pop-validator` using Composer.

    composer require popphp/pop-validator

Or, require it in your composer.json file

    "require": {
        "popphp/pop-validator" : "^4.5.0"
    }

[Top](#pop-validator)

Quickstart
----------

Here's a list of the available built-in validators, all under the namespace `Pop\Validator\`:

|                          | Built-in Validators      |                      |
|--------------------------|--------------------------|----------------------|
| Accepted                 | Email                    | IsSubnetOf           |
| AlphaNumeric             | EndsWith                 | LengthBetweenInclude |
| Alpha                    | Equal                    | LengthBetween        |
| BetweenInclude           | GreaterThanEqual         | LengthGte            |
| Between                  | GreaterThan              | LengthGt             |
| Boolean                  | HasCountEqual            | LengthLte            |
| Contains                 | HasCountGreaterThanEqual | LengthLt             |
| CountEqual               | HasCountGreaterThan      | Length               |
| CountGreaterThanEqual    | HasCountLessThanEqual    | LessThanEqual        |
| CountGreaterThan         | HasCountLessThan         | LessThan             |
| CountLessThanEqual       | HasCountNotEqual         | NotContains          |
| CountLessThan            | HasOne                   | NotEmpty             |
| CountNotEqual            | HasOneThatEquals         | NotEndsWith          |
| CreditCard               | HasOnlyOne               | NotEqual             |
| DateTimeBetweenInclude   | HasOnlyOneThatEquals     | NotInArray           |
| DateTimeBetween          | InArray                  | NotIn                |
| DateTimeEqual            | In                       | NotStartsWith        |
| DateTimeGreaterThanEqual | Ipv4                     | Numeric              |
| DateTimeGreaterThan      | Ipv6                     | RegEx                |
| DateTimeLessThanEqual    | IsArray                  | StartsWith           |
| DateTimeLessThan         | IsEmpty                  | Subnet               |
| DateTimeNotEqual         | IsJson                   | Url                  |
| Declined                 | IsNotEmpty               |                      |

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

[Top](#pop-validator)

Validation Sets
---------------

Validation sets are a way to group validators together to evaluate all of them at one time.
With that, a level of strictness can be set to enforce whether or not all the validations have
to pass or just some of them.

```php
use Pop\Validator\ValidatorSet;

$set = ValidatorSet();
$set->addValidators(['username' => ['AlphaNumeric' => null, 'LengthGte' => 8]]);

if ($set->evaluate(['username' => 'username_123'])) {
    echo 'The username satisfies the requirements.' . PHP_EOL;
} else {
    print_r($set->getErrors());
}
```

#### Lazy-Loading vs Eager-Loading

If the validators are added to the set validator using the `add*` methods will store the validator
configuration and not create the validator objects until the validator set is evaluated (lazy-loading.)
Using the `load*` methods, actual validator objects will be stored in the instance of the validator
set object (eager-loading.)

#### Strictness

The strictness of the validator set can be set as needed. If set to `STRICT_NONE` then only one
validator in the set would have to pass in order for the whole set to pass. The same applies for
conditions as well:

```php
use Pop\Validator\ValidatorSet;

$set = ValidatorSet();
$set->addValidators(['username' => ['AlphaNumeric' => null, 'LengthGte' => 8]]);
$set->setStrict(ValidatorSet::STRICT_NONE);

if ($set->evaluate(['username' => 'someuser_!23'])) {
    echo 'The username satisfies the requirements.' . PHP_EOL;
} else {
    print_r($set->getErrors());
}
```

Available strict constants are:

- `STRICT_NONE`
- `STRICT_VALIDATIONS_ONLY`
- `STRICT_CONDITIONS_ONLY`
- `STRICT_BOTH`

[Top](#pop-validator)

### Conditions

Conditions can be added to a validation set that would need to pass in order for the validations
to evaluate. Conditions are validators themselves. And the strictness level can be utilized as well
to enforce whether or not all the conditions pass or just some of them.

**Note:** By default, conditions store their validation configuration via lazy-loading and do not
create the validator object until the condition is evaluated.   

```php
use Pop\Validator\ValidatorSet;
use Pop\Validator\Condition;

$set = new ValidatorSet();
$set->addCondition(new Condition('client_id', 'Equal', '1'));
$set->addValidator('documents', 'NotEmpty');

$data = [
    'client_id' => 1,
    'documents' => [
        'some_file_1.pdf',
        'some_file_2.pdf',
    ]
];

if ($set->evaluate($data)) {
    echo 'The client order data satisfies the requirements.' . PHP_EOL;
} else {
    print_r($set->getErrors());
}
```

In the above example, if the value of `client_id` is changed to 2, the validators will not be evaluated,
as the required conditions would not be met.

[Top](#pop-validator)

### Rules

Rules provide a shorthand way to wire up validations and conditions within the validation set. Rules are
colon-separated strings compromised of a field, validator and value (optional.) The validator should be
a `snake_case` format of the class string.

Below is the same example from above, but using rules instead:

```php
$set = Pop\Validator\ValidatorSet::createFromRules([
    'username:alpha_numeric',
    'username:length_gte:8'
]);

if ($set->evaluate(['username' => 'someuser_123'])) {
    echo 'The username satisfies the requirements.' . PHP_EOL;
} else {
    print_r($set->getErrors());
}
```

Conditions can be added using rules as well. Here is the example from above using rules instead:

```php
use Pop\Validator\ValidatorSet;

$set = ValidatorSet::createFromRules('documents:not_empty')
    ->addConditionFromRule('client_id:equal:1');

$data = [
    'client_id' => 1,
    'documents' => [
        'some_file_1.pdf',
        'some_file_2.pdf',
    ]
];

if ($set->evaluate($data)) {
    echo 'The client order data satisfies the requirements.' . PHP_EOL;
} else {
    print_r($set->getErrors());
}
```

[Top](#pop-validator)
