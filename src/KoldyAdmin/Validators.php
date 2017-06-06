<?php declare(strict_types=1);

namespace KoldyAdmin;

use KoldyAdmin\Db\AdminAccount;

/**
 * Class Validators - All common validators in one place
 * @package KoldyAdmin
 */
class Validators
{

    /**
     * @return string
     */
    public static function screenHeight(): string
    {
        return 'required|integer|min:100|max:3000';
    }

    /**
     * @return string
     */
    public static function screenWidth(): string
    {
        return 'required|integer|min:100|max:9000';
    }

    /**
     * Validator for admin account's password input
     *
     * @return string
     */
    public static function adminAccountPassword(): string
    {
        $minLength = AdminAccount::PASSWORD_MIN_LENGTH;
        $maxLength = AdminAccount::PASSWORD_MAX_LENGTH;
        return "required|minLength:{$minLength}|maxLength:{$maxLength}";
    }

    /**
     * Validator for admin account's first and last name
     *
     * @return string
     */
    public static function adminAccountName(): string
    {
        $minLength = AdminAccount::NAME_MIN_LENGTH;
        $maxLength = AdminAccount::NAME_MAX_LENGTH;
        return "required|minLength:{$minLength}|maxLength:{$maxLength}";
    }

}