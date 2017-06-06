<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use DateTime;
use DateTimeZone;
use JsonSerializable;
use Koldy\Db\Model;
use KoldyAdmin\Db\Traits\CreatedAtTrait;

/**
 * Class AdminAccountInvitation
 * @package KoldyAdmin\Db
 * @property int id
 * @property int invited_by
 * @property string email
 * @property int group_id
 * @property string auth
 * @property string created_at
 * @property string accepted_at
 */
class AdminAccountInvitation extends Model implements JsonSerializable
{
    protected static $table = 'admin_account_invitation';

    protected static $adapter = 'admin';

    use CreatedAtTrait;

    /**
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @return int
     */
    public function getInvitedBy(): int
    {
        return (int)$this->invited_by;
    }

    /**
     * @return AdminAccount
     */
    public function getInvitedByAccount(): AdminAccount
    {
        /** @var AdminAccount $adminAccount */
        $adminAccount = AdminAccount::fetchOneOrFail($this->getInvitedBy());
        return $adminAccount;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getGroupId(): int
    {
        return (int)$this->group_id;
    }

    /**
     * @return AdminGroup
     */
    public function getGroup(): AdminGroup
    {
        return AdminGroup::fetchOneOrFail($this->getGroupId());
    }

    /**
     * @return string
     */
    public function getAuth(): string
    {
        return $this->auth;
    }

    /**
     * @return string
     */
    public function getAcceptedAt(): ?string
    {
        return $this->accepted_at;
    }

    /**
     * @return DateTime|null
     */
    public function getAcceptedAtDateTime(): ?DateTime
    {
        if ($this->accepted_at === null) {
            return null;
        }

        return new DateTime(new DateTimeZone('UTC'));
    }

    /**
     * @return bool
     */
    public function isAccepted(): bool
    {
        return $this->accepted_at !== null;
    }

    public function __toString()
    {
        return "Invitation #{$this->getId()} email={$this->getEmail()}";
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
          'id' => $this->getId(),
          'invited_by' => $this->getInvitedBy(),
          'email' => $this->getEmail(),
          'group_id' => $this->getGroupId(),
          'created_at' => $this->getCreatedAt(),
          'accepted_at' => $this->getAcceptedAt()
        ];
    }
}
