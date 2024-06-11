<?php

namespace App\Service\ExchangeRatesApi\Transfer;

abstract class AbstractTransfer
{
    public function fromArray(array $data): self
    {
        foreach ($data as $name => $value) {
            $this->{$name} = $this->_fromArray($name, $value);
        }

        return $this;
    }

    protected function _fromArray(string $propertyName, mixed $value): mixed
    {
        $reflectionProperty = new \ReflectionProperty(static::class, $propertyName);
        $propertyType = $reflectionProperty->getType()->getName();

        return match (true) {
            is_a($propertyType, AbstractTransfer::class, true) => (new $propertyType())->fromArray($value),
            default => $value,
        };
    }
}
