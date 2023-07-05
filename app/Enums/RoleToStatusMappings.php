<?php
namespace App\Enums;

final class RoleToStatusMappings
{
    public static function getEnumArray(): array
    {
        return [
            'reviewer' => 'Reviewing',
            'registrant' => 'Registering',
        ];
    }

    public const BASIC_STATUS = 'Basic';
}
