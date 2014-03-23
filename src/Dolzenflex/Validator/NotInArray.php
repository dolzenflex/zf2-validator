<?php


namespace Cougar\Outbound\Import\Validator;


use \Zend\Validator\InArray;

class NotInArray extends InArray
{
    const IN_ARRAY = 'notInArray';
   
    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::IN_ARRAY => 'The input was found in the haystack',
    );

    
    /**
     * Returns true if and only if $value is NOT  contained in the haystack option. If the strict
     * option is true, then the type of $value is also checked.
     *
     * @param mixed $value
     * See {@link http://php.net/manual/function.in-array.php#104501}
     * @return bool
     */
    public function isValid($value)
    {
        if (!parent::isValid($value)) {
            return true;
        }

        $this->error(self::IN_ARRAY);
        return false;
    }
}
