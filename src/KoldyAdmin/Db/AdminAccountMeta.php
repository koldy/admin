<?php declare(strict_types=1);

namespace KoldyAdmin\Db;

use Koldy\Db\Model;
use KoldyAdmin\Db\Traits\AccountTrait;
use KoldyAdmin\Db\Traits\CreatedAtTrait;
use KoldyAdmin\Db\Traits\UpdatedAtTrait;

/**
 * Class AdminAccountMeta
 * @package KoldyAdmin\Db
 * @property int account_id
 * @property string key_name
 * @property string key_value
 * @property string created_at
 * @property string updated_at
 */
class AdminAccountMeta extends Model
{

    protected static $table = 'admin_account_meta';

    protected static $primaryKey = ['account_id', 'key_name'];

    protected static $autoIncrement = false;

    protected static $adapter = 'admin';

    use AccountTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key_name;
    }

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->key_value;
    }


}