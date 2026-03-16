<?php

declare(strict_types=1);

namespace SergeR\RussianPostSDK\Dto;

trait HasKebabCaseSerialization
{
    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        $result = [];
        foreach ((new \ReflectionClass($this))->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            $value = $prop->getValue($this);
            if ($value === null) {
                continue; // omit null fields
            }
            // camelCase → kebab-case
            $propName = $prop->getName();
            $key = strtolower(preg_replace('/[A-Z]/', '-$0', lcfirst($propName)) ?? '');
            $result[$key] = match (true) {
                $value instanceof \BackedEnum => $value->value,
                is_object($value) && method_exists($value, 'toArray') => $value->toArray(),
                is_array($value) => array_map(
                    static fn($v) => is_object($v) && method_exists($v, 'toArray') ? $v->toArray() : ($v instanceof \BackedEnum ? $v->value : $v),
                    $value
                ),
                default => $value,
            };
        }
        return $result;
    }
}
