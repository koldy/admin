<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use Koldy\Db\Model;

/**
 * Class AdminGroup
 * @package KoldyAdmin\Db
 * @property int group_id
 * @property string acl_group
 * @property string acl_key
 */
class AdminGroupAcl extends Model
{

    protected static $table = 'admin_group_acl';

    protected static $adapter = 'admin';

    protected static $primaryKey = ['group_id', 'acl_group', 'acl_key'];

    /**
     * @return int
     */
    public function getGroupId(): int
    {
        return (int)$this->group_id;
    }

    /**
     * @return string
     */
    public function getAclGroup(): string
    {
        return $this->acl_group;
    }

    /**
     * @return string
     */
    public function getAclKey(): string
    {
        return $this->acl_key;
    }

    public function __toString()
    {
        return "AdminGroupAcl group_id={$this->getGroupId()} key={$this->getAclKey()}";
    }

}
