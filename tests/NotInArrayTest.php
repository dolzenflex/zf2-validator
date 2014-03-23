<?php

class NotInArrayTest extends PHPUnit_Framework_TestCase
{

    private $validator;

    function setup()
    {
        $haystack = array(
            'a',
            'b',
            1,
            2
        );
        $this->validator = new Dolzenflex\Validator\NotInArray();
        $this->validator->setHaystack($haystack);
    }

    function testReturnTreWhenIsValid()
    {
        $value = 'b';
        $this->assertEquals(false, $this->validator->isValid($value));
    }

    function testReturnFalseWhenIsNotValid()
    {
        $value = 7;
        $this->assertEquals(true, $this->validator->isValid($value));
    }
}
