<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use DateTime;
use Koldy\Db\Model;
use KoldyAdmin\Db\Traits\AccountTrait;

/**
 * Class AdminEmail
 * @package KoldyAdmin\Db
 * @property int id
 * @property int account_id
 * @property string email
 * @property int is_primary
 * @property string created_at
 * @property string verified_at
 */
class AdminEmailArchive extends Model
{

    protected static $table = 'admin_email_archive';

    protected static $adapter = 'admin';

    use AccountTrait;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return (int)$this->is_primary == 1;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }

    /**
     * @return DateTime
     */
    public function getVerifiedAt(): ?DateTime
    {
        if ($this->verified_at === null) {
            return null;
        }

        return new DateTime($this->verified_at);
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

}