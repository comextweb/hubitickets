<?php

namespace App\Constants;

class UserConstants
{
    // Códigos de roles
    public const ROLE_ADMIN = 'admin';
    public const ROLE_AGENT = 'agent';
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_CODE_AGENT_ADMIN = 'AGT_ADM';

    // Roles no editables
    public const NON_EDITABLE_ROLES = [
        self::ROLE_AGENT,
        self::ROLE_CUSTOMER,
        self::ROLE_ADMIN,
    ];
}
