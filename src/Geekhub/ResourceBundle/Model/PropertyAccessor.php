<?php

namespace Geekhub\ResourceBundle\Model;

trait PropertyAccessor
{
    public function __call($method, $arguments)
    {
        $property = substr($method, 3, strlen($method));
        $property = lcfirst($property);

        if (!property_exists(__CLASS__, $property)) {
            throw new \BadMethodCallException(
                "Undefined property '$property'!"
            );
        } elseif ('get' == substr($method, 0, 3)) {
            return $this->$property;
        } elseif ('set' == substr($method, 0, 3)) {
            $this->$property = $arguments[0];

            return $this;
        } else {
            throw new \BadMethodCallException(
                "Undefined method '$method'. The method name must start with " .
                "either add, set or get!"
            );
        }
    }
}
