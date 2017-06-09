<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use DateTime;
use JsonSerializable;
use Koldy\Application;
use Koldy\Db\Model;
use Koldy\Db\Query\ResultSet;
use Koldy\Log;
use Koldy\Mail;
use KoldyAdmin\AdminAccount\Meta;
use KoldyAdmin\Config;
use KoldyAdmin\Exception;

/**
 * Class Admin
 * @package KoldyAdmin\Db
 * @property int id
 * @property string first_name
 * @property string last_name
 * @property int group_id
 * @property string password
 * @property int failed_login_attempts
 * @property string created_at
 * @property string last_seen_on
 * @property string last_seen_at
 * @property string deactivated_at
 */
class AdminAccount extends Model implements JsonSerializable
{

    protected static $table = 'admin_account';

    protected static $adapter = 'admin';

    public const MAX_FAILED_LOGINS = 5;

    public const PASSWORD_MIN_LENGTH = 8;
    public const PASSWORD_MAX_LENGTH = 100;
    public const NAME_MIN_LENGTH = 2;
    public const NAME_MAX_LENGTH = 80;

    /**
     * @var AdminEmail[]
     */
    private $emails = null;

    /**
     * @var Meta
     */
    private $meta = null;

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
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @return null|string
     */
    public function getFullName(): ?string
    {
        $parts = [];

        $firstName = $this->getFirstName();
        $lastName = $this->getLastName();

        if ($firstName !== null) {
            $parts[] = $firstName;
        }

        if ($lastName !== null) {
            $parts[] = $lastName;
        }

        if (count($parts) > 0) {
            return implode(' ', $parts);
        }

        return null;
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
        /** @var AdminGroup $adminGroup */
        $adminGroup = AdminGroup::fetchOne($this->getGroupId());
        return $adminGroup;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return int
     */
    public function getFailedLoginAttempts(): int
    {
        return $this->failed_login_attempts;
    }

    /**
     * @return bool
     */
    public function hasReachedMaxLoginAttempts(): bool
    {
        return $this->getFailedLoginAttempts() >= self::MAX_FAILED_LOGINS;
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        if ($this->created_at === null) {
            return null;
        }

        return $this->created_at;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAtDateTime(): ?DateTime
    {
        if ($this->created_at === null) {
            return null;
        }

        return new DateTime($this->getCreatedAt());
    }

    /**
     * @return string
     */
    public function getLastSeenOn(): ?string
    {
        return $this->last_seen_on;
    }

    /**
     * @return DateTime
     */
    public function getLastSeenAt(): ?DateTime
    {
        if ($this->last_seen_at === null) {
            return null;
        }

        return new DateTime($this->last_seen_at);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->deactivated_at === null;
    }

    /**
     * @return bool
     */
    public function isDeactivated(): bool
    {
        return $this->deactivated_at !== null;
    }

    /**
     * @return string
     */
    public function getDeactivatedAt(): ?string
    {
        if ($this->isActive()) {
            return null;
        }

        return $this->deactivated_at;
    }

    /**
     * @return DateTime
     */
    public function getDeactivatedAtDateTime(): ?DateTime
    {
        if ($this->isActive()) {
            return null;
        }

        return new DateTime($this->getDeactivatedAt());
    }

    /**
     * @return AdminEmail[]
     */
    public function getEmails(): array
    {
        if ($this->emails === null) {
            /** @var AdminEmail[] emails */
            $this->emails = AdminEmail::fetch(['account_id' => $this->getId()]);
        }

        return $this->emails;
    }

    /**
     * @return AdminEmail
     * @throws Exception
     */
    public function getPrimaryEmail(): AdminEmail
    {
        foreach ($this->getEmails() as $adminEmail) {
            if ($adminEmail->isPrimary()) {
                return $adminEmail;
            }
        }

        throw new Exception('Can not find primary admin email for account #' . $this->getId());
    }

    /**
     * Send text to all emails of this account
     *
     * @param string $subject
     * @param string $text
     *
     * @return AdminEmail[]
     */
    public function notifyAllEmails(string $subject, string $text): array
    {
        $emails = $this->getEmails();
        $sentTo = [];

        foreach ($emails as $email) {
            if ($email->isVerified()) {
                Mail::create()
                  ->to($email->getEmail())
                  ->from(Config::noReplyEmail())
                  ->subject($subject)
                  ->body($text)
                  ->send();

                $sentTo[] = $email;
            }
        }

        return $sentTo;
    }

    /**
     * @param bool $load
     *
     * @return Meta
     */
    public function meta(bool $load = true): Meta
    {
        if ($this->meta === null) {
            $this->meta = new Meta($this, $load);
        } else {
            if ($load && !$this->meta->isLoaded()) {
                $this->meta->load();
            }
        }

        return $this->meta;
    }

    /**
     * @return ResultSet
     */
    public function getAccountsListResultSet(): ResultSet
    {
        /** @var ResultSet $resultSet */
        $resultSet = static::resultSet()
          ->innerJoin('admin_group', 'admin_group.id', '=', 'admin_account.group_id')
          ->field('admin_account.id')
          ->field('admin_account.first_name')
          ->field('admin_account.last_name')
          ->field('admin_account.failed_login_attempts')
          ->field('admin_account.deactivated_at')
          ->field('admin_group.name', 'group_name');

        return $resultSet;
    }

    /**
     * @return string
     */
    public function getStorageDirectory(): string
    {
        return Application::getStoragePath("account/{$this->getId()}/");
    }

    public function __toString()
    {
        return "Admin #{$this->getId()} name={$this->getFullName()} group_id={$this->getGroupId()}";
    }

    /**
     * Use this account as "who" identifier in logs
     */
    public function useInLogs(): void
    {
        Log::setWho(trim("#{$this->getId()} {$this->getFullName()}"));
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
          'first_name' => $this->getFirstName(),
          'last_name' => $this->getLastName(),
          'full_name' => $this->getFullName(),
          'group_id' => $this->getGroupId(),
          'created_at' => $this->getCreatedAt(),
          'deactivated_at' => $this->getDeactivatedAt()
        ];
    }
}
