<?php

namespace App\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumCategoryType extends Type 
{
    const ENUM_CATEGORY = 'enumcategory';
    const CATEGORY_GROUP = 'group';
    const CATEGORY_EXPENSE = 'expense';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string 
    {
        return "ENUM('group', 'expense')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed 
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed 
    {
        if (!in_array($value, array(self::CATEGORY_GROUP, self::CATEGORY_EXPENSE))) {
            throw new \InvalidArgumentException("Invalid category");
        }
        return $value;
    }

    public function getName(): string
    {
        return self::ENUM_CATEGORY;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}