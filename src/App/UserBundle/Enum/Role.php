<?php

namespace App\UserBundle\Enum;

use Kf\KitBundle\Model\StaticEnum;

class Role extends StaticEnum
{
    const USER   = 'ROLE_USER';
    const ADMIN  = 'ROLE_ADMIN';

    static protected $enum = [
        self::USER     => 'user',
        self::ADMIN    => 'admin',
    ];

    protected static $labelClass = [
        self::USER   => 'label label-success',
        self::ADMIN  => 'label label-danger',
    ];

    public static function getLabelClass($key)
    {
        return isset(static::$labelClass[$key]) ? static::$labelClass[$key]
            : 'label label-default';
    }
}

