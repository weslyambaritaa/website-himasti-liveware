<?php

namespace App\Helper;

class ConstHelper
{
    const OPTION_ROLES = [
        'Admin',
        'Tim SPM',
    ];

    public static function getOptionRoles()
    {
        $roles = self::OPTION_ROLES;
        sort($roles);

        return $roles;
    }

    const OPTION_POSISI_TIM = [
        'Koordinator',
        'Anggota',
    ];

    public static function getOptionPosisiTim()
    {
        $posisi = self::OPTION_POSISI_TIM;
        sort($posisi);

        return $posisi;
    }
}
