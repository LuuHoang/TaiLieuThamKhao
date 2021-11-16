<?php


namespace App\Constants;


class ColumnExcelUser
{
    const ID = 'id';

    const STAFF_CODE = 'staff_code';

    const NAME = 'full_name';

    const EMAIL = 'email';

    const ADDRESS = 'address';

    const DEPARTMENT = 'department';

    const POSITION = 'position';

    const ROLE = 'role';

    const USER_MANAGER = 'user_manager';

    const TITLES = [
        self::ID => 'ID',
        self::STAFF_CODE => 'Staff Code',
        self::NAME => 'Name',
        self::EMAIL => 'Email',
        self::ADDRESS => 'Address',
        self::DEPARTMENT => 'Department',
        self::POSITION => 'Position',
        self::ROLE => 'Role',
        self::USER_MANAGER => 'User Manager'
    ];
}
