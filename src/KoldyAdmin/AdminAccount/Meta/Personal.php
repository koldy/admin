<?php declare(strict_types=1);

namespace KoldyAdmin\AdminAccount\Meta;

use KoldyAdmin\AdminAccount\Meta;
use KoldyAdmin\Db\AdminAccount;

class Personal
{
    private $meta;

    /**
     * Personal constructor.
     *
     * @param Meta $meta
     */
    public function __construct(Meta &$meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return AdminAccount
     */
    public function getAdminAccount(): AdminAccount
    {
        return $this->meta->getAdminAccount();
    }
}