<?php

namespace Pop\Validator\Test;

use Pop\Validator;
use PHPUnit\Framework\TestCase;

class SetTest extends TestCase
{

    public function testGetAvailableValidators()
    {
        $validators = Validator\ValidatorSet::getAvailableValidators();
        $this->assertIsArray($validators);
        $this->assertTrue(array_key_exists('Accepted', $validators));
        $this->assertTrue(in_array('accepted', $validators));
    }

    public function testAdd1()
    {
        $set = Validator\ValidatorSet::add(['Equal' => 1], 'user_id');
        $this->assertTrue($set->hasValidators());
        $this->assertTrue($set->hasValidators('user_id'));
    }

    public function testAdd2()
    {
        $set = Validator\ValidatorSet::add(['user_id' => ['Equal' => 1]]);
        $this->assertTrue($set->hasValidators());
        $this->assertTrue($set->hasValidators('user_id'));
    }

    public function testLoad1()
    {
        $set = Validator\ValidatorSet::load([new Validator\Equal(1)], 'user_id');
        $this->assertTrue($set->hasLoadedValidators());
        $this->assertTrue($set->hasLoadedValidators('user_id'));
    }

    public function testLoad2()
    {
        $set = Validator\ValidatorSet::load(['user_id' => [new Validator\Equal(1)]]);
        $this->assertTrue($set->hasLoadedValidators());
        $this->assertTrue($set->hasLoadedValidators('user_id'));
    }

    public function testCreateFromRules()
    {
        $set = Validator\ValidatorSet::createFromRules([
            'user_id:equal:1',
            'logins:greater_than:0'
        ]);
        $this->assertTrue($set->hasValidators());
        $this->assertTrue($set->hasValidators('user_id'));
    }

    public function testAddValidatorsFromRules()
    {
        $set = new Validator\ValidatorSet();
        $set->addValidatorsFromRules([
            'user_id:equal:1',
            'logins:greater_than:0'
        ]);
        $this->assertTrue($set->hasValidators());
        $this->assertTrue($set->hasValidators('user_id'));
        $this->assertTrue($set->hasValidators('logins'));
        $this->assertCount(2, $set->getValidators());
        $this->assertCount(1, $set->getValidators('user_id'));
        $this->assertCount(1, $set->getValidators('logins'));
    }

    public function testAddValidators1()
    {
        $set = new Validator\ValidatorSet();
        $set->addValidators(['user_id' => [
            'equal' => 1
        ]]);
        $this->assertCount(1, $set->getValidators());
        $this->assertCount(1, $set->getValidators('user_id'));
    }

    public function testAddValidators2()
    {
        $set = new Validator\ValidatorSet();
        $set->addValidators(['user_id' => [
            'equal' => ['value' => 1, 'message' => 'User ID must equal 1.']
        ]]);
        $this->assertCount(1, $set->getValidators());
        $this->assertCount(1, $set->getValidators('user_id'));
    }

    public function testAddValidators3()
    {
        $set = new Validator\ValidatorSet();
        $set->addValidators(['user_id' => 'NotEmpty']);
        $this->assertCount(1, $set->getValidators());
        $this->assertCount(1, $set->getValidators('user_id'));
    }

    public function testAddValidatorsToField1()
    {
        $set = new Validator\ValidatorSet();
        $set->addValidatorsToField('user_id', [
            'equal' => 1
        ]);
        $this->assertCount(1, $set->getValidators());
        $this->assertCount(1, $set->getValidators('user_id'));
    }

    public function testAddValidatorsToField2()
    {
        $set = new Validator\ValidatorSet();
        $set->addValidatorsToField('user_id', [
            'equal' => ['value' => 1, 'message' => 'User ID must equal 1.']
        ]);
        $this->assertCount(1, $set->getValidators());
        $this->assertCount(1, $set->getValidators('user_id'));
    }

    public function testAddValidatorException()
    {
        $this->expectException('InvalidArgumentException');
        $set = new Validator\ValidatorSet();
        $set->addValidator('user_id', 'Bad');
    }

    public function testLoadValidators1()
    {
        $set = new Validator\ValidatorSet();
        $set->loadValidators(['user_id' => [new Validator\Equal(1)]]);
        $this->assertCount(1, $set->getLoadedValidators());
        $this->assertCount(1, $set->getLoadedValidators('user_id'));
    }

    public function testLoadValidators2()
    {
        $set = new Validator\ValidatorSet();
        $set->loadValidator('value_1', new Validator\Equal('[value_2]'), ['value_1' => 1, 'value_2' => 1]);
        $this->assertCount(1, $set->getLoadedValidators());
        $this->assertCount(1, $set->getLoadedValidators('value_1'));
    }

    public function testLoadValidatorsToField()
    {
        $set = new Validator\ValidatorSet();
        $set->loadValidatorsToField('user_id', [new Validator\Equal(1)]);
        $this->assertCount(1, $set->getLoadedValidators());
        $this->assertCount(1, $set->getLoadedValidators('user_id'));
        $this->assertTrue($set->hasLoadedValidators());
        $this->assertTrue($set->hasLoadedValidators('user_id'));
        $this->assertTrue($set->isLoaded());
        $this->assertTrue($set->isLoaded('user_id'));
    }

    public function testAddConditions()
    {
        $set = new Validator\ValidatorSet();
        $set->addConditions([
            'user_id' => Validator\Condition::createFromRule('user_id:equal:1'),
            'logins'  => Validator\Condition::createFromRule('logins:greater_than:0')
        ]);
        $this->assertCount(2, $set->getConditions());
        $this->assertTrue($set->hasConditions());
    }

    public function testAddConditionsFromRules()
    {
        $set = new Validator\ValidatorSet();
        $set->addConditionsFromRules([
            'user_id:equal:1',
            'logins:greater_than:0'
        ]);
        $this->assertCount(2, $set->getConditions());
    }

    public function testStrict()
    {
        $set = new Validator\ValidatorSet();
        $set->setStrict(Validator\ValidatorSet::STRICT_BOTH);
        $this->assertTrue($set->isStrict());
        $this->assertEquals(Validator\ValidatorSet::STRICT_BOTH, $set->getStrict());
    }

    public function testStrictException()
    {
        $this->expectException('InvalidArgumentException');
        $set = new Validator\ValidatorSet();
        $set->setStrict(-1);
    }

    public function testEvaluate1()
    {
        $set = new Validator\ValidatorSet();
        $set->addValidatorsFromRules([
            'user_id:equal:1',
            'logins:greater_than:0'
        ]);

        $this->assertFalse($set->isEvaluated());

        $result = $set->evaluate([
            'user_id' => 1,
            'logins' => 1
        ]);

        $this->assertTrue($result);
        $this->assertFalse($set->hasErrors());
        $this->assertFalse($set->hasErrors('user_id'));
        $this->assertFalse($set->hasErrors('logins'));

        $this->assertCount(2, $set->getEvaluated());
        $this->assertTrue($set->getEvaluated('user_id')[0]);
        $this->assertTrue($set->getEvaluated('logins')[0]);
        $this->assertEquals(1, $set->getValidationStatus());
    }

    public function testEvaluate2()
    {
        $set = new Validator\ValidatorSet();
        $set->addValidatorsFromRules([
            'user_id:equal:1',
            'logins:greater_than:0'
        ]);

        $result = $set->evaluate([
            'user_id' => 2,
            'logins' => 0
        ]);

        $this->assertFalse($result);
        $this->assertTrue($set->hasErrors());
        $this->assertTrue($set->hasErrors('user_id'));
        $this->assertTrue($set->hasErrors('logins'));
        $this->assertCount(2, $set->getEvaluated());
        $this->assertFalse($set->getEvaluated('user_id')[0]);
        $this->assertFalse($set->getEvaluated('logins')[0]);
        $this->assertCount(2, $set->getErrors());
        $this->assertCount(1, $set->getErrors('user_id'));
        $this->assertCount(1, $set->getErrors('logins'));
        $this->assertEquals(0, $set->getValidationStatus());
    }



    public function testEvaluateSome()
    {
        $set = new Validator\ValidatorSet();
        $set->setStrict(Validator\ValidatorSet::STRICT_NONE);
        $set->addValidatorsFromRules([
            'user_id:equal:1',
            'logins:greater_than:0'
        ]);

        $result = $set->evaluate([
            'user_id' => 1,
            'logins' => 0
        ]);

        $this->assertEquals(2, $set->getValidationStatus());
    }

    public function testEvaluatePostLoad1()
    {
        $input = [
            'user_id' => 1,
            'logins' => 1
        ];

        $set = new Validator\ValidatorSet();
        $set->addValidatorsFromRules(['user_id:equal:1']);
        $set->loadValidators(null, $input);
        $set->addValidatorsFromRules(['logins:greater_than:0']);

        $result = $set->evaluate($input);

        $this->assertTrue($result);
        $this->assertFalse($set->hasErrors());
        $this->assertFalse($set->hasErrors('user_id'));
        $this->assertFalse($set->hasErrors('logins'));

        $this->assertCount(2, $set->getEvaluated());
        $this->assertTrue($set->getEvaluated('user_id')[0]);
        $this->assertTrue($set->getEvaluated('logins')[0]);
        $this->assertEquals(1, $set->getValidationStatus());
    }


    public function testEvaluatePostLoad2()
    {
        $input = [
            'user_id' => 1,
            'logins' => 0
        ];

        $set = new Validator\ValidatorSet();
        $set->addValidatorsFromRules(['user_id:equal:1']);
        $set->loadValidators(null, $input);
        $set->addValidatorsFromRules(['logins:greater_than:0']);

        $result = $set->evaluate($input);

        $this->assertFalse($result);
        $this->assertTrue($set->hasErrors());
        $this->assertFalse($set->hasErrors('user_id'));
        $this->assertTrue($set->hasErrors('logins'));
    }

    public function testEvaluateWithConditions()
    {
        $set = new Validator\ValidatorSet();
        $set->addConditionsFromRules([
            'user_id:equal:1',
        ]);

        $set->addValidatorsFromRules([
            'username:not_empty',
            'logins:greater_than:0'
        ]);

        $this->assertFalse($set->isEvaluated());

        $result = $set->evaluate([
            'user_id' => 1,
            'username' => 'john_doe',
            'logins' => 1
        ]);

        $this->assertTrue($result);
        $this->assertFalse($set->hasErrors());
        $this->assertFalse($set->hasErrors('username'));
        $this->assertFalse($set->hasErrors('logins'));

        $this->assertCount(2, $set->getEvaluated());
        $this->assertTrue($set->getEvaluated('username')[0]);
        $this->assertTrue($set->getEvaluated('logins')[0]);
        $this->assertEquals(1, $set->getValidationStatus());
        $this->assertEquals(1, $set->getConditionStatus());
    }

    public function testEvaluateWithSomeConditions1()
    {
        $set = new Validator\ValidatorSet();
        $set->setStrict(Validator\ValidatorSet::STRICT_VALIDATIONS_ONLY);
        $set->addConditionsFromRules([
            'user_id:equal:1',
            'country:equal:US',
        ]);

        $set->addValidatorsFromRules([
            'username:not_empty',
            'logins:greater_than:0'
        ]);

        $this->assertFalse($set->isEvaluated());

        $result = $set->evaluate([
            'user_id' => 1,
            'country' => 'UK',
            'username' => 'john_doe',
            'logins' => 1
        ]);

        $this->assertTrue($result);
        $this->assertFalse($set->hasErrors());
        $this->assertFalse($set->hasErrors('username'));
        $this->assertFalse($set->hasErrors('logins'));

        $this->assertCount(2, $set->getEvaluated());
        $this->assertTrue($set->getEvaluated('username')[0]);
        $this->assertTrue($set->getEvaluated('logins')[0]);
        $this->assertEquals(1, $set->getValidationStatus());
        $this->assertEquals(2, $set->getConditionStatus());
    }

    public function testEvaluateWithSomeConditions2()
    {
        $set = new Validator\ValidatorSet();
        $set->setStrict(Validator\ValidatorSet::STRICT_VALIDATIONS_ONLY);
        $set->addConditionsFromRules([
            'user_id:equal:1',
            'country:equal:US',
        ]);

        $set->addValidatorsFromRules([
            'username:not_empty',
            'logins:greater_than:0'
        ]);

        $this->assertFalse($set->isEvaluated());

        $result = $set->evaluate([
            'user_id' => 2,
            'country' => 'UK',
            'username' => 'john_doe',
            'logins' => 1
        ]);

        $this->assertTrue($result);
    }

}
