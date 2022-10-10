<?php

namespace PantheonSystems\Internal\Utility;

use Ramsey\Uuid\Uuid as RamseyUUID;

class Uuid
{
    public static function isUUID($string): bool
    {
        return RamseyUUID::isValid($string);
    }

    public static function createUUID(): string
    {
        return RamseyUUID::uuid4();
    }
}
