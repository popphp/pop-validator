<?php

namespace Pop\Validator\Test;

use Pop\Validator;
use PHPUnit\Framework\TestCase;

class SetTest extends TestCase
{

    public function testSet()
    {
        $set = new Validator\ValidatorSet();
        $this->assertInstanceOf(Validator\ValidatorSet::class, $set);
    }

}
