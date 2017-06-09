<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use DateTime;
use JsonSerializable;
use Koldy\Db\Model;
use KoldyAdmin\Db\Traits\AccountTrait;
use KoldyAdmin\Db\Traits\CreatedAtTrait;

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
class AdminEmail extends Model implements JsonSerializable
{

    protected static $table = 'admin_email';

    protected static $adapter = 'admin';

    use AccountTrait;
    use CreatedAtTrait;

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
     * @return null|string
     */
    public function getVerifiedAt(): ?string
    {
        return $this->verified_at;
    }

    /**
     * @return DateTime
     */
    public function getVerifiedAtDateTime(): ?DateTime
    {
        if ($this->verified_at === null) {
            return null;
        }

        return new DateTime($this->getVerifiedAt());
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    /**
     * @return int
     */
    public function destroy(): int
    {
        $data = $this->getData();
        unset($data['id']);

        AdminEmailArchive::create($data);

        return parent::destroy();
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
          'email' => $this->getEmail(),
          'md5' => md5($this->getEmail()),
          'is_primary' => $this->isPrimary(),
          'created_at' => $this->getCreatedAt(),
          'verified_at' => $this->getVerifiedAt()
        ];
    }

    public function __toString()
    {
        return "AdminEmail #{$this->getId()} email={$this->getEmail()}";
    }
}