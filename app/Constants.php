<?php


namespace App;


class Constants
{
    const ACITVE = 'active';
    const INACITVE = 'inactive';

    const ADMIN = 'admin';
    const MODERATOR = 'moderator';
    const CREATOR = 'creator';

    const ROLES = [
        self::ADMIN,
        self::MODERATOR,
        self::CREATOR
    ];

    const STATUSES = [
        self::ACITVE,
        self::INACITVE
    ];

}
