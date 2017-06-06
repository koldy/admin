<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use DateTime;
use JsonSerializable;
use Koldy\Db\Model;
use Koldy\Db\Query\ResultSet;

/**
 * Class AdminGroup
 * @package KoldyAdmin\Db
 * @property int id
 * @property string name
 * @property string key_name
 * @property int grant_all
 * @property string description
 * @property string created_at
 * @property int created_by
 */
class AdminGroup extends Model implements JsonSerializable
{

    protected static $table = 'admin_group';

    protected static $adapter = 'admin';

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->key_name;
    }

    /**
     * @return bool
     */
    public function hasGrantAll(): bool
    {
        return (int) $this->grant_all === 1;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
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
    public function getCreatedAtDatetime(): ?DateTime
    {
        if ($this->created_at === null) {
            return null;
        }

        return new DateTime($this->getCreatedAt());
    }

    /**
     * @return int
     */
    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    /**
     * @return AdminAccount
     */
    public function getCreatedByAccount(): AdminAccount
    {
        /** @var AdminAccount $adminAccount */
        $adminAccount = AdminAccount::fetchOneOrFail($this->getCreatedBy());
        return $adminAccount;
    }

    /**
     * @return ResultSet
     */
    public static function getAdminGroupListResultSet(): ResultSet
    {
        $thisTable = static::getTableName();


        /** @var ResultSet $resultSet */
        $resultSet = static::resultSet()
          ->fields(['id', 'name', 'key_name', 'grant_all', 'description', 'created_at', 'created_by'], $thisTable)

          ->leftJoin('admin_account aa2', 'aa2.group_id', '=', "{$thisTable}.id")
          ->field('COUNT(aa2.id)', 'users_in_group')

          ->groupBy("{$thisTable}.id")

          ->setSearchFields(["{$thisTable}.key_name", "{$thisTable}.name", "{$thisTable}.description"])
          ->resetGroupByOnCount();

        return $resultSet;
    }

    /**
     * Get all access control items as key => array(key => value) for this group
     *
     * @return array
     */
    public function getAcl(): array
    {
        $acl = [];

        /** @var AdminGroupAcl[] $list */
        $list = AdminGroupAcl::fetch(['group_id' => $this->getId()]);
        foreach ($list as $a) {
            $group = $a->getAclGroup();
            $key = $a->getAclKey();

            if (!isset($acl[$group])) {
                $acl[$group] = [];
            }

            $acl[$group][] = $key;
        }

        return $acl;
    }

    public function __toString()
    {
        return "AdminGroup #{$this->getId()} '{$this->getName()}'";
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
          'name' => $this->getName(),
          'key_name' => $this->getKeyName(),
          'grant_all' => $this->hasGrantAll(),
          'description' => $this->getDescription(),
          'created_at' => $this->getCreatedAt(),
          'created_by' => $this->getCreatedBy()
        ];
    }
}