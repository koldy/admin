<?php declare(strict_types=1);

namespace KoldyAdmin\AdminAccount\Meta;

use KoldyAdmin\AdminAccount\Meta;
use KoldyAdmin\Db\AdminAccount;

class Security
{

    /**
     * @var Meta
     */
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

    /**
     * @return Meta
     */
    private function getMeta(): Meta
    {
        if (!$this->meta->isLoaded()) {
            $this->meta->load();
        }

        return $this->meta;
    }

    /**
     * @return bool
     */
    public function shouldSendEmailOnSignIn(): bool
    {
        $meta = $this->getMeta()->get('send_email_on_sign_in');

        if ($meta === null) {
            return false;
        }

        return $meta->getValue() === 'yes';
    }
}