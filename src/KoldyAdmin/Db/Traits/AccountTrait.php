<?php declare(strict_types=1);

namespace KoldyAdmin\Db\Traits;

use KoldyAdmin\Db\AdminAccount;

/**
 * Trait AccountTrait
 * @package KoldyAdmin\Db\Traits
 * @property int account_id
 */
trait AccountTrait
{

    /**
     * Get account ID
     *
     * @return int
     */
    public function getAccountId(): int
    {
        return (int)$this->account_id;
    }

    /**
     * @return bool
     */
    public function hasAccountId(): bool
    {
        return $this->account_id !== null;
    }

    /**
     * @return AdminAccount
     */
    public function getAccount(): AdminAccount
    {
        /** @var AdminAccount $adminAccount */
        $adminAccount = AdminAccount::fetchOneOrFail($this->getAccountId());
        return $adminAccount;
    }

}