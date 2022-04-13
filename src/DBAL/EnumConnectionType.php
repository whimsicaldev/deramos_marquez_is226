<?php

namespace App\DBAL;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumConnectionType extends Type 
{
    const ENUM_CONNECTION = 'enumconnection';
    const STATUS_REQUESTED = 'requested';
    const STATUS_APPROVED = 'approved';
    const STATUS_DENIED = 'denied';
    const STATUS_BLOCKED = 'blocked';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string 
    {
        return "ENUM('requested', 'approved', 'denied', 'blocked')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed 
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed 
    {
        if (!in_array($value, array(self::STATUS_REQUESTED, self::STATUS_APPROVED, self::STATUS_DENIED, self::STATUS_BLOCKED))) {
            throw new \InvalidArgumentException("Invalid status");
        }
        return $value;
    }

    public function getName(): string
    {
        return self::ENUM_CONNECTION;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}