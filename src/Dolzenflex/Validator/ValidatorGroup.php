<?php
namespace Cougar\Outbound\Import\Validator;

use \Zend\Validator\ValidatorChain;
use \Zend\Validator\ValidatorInterface;
use \Zend\Stdlib\Exception\RuntimeException;

class ValidatorGroup extends ValidatorChain
{

    const ADD_OR = 'or';

    const ADD_AND = 'and';

    public function attach(ValidatorInterface $validator, $andOr = false, $key = null)
    {
        $this->validators[] = array(
            'instance' => $validator,
            'breakChainOnFailure' => false,
            'andOr' => $andOr,
            'key' => $key,
           
        );
        return $this;
    }

    public function prependValidator(ValidatorInterface $validator, $andOr = self::ADD_AND, $key = null)
    {
        array_unshift($this->validators, array(
            'instance' => $validator,
            'breakChainOnFailure' => false,
            'andOr' => $andOr,
            'key' => $key,
           
        ));
        return $this;
    }

    public function attachByName($name, $options = array(), $andOr = self::ADD_AND, $key = null)
    {
        $validator = $this->plugin($name, $options);
        $this->attach($validator, $andOr, $key);
        return $this;
    }

    public function prependByName($name, $options = array(), $andOr = self::ADD_AND, $key = null)
    {
        $validator = $this->plugin($name, $options);
        $this->prependValidator($validator, $andOr, $key);
        return $this;
    }

    public function isValid($value, $context = null)
    {
        $this->messages = array();
        $results = true;
        foreach ($this->validators as $element) {
            
            /*
             * chech if value is array and try to extract the configured key
             */
            if ( is_array($value ) ) {
                if (isset($element['key']) && isset($value[$element['key']])) {
                    $keyValue = $value[$element['key']];
                } else {
                    throw new RuntimeException("The key configured to be validate ({$element['key']})is not present in the value");
                }
            } else {
                $keyValue = $value;
            }
            
            /*
             * validate the value
             */
            $validator = $element['instance'];
            $isValid = $validator->isValid($keyValue, $context);
                      
            
            /*
             * update the chain result
             */
            if (self::ADD_AND == $element['andOr']) {
                $results = $results && $isValid;
            } else {
                $results = $results || $isValid;
            }
                      
           
            if ($isValid) {
                continue;
            }
            
            /*
             * if the not valid add erro message
             */
            $messages = $validator->getMessages();
            $this->messages = array_replace_recursive($this->messages, $messages);
        }
        
        return $results;
    }
}