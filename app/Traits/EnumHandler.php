<?php

namespace App\Traits;

use BackedEnum;
use InvalidArgumentException;

trait EnumHandler
{
    protected static function createFromString(string $value, string $enumClass): BackedEnum
    {
        try {
            if (!class_exists($enumClass) || !is_a($enumClass, BackedEnum::class, true)) {
                throw new InvalidArgumentException("Invalid enum class: {$enumClass}");
            }

            $cases = array_map(fn($case) => $case->value, $enumClass::cases());
            
            if (!in_array(strtolower($value), array_map('strtolower', $cases))) {
                throw new InvalidArgumentException(
                    "Invalid value: {$value}. Must be one of: " . implode(', ', $cases)
                );
            }

            foreach ($enumClass::cases() as $case) {
                if (strtolower($case->value) === strtolower($value)) {
                    return $case;
                }
            }

            throw new InvalidArgumentException("Could not create enum from value: {$value}");
        } catch (\Throwable $e) {
            throw new InvalidArgumentException("Error creating enum: " . $e->getMessage());
        }
    }
}