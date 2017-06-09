<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use Koldy\Db\Model;
use Koldy\Util;
use KoldyAdmin\Db\Traits\AccountTrait;

/**
 * Class AdminEmailConfirmation
 * @package KoldyAdmin\Db
 * @property int account_id
 * @property int email_id
 * @property string auth1
 * @property string auth2
 */
class AdminEmailConfirmation extends Model
{

    protected static $table = 'admin_email_confirmation';

    protected static $adapter = 'admin';

    protected static $primaryKey = ['account_id', 'email_id'];

    protected static $autoIncrement = false;

    private const HASH_LENGTH = 24;

    /**
     * @var AdminEmail|null
     */
    private $email = null;

    use AccountTrait;

    /**
     * @return int
     */
    public function getEmailId(): int
    {
        return (int)$this->email_id;
    }

    /**
     * Fetch the admin email record from database on every method call
     *
     * @return AdminEmail
     */
    public function fetchAdminEmail(): AdminEmail
    {
        /** @var AdminEmail $adminEmail */
        $adminEmail = AdminEmail::fetchOneOrFail($this->getEmailId());
        return $adminEmail;
    }

    /**
     * Fetch admin email from database, but only once
     *
     * @return AdminEmail
     */
    public function getAdminEmail(): AdminEmail
    {
        if ($this->email === null) {
            $this->email = $this->fetchAdminEmail();
        }

        return $this->email;
    }

    /**
     * @return string
     */
    public function getAuth1(): string
    {
        return $this->auth1;
    }

    /**
     * @return string
     */
    public function getAuth2(): string
    {
        return $this->auth2;
    }

    /**
     * Create confirmation record
     *
     * @param int $accountId
     * @param int $emailId
     *
     * @return AdminEmailConfirmation
     */
    public static function createConfirmation(int $accountId, int $emailId): self
    {
        do {
            $auth1 = Util::randomString(self::HASH_LENGTH);
            $auth2 = Util::randomString(self::HASH_LENGTH);
        } while (static::count(['auth1' => $auth1, 'auth2' => $auth2]) > 0);

        /** @var self $record */
        $record = static::create([
          'account_id' => $accountId,
          'email_id' => $emailId,
          'auth1' => $auth1,
          'auth2' => $auth2
        ]);

        return $record;
    }

    public function __toString()
    {
        return "AdminEmailConfirmation account_id={$this->getAccountId()} email_id={$this->getEmailId()}";
    }
}
