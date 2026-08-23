pop-validator
=============

[![Build Status](https://github.com/popphp/pop-validator/workflows/phpunit/badge.svg)](https://github.com/popphp/pop-validator/actions)
[![Coverage Status](https://cc.popphp.org/coverage.php?comp=pop-validator)](https://cc.popphp.org/pop-validator/)

[![Join the chat at https://discord.gg/TZjgT74U7E](https://media.popphp.org/img/discord.svg)](https://discord.gg/TZjgT74U7E)

* [Overview](#overview)
* [Install](#install)
* [Quickstart](#quickstart)
  - [Match against multiple patterns](#match-against-multiple-patterns)
  - [Validate nested or related data](#validate-nested-or-related-data)
  - [Discover available validators](#discover-available-validators)
* [Validation Sets](#validation-sets)
  - [Multiple Validators](#multiple-validators)
  - [Custom Messaging](#custom-messaging)
  - [Lazy-Loading vs Eager-Loading](#lazy-loading-vs-eager-loading)
  - [Strictness](#strictness)
  - [Conditions](#conditions)
  - [Rules](#rules)
    - [Rule Messages](#rule-messages)
    - [Referencing Fields](#referencing-fields)
    - [Referencing Nested Fields](#referencing-nested-fields)

Overview
--------
`pop-validator` is a component for validating values and returning the appropriate result messaging.
The component comes with a set of built-in evaluation objects and also the ability to extend the
component and build your own.

`pop-validator` is a component of the [Pop PHP Framework](https://www.popphp.org/).

[Top](#pop-validator)

Install
-------

Install `pop-validator` using Composer.

    composer require popphp/pop-validator

Or, require it in your composer.json file

    "require": {
        "popphp/pop-validator" : "^5.0.0"
    }

[Top](#pop-validator)

Quickstart
----------

Here's a list of the available built-in validators, all under the namespace `Pop\Validator\`:

|                                 | Built-in Validators                |                                |
|---------------------------------|------------------------------------|--------------------------------|
| Accepted                        | Alpha                              | AlphaNumeric                   |
| Between                         | BetweenInclude                     | Boolean                        |
| Contains                        | CountEqual                         | CountGreaterThan               |
| CountGreaterThanEqual           | CountLessThan                      | CountLessThanEqual             |
| CountNotEqual                   | CreditCard                         | DateTimeBetween                |
| DateTimeBetweenInclude          | DateTimeEqual                      | DateTimeGreaterThan            |
| DateTimeGreaterThanEqual        | DateTimeLessThan                   | DateTimeLessThanEqual          |
| DateTimeNotEqual                | Declined                           | Email                          |
| EndsWith                        | Equal                              | GreaterThan                    |
| GreaterThanEqual                | HasAtLeast                         | HasAtMost                      |
| HasCountEqual                   | HasCountGreaterThan                | HasCountGreaterThanEqual       |
| HasCountLessThan                | HasCountLessThanEqual              | HasCountNotEqual               |
| HasOne                          | HasOneDateTimeGreaterThan          | HasOneDateTimeGreaterThanEqual |
| HasOneDateTimeLessThan          | HasOneDateTimeLessThanEqual        | HasOneDateTimeThatEquals       |
| HasOneGreaterThan               | HasOneGreaterThanEqual             | HasOneIn                       |
| HasOneLessThan                  | HasOneLessThanEqual                | HasOneNotEmpty                 |
| HasOneThatContains              | HasOneThatEquals                   | HasOnlyOne                     |
| HasOnlyOneDateTimeGreaterThan   | HasOnlyOneDateTimeGreaterThanEqual | HasOnlyOneDateTimeLessThan     |
| HasOnlyOneDateTimeLessThanEqual | HasOnlyOneDateTimeThatEquals       | HasOnlyOneGreaterThan          |
| HasOnlyOneGreaterThanEqual      | HasOnlyOneLessThan                 | HasOnlyOneLessThanEqual        |
| HasOnlyOneThatContains          | HasOnlyOneThatEquals               | In                             |
| InArray                         | Ipv4                               | Ipv6                           |
| IsArray                         | IsEmpty                            | IsJson                         |
| IsNotEmpty                      | IsNotNull                          | IsNull                         |
| IsSubnetOf                      | Length                             | LengthBetween                  |
| LengthBetweenInclude            | LengthGreaterThan                  | LengthGreaterThanEqual         |
| LengthLessThan                  | LengthLessThanEqual                | LessThan                       |
| LessThanEqual                   | NotContains                        | NotEmpty                       |
| NotEndsWith                     | NotEqual                           | NotIn                          |
| NotInArray                      | NotStartsWith                      | Numeric                        |
| RegEx                           | Required                           | StartsWith                     |
| Subnet                          | Url                                |                                |

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

### Match against multiple patterns

`RegEx` also accepts an array of patterns as its value. By default, every pattern must match. Pass a
third constructor argument to require only a minimum number of them to match instead:

```php
// All patterns must match
$validator = new Pop\Validator\RegEx(['/[A-Z]/', '/[0-9]/']);
$validator->evaluate('Password1'); // Returns true - contains an uppercase letter and a digit
$validator->evaluate('password1'); // Returns false - missing the uppercase letter

// At least 1 of the 2 patterns must match
$validator = new Pop\Validator\RegEx(['/[A-Z]/', '/[0-9]/'], null, 1);
$validator->evaluate('password'); // Returns false - matches neither pattern
$validator->evaluate('Password'); // Returns true - matches the uppercase pattern
```

### Validate nested or related data

The `Has*` family of validators (`HasOne`, `HasOnlyOne`, `HasCountEqual`, `HasOneThatEquals`,
`HasOneThatContains`, their `DateTime` variants, and others) work differently from the rest of the built-in
validators. Instead of checking a single value, they're handed the *entire* input array and check for the
presence, count, or value of a field inside it - including fields nested in sub-arrays, addressed with dot
notation.

```php
$validator = new Pop\Validator\HasOne('users');

$data = [
    'users' => [
        ['username' => 'john_doe'],
    ],
];

$validator->evaluate($data); // Returns true - 'users' exists and has at least one item
```

Dot notation reaches into nested arrays:

```php
$validator = new Pop\Validator\HasOneThatEquals(['website_data.users.username' => 'john_doe']);

$data = [
    'website_data' => [
        'users' => [
            ['username' => 'john_doe'],
            ['username' => 'jane_doe'],
        ],
    ],
];

$validator->evaluate($data); // Returns true - at least one 'username' under website_data.users equals 'john_doe'
```

### Discover available validators

`ValidatorSet::getAvailableValidators()` returns every built-in validator class name mapped to its
`snake_case` rule name, which is useful for building dynamic forms or admin UIs:

```php
$validators = Pop\Validator\ValidatorSet::getAvailableValidators();
// ['Accepted' => 'accepted', 'Alpha' => 'alpha', 'AlphaNumeric' => 'alpha_numeric', ...]
```

[Top](#pop-validator)

Validation Sets
---------------

Validation sets are a way to group validators together to evaluate all of them at one time.
With that, a level of strictness can be set to enforce whether or not all the validations have
to pass or just some of them.

```php
use Pop\Validator\ValidatorSet;

$set = new ValidatorSet();
$set->addValidators(['username' => 'AlphaNumeric']);

if ($set->evaluate(['username' => 'username_123'])) {
    echo 'The username satisfies the requirements.' . PHP_EOL;
} else {
    print_r($set->getErrors());
}
```

#### Multiple Validators

```php
use Pop\Validator\ValidatorSet;

$set = new ValidatorSet();
$set->addValidators(['username' => ['AlphaNumeric' => null, 'LengthGreaterThanEqual' => 8]]);

if ($set->evaluate(['username' => 'username_123'])) {
    echo 'The username satisfies the requirements.' . PHP_EOL;
} else {
    print_r($set->getErrors());
}
```

#### Custom Messaging

```php
use Pop\Validator\ValidatorSet;

$set = new ValidatorSet();
$set->addValidators([
    'username' => [
        'AlphaNumeric' => [
            'value'   => null,
            'message' => 'The username can only contain alphanumeric characters.'
        ],
        'LengthGreaterThanEqual' => 8
    ]
]);

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
$set->addValidators(['username' => ['AlphaNumeric' => null, 'LengthGreaterThanEqual' => 8]]);
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
- `STRICT_BOTH` (default)

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

In the above example, if the value of `client_id` is changed to `2`, the validators will not be evaluated,
as the required conditions would not be satisfied.

[Top](#pop-validator)

### Rules

Rules provide a shorthand way to wire up validations and conditions within the validation set. Rules are
colon-separated strings compromised of a `field`, a `validator` and optional `value` and `message` values.
The validator should be a `snake_case` format of the class string, e.g. `HasOne` class should be written as
`has_one`.

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

#### Rule Messages

Custom messaging can be passed via the rule format as well, in the 4th position:

```php
$set = Pop\Validator\ValidatorSet::createFromRules([
    'username:length_gt:8:The username must be greater than 8 characters.'
]);
```

#### Referencing Fields

When writing rules, fields passed into the input data at the time of evaluating the data can be accessed
by using brackets:

```php
use Pop\Validator\ValidatorSet;

$set = ValidatorSet::createFromRules('value_1:equal:[value_2]');

$data = [
    'value_1' => 'test',
    'value_2' => 'test'
];

if ($set->evaluate($data)) {
    echo 'The data satisfies the requirements.' . PHP_EOL;
} else {
    print_r($set->getErrors());
}
```

#### Referencing Nested Fields

The bracket syntax above is for referencing another field's *value* inside a rule string. That's different
from pointing a validator at a *nested field to check*, which is done by calling `setField()` directly on a
validator instance with a `key[field]` bracket pattern: it tells the validator to check `$input[key][field]`
instead of the top-level field it would normally be handed.

```php
$validator = new Pop\Validator\Equal(1);
$validator->setField('user[active]');

$data = ['user' => ['active' => 1]];

$validator->evaluate($data); // Returns true - checks $data['user']['active']
```

[Top](#pop-validator)
